<!-- resources/views/invoices/store.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>
            {{
                isset($invoice->id)
                ? "Upravit fakturu č. " . ($invoice->invoice_number ?? 'Unknown')
                : 'Vytvořit fakturu'
            }}
        </h1>
        <form
                id="invoice-form"
                action="{{ isset($invoice->id) ? route('invoices.update', $invoice->id) : route('invoices.store') }}"
                method="POST">
            @csrf

            @if(isset($invoice->id))
                @method('PUT')
            @else
                @php
                    $invoice= new \App\Models\Invoice();
                    $invoice['rows'][0] = new \App\Models\InvoiceRow();
                @endphp
            @endif
            <table class="table table-bordered">
                <tr>
                    <td><label for="issue_date" class="form-label h4 fw-bold">Datum vystavení:</label></td>
                    <td>
                        <input type="date" class="form-control" name="issue_date" value="{{ isset($invoice->id) ? $invoice->issue_date : old('issue_date', date('Y-m-d')) }}" required>
                    </td>
                    <td>@error('issue_date')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="taxable_supply_date" class="form-label h4 fw-bold">Datum zdanitelného plnění:</label></td>
                    <td>
                        <input type="date" class="form-control" name="taxable_supply_date" value="{{ isset($invoice->id) ? $invoice->taxable_supply_date : old('taxable_supply_date', date('Y-m-d')) }}" required>
                    </td>
                    <td>@error('taxable_supply_date')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="due_date" class="form-label h4 fw-bold">Splatnost:</label></td>
                    <td>
                        <input type="date" class="form-control" name="due_date" value="{{ isset($invoice->id) ? $invoice->due_date : old('due_date', date('Y-m-d', strtotime('+14 days'))) }}" required>
                    </td>
                    <td>@error('due_date')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="currency" class="form-label h4 fw-bold">Měna:</label></td>
                    <td>
                        <select class="form-select" name="currency">
                            @foreach(\App\Enums\Currency::cases() as $currency)
                                <option value="{{ $currency }}" {{ (isset($invoice->id) && $invoice->currency == $currency) || old('currency') == $currency ? 'selected' : '' }}>
                                    {{ $currency }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>@error('currency')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="status" class="form-label h4 fw-bold">Stav:</label></td>
                    <td>
                        <select class="form-select" name="status">
                            @foreach(\App\Enums\InvoiceStatus::cases() as $status)
                                <option value="{{ $status }}" {{ (isset($invoice->id) && $invoice->status == $status) || old('status') == $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>@error('status')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="invoice_number" class="form-label h4 fw-bold">Číslo faktury:</label></td>
                    <td>
                        <input type="text" class="form-control" name="invoice_number" value="{{ isset($invoice->id) ? $invoice->invoice_number : old('invoice_number') }}">
                    </td>
                    <td>@error('invoice_number')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="customer_id" class="form-label h4 fw-bold">Zákazník:</label></td>
                    <td>
                        <select class="form-select" name="customer_id">
                            <option value="">Vyberte zákazníka</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ (isset($invoice->id) && $invoice->customer_id == $customer->id) || old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>@error('customer_id')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
            </table>

            <div id="invoice-rows" class="mb-3">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Popis</th>
                        <th>Cena za kus bez DPH</th>
                        <th>Počet kusů</th>
                        <th>DPH</th>
                        <th>Akce</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($invoice->rows as $index => $row)
                        <tr>
                            <input type="hidden" name="rows[{{ $index }}][id]" value="{{ $row->id ?? old("rows.{$index}.id", '') }}">
                            <td>
                                <input type="text" class="form-control" name="rows[{{ $index }}][text]" value="{{ old("rows.{$index}.text", $row->text ?? '') }}">
                                @error("rows.{$index}.text")
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </td>
                            <td>
                                <input type="number" class="form-control" name="rows[{{ $index }}][unit_price]" value="{{ old("rows.{$index}.unit_price", $row->unit_price ?? 0) }}">
                                @error("rows.{$index}.unit_price")
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </td>
                            <td>
                                <input type="number" class="form-control" name="rows[{{ $index }}][quantity]" value="{{ old("rows.{$index}.quantity", $row->quantity ?? 0) }}">
                                @error("rows.{$index}.quantity")
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </td>
                            <td>
                                <input type="number" class="form-control" name="rows[{{ $index }}][vat_rate]" value="{{ old("rows.{$index}.vat_rate", $row->vat_rate ?? 0) }}">
                                @error("rows.{$index}.vat_rate")
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-row">Odstranit</button>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>

                <button type="button" class="btn btn-success btn-sm add-row">Přidat řádek</button>
                <br>
                <br>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        {{ isset($invoice->id) ? 'Upravit fakturu' : 'Vytvořit fakturu' }}
                    </button>
                </div>

            </div>

        </form>
    </div>

        </form>
    </div>
@endsection
