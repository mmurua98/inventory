@extends('layouts.app')
@section('title', 'Editar Tasa de Impuesto')
@section('content')
<h1 class="h3 mb-3">Editar Tasa de Impuesto</h1>
<div class="card"><div class="card-body"><form method="POST" action="{{ route('master-data.tax-rates.update', $taxRate) }}">@csrf @method('PUT') @include('master-data.tax-rates._form')</form></div></div>
@endsection
