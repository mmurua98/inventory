@extends('layouts.app')
@section('title', 'Crear Unidad')
@section('content')
<h1 class="h3 mb-3">Nueva Unidad</h1>
<div class="card"><div class="card-body"><form method="POST" action="{{ route('master-data.units.store') }}">@csrf @include('master-data.units._form')</form></div></div>
@endsection
