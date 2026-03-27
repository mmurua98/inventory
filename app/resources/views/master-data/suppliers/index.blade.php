@extends('layouts.app')
@section('title', 'Proveedores')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3"><h1 class="h3 mb-0">Proveedores</h1><a href="{{ route('master-data.suppliers.create') }}" class="btn btn-primary">Nuevo proveedor</a></div>
<div class="card"><div class="table-responsive"><table class="table table-striped mb-0">
<thead><tr><th>ID</th><th>Código</th><th>Razón social</th><th>Impuesto</th><th>Activo</th><th class="text-end">Acciones</th></tr></thead>
<tbody>
@forelse($suppliers as $item)
<tr>
    <td>{{ $item->id }}</td><td>{{ $item->supplier_code }}</td><td>{{ $item->legal_name }}</td><td>{{ $item->defaultTaxRate?->code ?? 'N/A' }}</td><td>{{ $item->is_active ? 'Sí' : 'No' }}</td>
    <td class="text-end"><a href="{{ route('master-data.suppliers.edit', $item) }}" class="btn btn-sm btn-outline-primary">Editar</a>
        <form action="{{ route('master-data.suppliers.destroy', $item) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button></form></td>
</tr>
@empty
<tr><td colspan="6" class="text-center">No hay registros.</td></tr>
@endforelse
</tbody>
</table></div></div>
<div class="mt-3">{{ $suppliers->links() }}</div>
@endsection
