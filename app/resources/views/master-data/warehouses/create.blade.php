@extends('layouts.app')
@section('title', 'Crear Almacén')
@section('content')
<h1 class="h3 mb-3">Nuevo Almacén</h1>
<div class="card"><div class="card-body"><form method="POST" action="{{ route('master-data.warehouses.store') }}">@csrf @include('master-data.warehouses._form')</form></div></div>
@endsection
