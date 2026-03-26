@extends('layouts.app')
@section('title', 'Editar Unidad')
@section('content')
<h1 class="h3 mb-3">Editar Unidad</h1>
<div class="card"><div class="card-body"><form method="POST" action="{{ route('master-data.units.update', $unit) }}">@csrf @method('PUT') @include('master-data.units._form')</form></div></div>
@endsection
