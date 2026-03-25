@extends('layouts.app')
@section('title', 'Editar Proveedor')
@section('content')
<h1 class="h3 mb-3">Editar Proveedor</h1>
<div class="card"><div class="card-body"><form method="POST" action="{{ route('master-data.suppliers.update', $supplier) }}">@csrf @method('PUT') @include('master-data.suppliers._form')</form></div></div>
@endsection
