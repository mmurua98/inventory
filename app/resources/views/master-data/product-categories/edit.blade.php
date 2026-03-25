@extends('layouts.app')

@section('title', 'Editar Categoría')

@section('content')
<h1 class="h3 mb-3">Editar Categoría</h1>
<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('master-data.product-categories.update', $productCategory) }}">
        @csrf
        @method('PUT')
        @include('master-data.product-categories._form')
    </form>
</div></div>
@endsection
