<!-- resources/views/invoices/store.blade.php -->
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

            <!-- General Invoice Data Table -->
            <table class="table table-bordered">
                <tr>
                    <td><label for="issue_date" class="form-label h4 fw-bold">Datum vystavení:</label></td>
                    <td><input type="date" class="form-control" name="issue_date" value="{{ isset($invoice) ? $invoice->issue_date : old('issue_date') }}" required></td>
                    <td>@error('issue_date')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="taxable_supply_date" class="form-label h4 fw-bold">Datum zdanitelného plnění:</label></td>
                    <td><input type="date" class="form-control" name="taxable_supply_date" value="{{ isset($invoice) ? $invoice->taxable_supply_date : old('taxable_supply_date') }}" required></td>
                    <td>@error('taxable_supply_date')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="due_date" class="form-label h4 fw-bold">Splatnost:</label></td>
                    <td><input type="date" class="form-control" name="due_date" value="{{ isset($invoice) ? $invoice->due_date : old('due_date') }}" required></td>
                    <td>@error('due_date')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="currency" class="form-label h4 fw-bold">Měna:</label></td>
                    <td>
                        <select class="form-select" name="currency" required>
                            @foreach(\App\Enums\Currency::cases() as $currency)
                                <option value="{{ $currency }}" {{ isset($invoice) && $invoice->currency == $currency ? 'selected' : '' }}>{{ $currency }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>@error('currency')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="status" class="form-label h4 fw-bold">Stav:</label></td>
                    <td>
                        <select class="form-select" name="status" required>
                            @foreach(\App\Enums\InvoiceStatus::cases() as $status)
                                <option value="{{ $status }}" {{ isset($invoice) && $invoice->status == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>@error('status')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="invoice_number" class="form-label h4 fw-bold">Číslo faktury:</label></td>
                    <td><input type="text" class="form-control" name="invoice_number" value="{{ isset($invoice) ? $invoice->invoice_number : old('invoice_number') }}" required></td>
                    <td>@error('invoice_number')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="oss_regime" class="form-label h4 fw-bold">Režim OSS:</label></td>
                    <td><input type="text" class="form-control" name="oss_regime" value="{{ isset($invoice) ? $invoice->oss_regime : old('oss_regime') }}" required></td>
                    <td>@error('oss_regime')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="oss_info" class="form-label h4 fw-bold">Informace o režimu OSS:</label></td>
                    <td><input type="text" class="form-control" name="oss_info" value="{{ isset($invoice) ? $invoice->oss_info : old('oss_info') }}" required></td>
                    <td>@error('oss_info')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="oss_country" class="form-label h4 fw-bold">Země pro OSS:</label></td>
                    <td>
                        <select class="form-select" name="oss_country" required>
                            @foreach(\App\Enums\Country::cases() as $country)
                                <option value="{{ $country }}" {{ isset($invoice) && $invoice->oss_country == $country ? 'selected' : '' }}>{{ $country }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>@error('oss_country')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="oss_vat_id" class="form-label h4 fw-bold">DIČ pro OSS:</label></td>
                    <td><input type="text" class="form-control" name="oss_vat_id" value="{{ isset($invoice) ? $invoice->oss_vat_id : old('oss_vat_id') }}" required></td>
                    <td>@error('oss_vat_id')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="oss_taxable_supply" class="form-label h4 fw-bold">OSS zdanitelné plnění:</label></td>
                    <td><input type="text" class="form-control" name="oss_taxable_supply" value="{{ isset($invoice) ? $invoice->oss_taxable_supply : old('oss_taxable_supply') }}" required></td>
                    <td>@error('oss_taxable_supply')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="oss_taxable_supply_currency" class="form-label h4 fw-bold">Měna OSS zdanitelného plnění:</label></td>
                    <td>
                        <select class="form-select" name="oss_taxable_supply_currency" required>
                            @foreach(\App\Enums\Currency::cases() as $currency)
                                <option value="{{ $currency }}" {{ isset($invoice) && $invoice->oss_taxable_supply_currency == $currency ? 'selected' : '' }}>{{ $currency }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>@error('oss_taxable_supply_currency')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="customer_id" class="form-label h4 fw-bold">Zákazník:</label></td>
                    <td>
                        <select class="form-select" name="customer_id" required>
                            <option value="">Vyberte zákazníka</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ isset($invoice) && $invoice->customer_id == $customer->id ? 'selected' : '' }}>
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
                                    <button type="button" class="btn btn-danger btn-sm remove-row">Odstranit</button>
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
                                <button type="button" class="btn btn-danger btn-sm remove-row">Odstranit</button>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
                <button type="button" class="btn btn-success btn-sm add-row">Přidat řádek</button>

                <br>
                <br>
{{--                <button type="submit" class="btn btn-primary"> alignf rigt --}}
{{--                    {{ isset($invoice) ? 'Upravit fakturu' : 'Vytvořit fakturu' }}--}}
{{--                </button>--}}
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        {{ isset($invoice) ? 'Upravit fakturu' : 'Vytvořit fakturu' }}
                    </button>
                </div>

            </div>

        </form>
    </div>

        </form>
    </div>
@endsection
