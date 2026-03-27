@extends('layouts.app')
@section('title', 'Almacenes')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3"><h1 class="h3 mb-0">Almacenes</h1><a href="{{ route('master-data.warehouses.create') }}" class="btn btn-primary">Nuevo almacén</a></div>
<div class="card"><div class="table-responsive"><table class="table table-striped mb-0"><thead><tr><th>ID</th><th>Código</th><th>Nombre</th><th>Ubicación</th><th>Activo</th><th class="text-end">Acciones</th></tr></thead><tbody>
@forelse($warehouses as $item)
<tr><td>{{ $item->id }}</td><td>{{ $item->code }}</td><td>{{ $item->name }}</td><td>{{ $item->location }}</td><td>{{ $item->is_active ? 'Sí' : 'No' }}</td><td class="text-end"><a href="{{ route('master-data.warehouses.edit', $item) }}" class="btn btn-sm btn-outline-primary">Editar</a> <form action="{{ route('master-data.warehouses.destroy', $item) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit">Eliminar</button></form></td></tr>
@empty
<tr><td colspan="6" class="text-center">No hay registros.</td></tr>
@endforelse
</tbody></table></div></div>
<div class="mt-3">{{ $warehouses->links() }}</div>
@endsection
