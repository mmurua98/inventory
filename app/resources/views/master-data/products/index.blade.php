@extends('layouts.app')
@section('title', 'Productos')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3"><h1 class="h3 mb-0">Productos</h1><a href="{{ route('master-data.products.create') }}" class="btn btn-primary">Nuevo producto</a></div>
<div class="card"><div class="table-responsive"><table class="table table-striped mb-0">
<thead><tr><th>ID</th><th>SKU</th><th>Nombre</th><th>Categoría</th><th>Unidad</th><th>Impuesto</th><th>Activo</th><th class="text-end">Acciones</th></tr></thead>
<tbody>
@forelse($products as $item)
<tr>
    <td>{{ $item->id }}</td><td>{{ $item->sku }}</td><td>{{ $item->name }}</td><td>{{ $item->category?->name }}</td><td>{{ $item->unit?->code }}</td><td>{{ $item->taxRate?->code }}</td><td>{{ $item->is_active ? 'Sí' : 'No' }}</td>
    <td class="text-end"><a href="{{ route('master-data.products.edit', $item) }}" class="btn btn-sm btn-outline-primary">Editar</a>
        <form action="{{ route('master-data.products.destroy', $item) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button></form></td>
</tr>
@empty
<tr><td colspan="8" class="text-center">No hay registros.</td></tr>
@endforelse
</tbody>
</table></div></div>
<div class="mt-3">{{ $products->links() }}</div>
@endsection
