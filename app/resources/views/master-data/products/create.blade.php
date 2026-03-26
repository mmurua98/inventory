@extends('layouts.app')
@section('title', 'Crear Producto')
@section('content')
<h1 class="h3 mb-3">Nuevo Producto</h1>
<div class="card"><div class="card-body"><form method="POST" action="{{ route('master-data.products.store') }}">@csrf @include('master-data.products._form')</form></div></div>
@endsection
