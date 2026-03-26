@extends('layouts.app')

@section('title', 'Categorías de Producto')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Categorías de Producto</h1>
    <a href="{{ route('master-data.product-categories.create') }}" class="btn btn-primary">Nueva categoría</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Activo</th>
                <th class="text-end">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse($productCategories as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->is_active ? 'Sí' : 'No' }}</td>
                    <td class="text-end">
                        <a href="{{ route('master-data.product-categories.edit', $item) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                        <form action="{{ route('master-data.product-categories.destroy', $item) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">No hay registros.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $productCategories->links() }}</div>
@endsection
