@extends('layouts.app')

@section('title', 'Unidades')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Unidades</h1>
    <a href="{{ route('master-data.units.create') }}" class="btn btn-primary">Nueva unidad</a>
</div>
<div class="card"><div class="table-responsive">
    <table class="table table-striped mb-0">
        <thead><tr><th>ID</th><th>Código</th><th>Nombre</th><th class="text-end">Acciones</th></tr></thead>
        <tbody>
        @forelse($units as $item)
            <tr>
                <td>{{ $item->id }}</td><td>{{ $item->code }}</td><td>{{ $item->name }}</td>
                <td class="text-end">
                    <a href="{{ route('master-data.units.edit', $item) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                    <form action="{{ route('master-data.units.destroy', $item) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button></form>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center">No hay registros.</td></tr>
        @endforelse
        </tbody>
    </table>
</div></div>
<div class="mt-3">{{ $units->links() }}</div>
@endsection
