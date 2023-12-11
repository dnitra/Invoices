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
                    <td>
                        <label for="name" class="form-label h4 fw-bold">Název:</label>
                        @error('name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </td>
                    <td>
                        <input type="text" class="form-control" name="name" value="{{ old('name', isset($customer) ? $customer->name : '') }}" required>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="street" class="form-label h4 fw-bold">Ulice:</label>
                        @error('street')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </td>
                    <td>
                        <input type="text" class="form-control" name="street" value="{{ old('street', isset($customer) ? $customer->street : '') }}">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="city" class="form-label h4 fw-bold">Město:</label>
                        @error('city')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </td>
                    <td>
                        <input type="text" class="form-control" name="city" value="{{ old('city', isset($customer) ? $customer->city : '') }}">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="zip" class="form-label h4 fw-bold">PSČ:</label>
                        @error('zip')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </td>
                    <td>
                        <input type="text" class="form-control" name="zip" value="{{ old('zip', isset($customer) ? $customer->zip : '') }}">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="country" class="form-label h4 fw-bold">Země:</label>
                        @error('country')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </td>
                    <td>
                        <select class="form-select" name="country" required>
                            @foreach(\App\Enums\Country::getCases() as $country)
                                <option value="{{ $country }}" {{ old('country', isset($customer) ? $customer->country : '') === $country ? 'selected' : '' }}>
                                    {{ $country }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="vat_id" class="form-label h4 fw-bold">DIČ:</label>
                        @error('vat_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </td>
                    <td>
                        <input type="text" class="form-control" name="vat_id" value="{{ old('vat_id', isset($customer) ? $customer->vat_id : '') }}">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="email" class="form-label h4 fw-bold">Email:</label>
                        @error('email')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </td>
                    <td>
                        <input type="email" class="form-control" name="email" value="{{ old('email', isset($customer) ? $customer->email : '') }}">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="phone" class="form-label h4 fw-bold">Telefon:</label>
                        @error('phone')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </td>
                    <td>
                        <input type="text" class="form-control" name="phone" value="{{ old('phone', isset($customer) ? $customer->phone : '') }}">
                    </td>
                </tr>
            </table>

            <button type="submit" class="btn btn-primary">{{ isset($customer) ? 'Upravit zákazníka' : 'Vytvořit zákazníka' }}</button>
        </form>
    </div>
@endsection
