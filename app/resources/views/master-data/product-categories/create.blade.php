@extends('layouts.app')

@section('title', 'Crear Categoría')

@section('content')
<h1 class="h3 mb-3">Nueva Categoría</h1>
<div class="card"><div class="card-body">
    <form method="POST" action="{{ route('master-data.product-categories.store') }}">
        @csrf
        @include('master-data.product-categories._form')
    </form>
</div></div>
@endsection
