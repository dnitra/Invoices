<!-- resources/views/customers/store.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{ isset($customer) ? route('customers.update', $customer->id) : route('customers.store') }}" method="POST">
            @csrf

            @if(isset($customer))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label for="name" class="form-label">Název:</label>
                <input type="text" class="form-control" name="name" value="{{ old('name', isset($customer) ? $customer->name : '') }}" required>
                @error('name')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="street" class="form-label">Ulice:</label>
                <input type="text" class="form-control" name="street" value="{{ old('street', isset($customer) ? $customer->street : '') }}">
                @error('street')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="city" class="form-label">Město:</label>
                <input type="text" class="form-control" name="city" value="{{ old('city', isset($customer) ? $customer->city : '') }}">
                @error('city')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="zip" class="form-label">PSČ:</label>
                <input type="text" class="form-control" name="zip" value="{{ old('zip', isset($customer) ? $customer->zip : '') }}">
                @error('zip')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="country" class="form-label">Země:</label>
                <select class="form-select" name="country" required>
                    @foreach(\App\Enums\Country::cases() as $country)
                        <option value="{{ $country }}" {{ old('country', isset($customer) ? $customer->country : '') === $country ? 'selected' : '' }}>{{ $country }}</option>
                    @endforeach
                </select>
                @error('country')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="vat_id" class="form-label">DIČ:</label>
                <input type="text" class="form-control" name="vat_id" value="{{ old('vat_id', isset($customer) ? $customer->vat_id : '') }}" required>
                @error('vat_id')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" name="email" value="{{ old('email', isset($customer) ? $customer->email : '') }}">
                @error('email')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Telefon:</label>
                <input type="text" class="form-control" name="phone" value="{{ old('phone', isset($customer) ? $customer->phone : '') }}">
                @error('phone')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="bank_account" class="form-label">Číslo účtu:</label>
                <input type="text" class="form-control" name="bank_account" value="{{ old('bank_account', isset($customer) ? $customer->bank_account : '') }}">
                @error('bank_account')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="bank_code" class="form-label">Kód banky:</label>
                <input type="text" class="form-control" name="bank_code" value="{{ old('bank_code', isset($customer) ? $customer->bank_code : '') }}">
                @error('bank_code')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="bank_name" class="form-label">Název banky:</label>
                <input type="text" class="form-control" name="bank_name" value="{{ old('bank_name', isset($customer) ? $customer->bank_name : '') }}">
                @error('bank_name')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">{{ isset($customer) ? 'Upravit zákazníka' : 'Vytvořit zákazníka' }}</button>
        </form>
    </div>
@endsection
