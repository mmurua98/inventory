@extends('layouts.app')
@section('title', 'Crear Tasa de Impuesto')
@section('content')
<h1 class="h3 mb-3">Nueva Tasa de Impuesto</h1>
<div class="card"><div class="card-body"><form method="POST" action="{{ route('master-data.tax-rates.store') }}">@csrf @include('master-data.tax-rates._form')</form></div></div>
@endsection
