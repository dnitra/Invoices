<?php

namespace App\Http\Controllers;

use App\Enums\Country;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('customers.index', [
            'customers' => Customer::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.store');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = $this->validateCustomer($request);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $customer = Customer::create($request->all());
            return redirect()->route('customers.index')->with('success', 'Zákazník byl úspěšně vytvořen.');
        } catch (\Exception $e) {
            return redirect()->route('customers.index')->with('error', 'Chyba při vytváření zákazníka. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource as JSON in this case.
     */
    public function show(string $id)
    {
        return Customer::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('customers.store', [
            'customer' => Customer::findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validator = $this->validateCustomer($request);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $customer = Customer::findOrFail($id);
            $customer->update($request->all());

            return redirect()->route('customers.index')->with('success', 'Zákazník byl úspěšně upraven.');
        } catch (\Exception $e) {
            return redirect()->route('customers.index')->with('error', 'Chyba při úpravě zákazníka. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();

            return redirect()->route('customers.index')->with('success', 'Zákazník byl úspěšně smazán.');
        } catch (\Exception $e) {
            return redirect()->route('customers.index')->with('error', 'Chyba při mazání zákazníka. ' . $e->getMessage());
        }
    }

    protected function validateCustomer(Request $request)
    {

        $messages = [
            'name.required' => 'Název je povinný.',
            'country.required' => 'Země je povinná.',
            'vat_id.required' => 'DIČ je povinné.',
            'vat_id.unique' => 'Zadané DIČ již existuje.',
            'vat_id.vat' => 'Zadané DIČ není platné.',
        ];
        //+ add custom validation rule with ibercodes\vat\validator

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|string',
            'street' => 'nullable|string',
            'city' => 'nullable|string',
            'zip' => 'nullable|string',
            'country' => 'required|in:' . implode(',', Country::getCases()),
            'vat_id' => 'required|string|unique:customers,vat_id',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'bank_account' => 'nullable|string',
            'bank_code' => 'nullable|string',
            'bank_name' => 'nullable|string',
        ], $messages);
        $validator->after(function ($validator) use ($request) {
            $vatValidator = new \Ibericode\Vat\Validator();
            if (!$vatValidator->validateVatNumberFormat($request->vat_id)) {
                $validator->errors()->add('vat_id', 'Špatný formát DIČ.');
            }
//            if (!$vatValidator->validateVatNumber($request->vat_id)) {
//                $validator->errors()->add('vat_id', 'DIČ není platné.');
//            }
        });
        return $validator;
    }
}
