@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
               <h1>Seznam faktur</h1>

        </div>
        <!-- Display a table with a list of invoices -->
        <table class="table table-striped table-hover table-bordered table-sm">
            <thead>
            @if(count($invoices) === 0)
                <tr>
                    <td colspan="7">Nebyly nalezeny žádné faktury. <a href="{{ route('invoices.create') }}">Vytvořit fakturu</a></td>
                </tr>
            @else
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Odběratel</th>
                    <th scope="col">Datum vystavení</th>
                    <th scope="col">Splatnost</th>
                    <th scope="col">Částka</th>
                    <th scope="col">Stav</th>
                    <th scope="col">Akce</th>
                </tr>
            </thead>
            <tbody>
            @foreach($invoices as $invoice)
                <tr>
{{--                    <td>{{ $invoice['id'] }}</td>--}}
{{--                    <td>{{ $invoice['customer'] }}</td>--}}
{{--                    <td>{{ \Carbon\Carbon::parse($invoice['issue_date'])->format('d. m. Y') }}</td>--}}
{{--                    <td>{{ \Carbon\Carbon::parse($invoice['due_date'])->format('d. m. Y') }}</td>--}}
{{--                    <td>{{ $invoice['amount'] }} {{ $invoice['currency'] }}</td>--}}
{{--                    <td>{{ $invoice['status'] }}</td>--}}
                    <td>{{ $invoice->id }}</td>
                    <td>{{ $invoice->customer->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($invoice->issue_date)->format('d. m. Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d. m. Y') }}</td>
                    <td>{{ $invoice->amount }} {{ $invoice->currency }}</td>
                    <td>{{ $invoice->status }}</td>
                    <td>
                        <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-primary btn-sm">Upravit</a>
                        <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Jsou si jisti, že chcete smazat tuto fakturu?')">Smazat</button>
                        </form>
                        <a href="{{ route('invoices.download', $invoice->id) }}" class="btn btn-success btn-sm">Stáhnout</a>
                    </td>
                </tr>
            @endforeach
            <td colspan="7"><a href="{{ route('invoices.create') }}" class="btn btn-primary btn-sm">Vytvořit fakturu</a></td>
            @endif
            </tbody>
        </table>
    </div>
@endsection
