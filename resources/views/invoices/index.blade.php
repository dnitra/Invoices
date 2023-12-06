@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
               <h1>Seznam faktur</h1>

        </div>
        <table class="table table-striped table-hover table-bordered table-sm">
            <thead>
                <tr>
                    <th scope="col">VS</th>
                    <th scope="col">Odběratel</th>
                    <th scope="col">Datum vystavení</th>
                    <th scope="col">Splatnost</th>
                    <th scope="col">Částka</th>
                    <th scope="col">Akce</th>
                </tr>
            </thead>
            <tbody>
            @if($invoicesPresence = count($invoices) !== 0)
                @foreach($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>
                            @if($invoice->customer)
                                <a
                                    href="{{ route('customers.edit', $invoice->customer->id) }}"
                                    class="btn btn-primary btn-sm"
                                >
                                    {{ $invoice->customer->name }}
                                </a>
                            @else
                                <span class="text-danger">Není přiřazen</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($invoice->issue_date)->format('d. m. Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d. m. Y') }}</td>
                        <td>{{ $invoice->amount }} {{ $invoice->currency }}</td>
                        <td>
                            <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-primary btn-sm">Upravit</a>
                            <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Jsou si jisti, že chcete smazat tuto fakturu?')">Smazat</button>
                            </form>
                            <a href="{{ route('invoices.download-xml', $invoice->id) }}" class="btn btn-success btn-sm">Stáhnout</a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7">Nebyly nalezeny žádné faktury.</td>
                </tr>
            @endif
            </tbody>
        </table>
        <div class="d-flex justify-content-end">
            <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-sm">Vytvořit fakturu</a>
        </div>
    </div>
@endsection
