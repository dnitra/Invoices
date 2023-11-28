<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\InvoiceRow;
use Illuminate\Http\Request;
use App\Models\Invoice; // Assuming your Invoice model is in the App\Models namespace

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::all(); // Replace this with your actual logic to fetch invoices from the database

        return view('invoices.index', [
            'invoices' => $invoices,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Add logic if needed, but in this case, we're using the same form for create and edit
        return view('invoices.store', [
            'customers' => Customer::all(), // Assuming you have a Customer model
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this->validateInvoice($request);

            // Create or update the invoice based on whether $request has 'id' field
            $invoice = Invoice::create($request->only([
                'issue_date',
                'taxable_supply_date',
                'due_date',
                'total_price',
                'currency',
                'status',
                'invoice_number',
                'oss_regime',
                'oss_info',
                'oss_country',
                'oss_vat_id',
                'oss_taxable_supply',
                'oss_taxable_supply_currency',
                'customer_id',
            ]));

            // Handle the rows associated with the invoice
            $rowsData = $request->only('rows');
            $rows = [];
            foreach ($rowsData['rows'] as $index => $row) {
                InvoiceRow::create([
                    'text' => $row['text'],
                    'quantity' => $row['quantity'],
                    'unit_price' => $row['unit_price'],
                    'vat_rate' => $row['vat_rate'],
                ]);
            }

            $invoice->rows()->createMany($rows);

            return redirect()->route('invoices.index')->with('success', 'Invoice saved successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error saving the invoice. ' . $e->getMessage());
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
            return redirect()->route('invoices.index')->with('error', 'Invoice not found.');
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
            ]);
        } catch (\Exception $e) {
            return redirect()->route('invoices.index')->with('error', 'Invoice not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $this->validateInvoice($request);
            $invoice = Invoice::findOrFail($id);
            $invoice->update($request->only([
                'id',
                'issue_date',
                'taxable_supply_date',
                'due_date',
                'total_price',
                'currency',
                'status',
                'invoice_number',
                'oss_regime',
                'oss_info',
                'oss_country',
                'oss_vat_id',
                'oss_taxable_supply',
                'oss_taxable_supply_currency',
                'customer_id',
            ]));

            // Handle the rows associated with the invoice
            $rows = $request->only('rows');
            $rowsData = $request->only('rows');
            foreach ($rowsData['rows'] as $index => $row) {
                $row = InvoiceRow::findOrFail($row['id']);
                $row->update([
                    'text' => $row['text'],
                    'quantity' => $row['quantity'],
                    'unit_price' => $row['unit_price'],
                    'vat_rate' => $row['vat_rate'],
                ]);
            }


            return redirect()->route('invoices.index')->with('success', 'Invoice saved successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error saving the invoice. ' . $e->getMessage());
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
            return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('invoices.index')->with('error', 'Error deleting the invoice. ' . $e->getMessage());
        }
    }

    /**
     * Validate the invoice request.
     */
    protected function validateInvoice(Request $request)
    {
        $request->validate([
            'issue_date' => 'required|date',
            'taxable_supply_date' => 'required|date',
            'due_date' => 'required|date',
            'currency' => 'required|in:' . implode(',', \App\Enums\Currency::getCases()),
            'status' => 'required|in:' . implode(',', \App\Enums\InvoiceStatus::getCases()),
            'invoice_number' => 'required|string',
            'oss_regime' => 'required|string',
            'oss_info' => 'required|string',
            'oss_country' => 'required|in:' . implode(',', \App\Enums\Country::getCases()),
            'oss_vat_id' => 'required|string',
            'oss_taxable_supply' => 'required|string',
            'oss_taxable_supply_currency' => 'required|in:' . implode(',', \App\Enums\Currency::getCases()),
            'customer_id' => 'required|exists:customers,id',
            'rows.*.text' => 'required|string',
            'rows.*.unit_price' => 'required|integer',
            'rows.*.quantity' => 'required|integer',
            'rows.*.vat_rate' => 'required|integer',
        ]);
    }

}
