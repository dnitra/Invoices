<?php

namespace App\Http\Controllers;

use App\Actions\XmlGenerator;
use App\Models\Customer;
use App\Models\InvoiceRow;
use Illuminate\Http\Request;
use App\Models\Invoice;


class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with('rows')->get();
        return view('invoices.index', [
            'invoices' => $invoices,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('invoices.store', [
            'customers' => Customer::all(['id', 'name']),
            'vatRates' => $this->getVatRates('CZ'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            $validator = $this->createInvoiceValidator($request);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $invoice = Invoice::create($request->only([
                'issue_date',
                'taxable_supply_date',
                'due_date',
                'total_price',
                'currency',
                'status',
                'invoice_number',
                'customer_id',
                'oss',
                'invoice_text'
            ]));

            $rowsData = $request->only('rows');
            $rows = [];
            foreach ($rowsData['rows'] as $index => $row) {
                InvoiceRow::create([
                    'invoice_id' => $invoice->id,
                    'text' => $row['text'],
                    'quantity' => $row['quantity'],
                    'unit_price' => $row['unit_price'],
                    'vat_rate' => $row['vat_rate'],
                ]);
            }

            $invoice->rows()->createMany($rows);

            return redirect()->route('invoices.index')->with('success', "Faktura č. $invoice->invoice_number byla úspěšně vytvořena.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Chyba při vytváření faktury. ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $invoice = Invoice::findOrFail($id);

            return view('invoices.show', [
                'invoice' => $invoice,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('invoices.index')->with('error', 'Faktura nebyla nalezena.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $invoice = Invoice::findOrFail($id);

            return view('invoices.store', [
                'invoice' => $invoice,
                'customers' => Customer::all(),
                'vatRates' => $this->getVatRates($invoice->customer->country),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('invoices.index')->with('error', 'Faktura nebyla nalezena.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validator = $this->createInvoiceValidator($request);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $invoice = Invoice::findOrFail($id);
            $this->updateInvoice($invoice, $request);

            $rowsData = $request->only('rows');

            $this->deleteUnselectedRows($invoice, $rowsData);
            $this->updateOrCreateRows($invoice, $rowsData['rows']);

            return redirect()->route('invoices.index')->with('success', "Faktura č. $invoice->invoice_number byla úspěšně upravena.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Chyba při úpravě faktury. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            $invoice->rows()->delete();
            $invoice->delete();
            return redirect()->route('invoices.index')->with('success', "Faktura č. $invoice->invoice_number byla úspěšně smazána.");
        } catch (\Exception $e) {
            return redirect()->route('invoices.index')->with('error', 'Chyba při mazání faktury. ' . $e->getMessage());
        }
    }

    /**
     * Download the invoice XML.
     */
    public function downloadInvoiceXml($invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        $xmlFilePath = XmlGenerator::generateInvoiceXml($invoice);

        return response()->download(storage_path("app/$xmlFilePath"));
    }
    /**
     * Validate the invoice request.
     */
    protected function createInvoiceValidator(Request $request)
    {
        $messages = [
            'issue_date.required' => 'Prosím vyplňte datum vystavení.',
            'issue_date.date' => 'Prosím vyplňte platné datum vystavení.',
            'taxable_supply_date.required' => 'Prosím vyplňte datum zdanitelného plnění.',
            'taxable_supply_date.date' => 'Prosím vyplňte platné datum zdanitelného plnění.',
            'due_date.required' => 'Prosím vyplňte datum splatnosti.',
            'due_date.date' => 'Prosím vyplňte platné datum splatnosti.',
            'invoice_text.required' => 'Prosím vyplňte text faktury.',
            'invoice_text.max' => 'Prosím vyplňte maximálně 240 znaků.',
            'currency.required' => 'Prosím vyplňte měnu.',
            'currency.in' => 'Prosím vyplňte platnou měnu.',
            'status.required' => 'Prosím vyplňte stav faktury.',
            'status.in' => 'Prosím vyplňte platný stav faktury.',
            'invoice_number.required' => 'Prosím vyplňte číslo faktury.',
            'invoice_number.string' => 'Prosím vyplňte platné číslo faktury.',
            'customer_id.exists' => 'Prosím vyberte platného zákazníka.',
            'rows.*.text.required' => 'Prosím vyplňte text položky.',
            'rows.*.text.string' => 'Prosím vyplňte platný text položky.',
            'rows.*.unit_price.required' => 'Prosím vyplňte cenu položky.',
            'rows.*.unit_price.integer' => 'Prosím vyplňte platnou cenu položky.',
            'rows.*.quantity.required' => 'Prosím vyplňte množství položky.',
            'rows.*.quantity.integer' => 'Prosím vyplňte platné množství položky.',
            'rows.*.vat_rate.required' => 'Prosím vyplňte sazbu DPH položky.',
            'rows.*.vat_rate.integer' => 'Prosím vyplňte platnou sazbu DPH položky.',
        ];

        $validator = \Validator::make($request->all(), [
            'issue_date' => 'required|date',
            'taxable_supply_date' => 'required|date',
            'due_date' => 'required|date',
            'invoice_text' => 'required|string|max:240',
            'currency' => 'required|in:' . implode(',', \App\Enums\Currency::getCases()),
            'invoice_number' => 'required|string',
            'customer_id' => 'exists:customers,id',
            'rows' => 'required|array|min:1',
            'rows.*.id' => 'nullable|exists:invoice_rows,id',
            'rows.*.text' => 'required|string',
            'rows.*.unit_price' => 'required|integer',
            'rows.*.quantity' => 'required|integer',
            'rows.*.vat_rate' => 'required|integer',
        ], $messages);

        return $validator;
    }
    private function updateInvoice(Invoice $invoice, Request $request)
    {
        $invoice->update($request->only([
            'id',
            'issue_date',
            'taxable_supply_date',
            'due_date',
            'total_price',
            'currency',
            'status',
            'invoice_number',
            'customer_id',
            'oss',
            'invoice_text'
        ]));
    }

    private function deleteUnselectedRows(Invoice $invoice, array $rowsData)
    {
        $rowIds = array_column($rowsData['rows'], 'id');
        $invoice->rows()->whereNotIn('id', $rowIds)->delete();
    }

    private function updateOrCreateRows(Invoice $invoice, array $rowsData)
    {
        foreach ($rowsData as $row) {
            $rowId = $row['id'] ?? null;

            if ($rowId) {
                $this->updateRow($rowId, $row);
            } else {
                $this->createRow($invoice, $row);
            }
        }
    }

    private function updateRow(int $rowId, array $row)
    {
        $invoiceRow = InvoiceRow::findOrFail($rowId);
        $this->fillRow($invoiceRow, $row);
        $invoiceRow->save();
    }

    private function createRow(Invoice $invoice, array $row)
    {
        $invoiceRow = new InvoiceRow();
        $this->fillRow($invoiceRow, $row);
        $invoice->rows()->save($invoiceRow);
    }

    private function fillRow(InvoiceRow $invoiceRow, array $row)
    {
        $invoiceRow->fill([
            'text' => $row['text'],
            'quantity' => $row['quantity'],
            'unit_price' => $row['unit_price'],
            'vat_rate' => $row['vat_rate'],
        ]);
    }

    public function getVatRates(string $country)
    {
        $vatRates = [];
        switch ($country) {
            case 'CZ':
                $vatRates = \App\Enums\VatCountries\CzVat::getCases();
                break;
            case 'PL':
                $vatRates = \App\Enums\VatCountries\PlVat::getCases();
                break;
            case 'SK':
                $vatRates = \App\Enums\VatCountries\SkVat::getCases();
                break;
        }
        return $vatRates;
    }
}
