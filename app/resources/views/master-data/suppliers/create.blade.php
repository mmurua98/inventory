@extends('layouts.app')
@section('title', 'Crear Proveedor')
@section('content')
<h1 class="h3 mb-3">Nuevo Proveedor</h1>
<div class="card"><div class="card-body"><form method="POST" action="{{ route('master-data.suppliers.store') }}">@csrf @include('master-data.suppliers._form')</form></div></div>
@endsection
