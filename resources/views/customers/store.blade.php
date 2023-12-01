<!-- resources/views/customers/store.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>
            {{
                isset($customer->id)
                ? "Upravit zákazníka id. $customer->id"
                : 'Vytvořit zákazníka'
            }}
        </h1>

        <form action="{{ isset($customer) ? route('customers.update', $customer->id) : route('customers.store') }}" method="POST">
            @csrf

            @if(isset($customer))
                @method('PUT')
            @endif

            <table class="table">
                <tr>
                    <td><label for="name" class="form-label h4 fw-bold">Název:</label></td>
                    <td><input type="text" class="form-control" name="name" value="{{ old('name', isset($customer) ? $customer->name : '') }}" required></td>
                    <td>@error('name')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="street" class="form-label h4 fw-bold">Ulice:</label></td>
                    <td><input type="text" class="form-control" name="street" value="{{ old('street', isset($customer) ? $customer->street : '') }}"></td>
                    <td>@error('street')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="city" class="form-label h4 fw-bold">Město:</label></td>
                    <td><input type="text" class="form-control" name="city" value="{{ old('city', isset($customer) ? $customer->city : '') }}"></td>
                    <td>@error('city')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="zip" class="form-label h4 fw-bold">PSČ:</label></td>
                    <td><input type="text" class="form-control" name="zip" value="{{ old('zip', isset($customer) ? $customer->zip : '') }}"></td>
                    <td>@error('zip')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="country" class="form-label h4 fw-bold">Země:</label></td>
                    <td>
                        <select class="form-select" name="country" required>
                            @foreach(\App\Enums\Country::cases() as $country)
                                <option value="{{ $country }}" {{ old('country', isset($customer) ? $customer->country : '') === $country ? 'selected' : '' }}>{{ $country }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>@error('country')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="vat_id" class="form-label h4 fw-bold">DIČ:</label></td>
                    <td><input type="text" class="form-control" name="vat_id" value="{{ old('vat_id', isset($customer) ? $customer->vat_id : '') }}" required></td>
                    <td>@error('vat_id')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="email" class="form-label h4 fw-bold">Email:</label></td>
                    <td><input type="email" class="form-control" name="email" value="{{ old('email', isset($customer) ? $customer->email : '') }}"></td>
                    <td>@error('email')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="phone" class="form-label h4 fw-bold">Telefon:</label></td>
                    <td><input type="text" class="form-control" name="phone" value="{{ old('phone', isset($customer) ? $customer->phone : '') }}"></td>
                    <td>@error('phone')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="bank_account" class="form-label h4 fw-bold">Číslo účtu:</label></td>
                    <td><input type="text" class="form-control" name="bank_account" value="{{ old('bank_account', isset($customer) ? $customer->bank_account : '') }}"></td>
                    <td>@error('bank_account')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="bank_code" class="form-label h4 fw-bold">Kód banky:</label></td>
                    <td><input type="text" class="form-control" name="bank_code" value="{{ old('bank_code', isset($customer) ? $customer->bank_code : '') }}"></td>
                    <td>@error('bank_code')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
                <tr>
                    <td><label for="bank_name" class="form-label h4 fw-bold">Název banky:</label></td>
                    <td><input type="text" class="form-control" name="bank_name" value="{{ old('bank_name', isset($customer) ? $customer->bank_name : '') }}"></td>
                    <td>@error('bank_name')<div class="text-danger">{{ $message }}</div>@enderror</td>
                </tr>
            </table>

            <button type="submit" class="btn btn-primary">{{ isset($customer) ? 'Upravit zákazníka' : 'Vytvořit zákazníka' }}</button>
        </form>
    </div>
@endsection
