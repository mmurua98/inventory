@extends('layouts.app')
@section('title', 'Editar Almacén')
@section('content')
<h1 class="h3 mb-3">Editar Almacén</h1>
<div class="card"><div class="card-body"><form method="POST" action="{{ route('master-data.warehouses.update', $warehouse) }}">@csrf @method('PUT') @include('master-data.warehouses._form')</form></div></div>
@endsection
