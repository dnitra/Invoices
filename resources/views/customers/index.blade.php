<!-- resources/views/customers/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Seznam zákazníků</h1>

        <table class="table table-striped table-hover table-bordered table-sm">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Název</th>
                    <th scope="col">Email</th>
                    <th scope="col">Telefon</th>
                    <th scope="col">Akce</th>
                </tr>
            </thead>
            <tbody>
            @if($customersPresence = count($customers) !== 0)
                @foreach($customers as $customer)
                <tr>
                    <td>{{ $customer->id }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>
                        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-primary btn-sm">Upravit</a>
                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this customer?')">Smazat</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            @else
                <tr>
                    <td colspan="7">
                        Nebyli nalezeni žádní zákazníci.
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
        <div class="d-flex justify-content-end">
            <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm">Vytvořit zákazníka</a>
        </div>
    </div>
@endsection
