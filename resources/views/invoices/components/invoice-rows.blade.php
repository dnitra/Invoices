<!-- resources/views/invoices/components/invoice-row.blade.php -->

<tr>
    <input type="hidden" name="{{ $name }}[id]" value="{{ $row->id }}">
    @foreach ($fields as $field)
        <td>
            {!! $field !!}
            @error("{$name}.{$loop->parent->index}.{$field['name']}")
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </td>
    @endforeach
    <td>
        <button type="button" class="btn btn-danger btn-sm remove-row">Odstranit</button>
    </td>
</tr>
