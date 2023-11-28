@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ isset($invoice) ? 'Upravit fakturu' : 'Vytvořit fakturu' }}</h1>

        <form
            id="invoice-form"
            action="{{ isset($invoice) ? route('invoices.update', $invoice->id) : route('invoices.store') }}"
            method="POST">
            @csrf

            @if(isset($invoice))
                @method('PUT')
            @endif

            <!-- Issue Date -->
            <div class="mb-3">
                <label for="issue_date" class="form-label">Datum vystavení:</label>
                <input type="date" class="form-control" name="issue_date" value="{{ isset($invoice) ? $invoice->issue_date : old('issue_date') }}" required>
                @error('issue_date')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Taxable Supply Date -->
            <div class="mb-3">
                <label for="taxable_supply_date" class="form-label">Datum zdanitelného plnění:</label>
                <input type="date" class="form-control" name="taxable_supply_date" value="{{ isset($invoice) ? $invoice->taxable_supply_date : old('taxable_supply_date') }}" required>
                @error('taxable_supply_date')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Due Date -->
            <div class="mb-3">
                <label for="due_date" class="form-label">Splatnost:</label>
                <input type="date" class="form-control" name="due_date" value="{{ isset($invoice) ? $invoice->due_date : old('due_date') }}" required>
                @error('due_date')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Currency -->
            <div class="mb-3">
                <label for="currency" class="form-label">Měna:</label>
                <select class="form-select" name="currency" required>
                    @foreach(\App\Enums\Currency::cases() as $currency)
                        <option value="{{ $currency }}" {{ isset($invoice) && $invoice->currency == $currency ? 'selected' : '' }}>{{ $currency }}</option>
                    @endforeach
                </select>
                @error('currency')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label for="status" class="form-label">Stav:</label>
                <select class="form-select" name="status" required>
                    @foreach(\App\Enums\InvoiceStatus::cases() as $status)
                        <option value="{{ $status }}" {{ isset($invoice) && $invoice->status == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
                @error('status')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Invoice Number -->
            <div class="mb-3">
                <label for="invoice_number" class="form-label">Číslo faktury:</label>
                <input type="text" class="form-control" name="invoice_number" value="{{ isset($invoice) ? $invoice->invoice_number : old('invoice_number') }}" required>
                @error('invoice_number')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- OSS Regime -->
            <div class="mb-3">
                <label for="oss_regime" class="form-label">Režim OSS:</label>
                <input type="text" class="form-control" name="oss_regime" value="{{ isset($invoice) ? $invoice->oss_regime : old('oss_regime') }}" required>
                @error('oss_regime')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- OSS Info -->
            <div class="mb-3">
                <label for="oss_info" class="form-label">Informace o režimu OSS:</label>
                <input type="text" class="form-control" name="oss_info" value="{{ isset($invoice) ? $invoice->oss_info : old('oss_info') }}" required>
                @error('oss_info')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- OSS Country -->
            <div class="mb-3">
                <label for="oss_country" class="form-label">Země pro OSS:</label>
                <select class="form-select" name="oss_country" required>
                    @foreach(\App\Enums\Country::cases() as $country)
                        <option value="{{ $country }}" {{ isset($invoice) && $invoice->oss_country == $country ? 'selected' : '' }}>{{ $country }}</option>
                    @endforeach
                </select>
                @error('oss_country')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- OSS VAT ID -->
            <div class="mb-3">
                <label for="oss_vat_id" class="form-label">DIČ pro OSS:</label>
                <input type="text" class="form-control" name="oss_vat_id" value="{{ isset($invoice) ? $invoice->oss_vat_id : old('oss_vat_id') }}" required>
                @error('oss_vat_id')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- OSS Taxable Supply -->
            <div class="mb-3">
                <label for="oss_taxable_supply" class="form-label">OSS zdanitelné plnění:</label>
                <input type="text" class="form-control" name="oss_taxable_supply" value="{{ isset($invoice) ? $invoice->oss_taxable_supply : old('oss_taxable_supply') }}" required>
                @error('oss_taxable_supply')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- OSS Taxable Supply Currency -->
            <div class="mb-3">
                <label for="oss_taxable_supply_currency" class="form-label">Měna OSS zdanitelného plnění:</label>
                <select class="form-select" name="oss_taxable_supply_currency" required>
                    @foreach(\App\Enums\Currency::cases() as $currency)
                        <option value="{{ $currency }}" {{ isset($invoice) && $invoice->oss_taxable_supply_currency == $currency ? 'selected' : '' }}>{{ $currency }}</option>
                    @endforeach
                </select>
                @error('oss_taxable_supply_currency')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Customer -->
            <div class="mb-3">
                <label for="customer_id" class="form-label">Zákazník:</label>
                <select class="form-select" name="customer_id" required>
                    <option value="">Vyberte zákazníka</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ isset($invoice) && $invoice->customer_id == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
                @error('customer_id')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Invoice Rows -->
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
                    @if(isset($invoice))
                        @foreach($invoice->rows as $row)
                            <tr>
                                <input type="hidden" name="rows[{{ $loop->index }}][id]" value="{{ $row->id }}">
                                <td>
                                    <input type="text" class="form-control" name="rows[{{ $loop->index }}][text]" value="{{ $row->text }}" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="rows[{{ $loop->index }}][unit_price]" value="{{ $row->unit_price }}" required>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="rows[{{ $loop->index }}][quantity]" value="{{ $row->quantity }}" required>
                                </td>
                                <td>
                                    <select class="form-select" name="rows[{{ $loop->index }}][vat_rate]" required>
                                        @foreach(\App\Enums\VatRate::cases() as $vatRate)
                                            <option value="{{ $vatRate }}" {{ $row->vat_rate == $vatRate ? 'selected' : '' }}>{{ $vatRate }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Odstranit</button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>
                                <input type="text" class="form-control" name="rows[0][text]" required>
                            </td>
                            <td>
                                <input type="number" class="form-control" name="rows[0][unit_price]" required>
                            </td>
                            <td>
                                <input type="number" class="form-control" name="rows[0][quantity]" required>
                            </td>
                            <td>
                                <select class="form-select" name="rows[0][vat_rate]" required>
                                    @foreach(\App\Enums\VatRate::cases() as $vatRate)
                                        <option value="{{ $vatRate }}">{{ $vatRate }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Odstranit</button>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td  class="text-center">
                            <button type="button" class="btn btn-success btn-sm" onclick="addRow()">Přidat řádek</button>
                        </td>
                    </tr>
                    </tbody>
                </table>


                <br>
                <button type="submit" class="btn btn-primary">
                    {{ isset($invoice) ? 'Upravit' : 'Vytvořit' }}
                </button>

            </div>

        </form>
    </div>
@endsection

