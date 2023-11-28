<!-- resources/views/customers/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Seznam zákazníků</h1>

        <!-- Display a table with a list of customers -->
        <table class="table table-striped table-hover table-bordered table-sm">
            <thead>
            @if(count($customers) === 0)
                <tr>
                    <td>Nebyli nalezeni žádní zákazníci.</td>
                    <td><a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm">Vytvořit zákazníka</a></td>
                </tr>
            @else
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Název</th>
                    <th scope="col">Email</th>
                    <th scope="col">Telefon</th>
                    <th scope="col">Akce</th>
                </tr>
            </thead>
            <tbody>
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
            @endif
            </tbody>
        </table>
    </div>
@endsection
