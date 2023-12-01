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
            $this->validateCustomer($request);
            $customer = Customer::create($request->all());
            return redirect()->route('customers.index')->with('success', 'Zákazník byl úspěšně vytvořen.');
        } catch (\Exception $e) {
            return redirect()->route('customers.index')->with('error', 'Chyba při vytváření zákazníka. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

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
            $this->validateCustomer($request);
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
         $request->validate([
            'name' => 'required|string',
            'street' => 'nullable|string',
            'city' => 'nullable|string',
            'zip' => 'nullable|string',
            'country' => 'required|in:' . implode(',', Country::getCases()),
            'vat_id' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'bank_account' => 'nullable|string',
            'bank_code' => 'nullable|string',
            'bank_name' => 'nullable|string',
        ]);

         $validator = new \Ibericode\Vat\Validator();

        if (!$validator->validateVatNumberFormat($request->vat_id)) {
            throw new \Exception('Špatný formát DIČ.');
        }
        if (!$validator->validateVatNumber($request->vat_id)) {
            throw new \Exception('DIČ nenalezeno.');
        }
    }
}
