@extends('layouts.app')
@section('title', 'Editar Producto')
@section('content')
<h1 class="h3 mb-3">Editar Producto</h1>
<div class="card"><div class="card-body"><form method="POST" action="{{ route('master-data.products.update', $product) }}">@csrf @method('PUT') @include('master-data.products._form')</form></div></div>
@endsection
