<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Datos Maestros')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('master-data.product-categories.index') }}">Fase 1 · Datos Maestros</a>
        <div class="navbar-nav">
            <a class="nav-link" href="{{ route('master-data.product-categories.index') }}">Categorías</a>
            <a class="nav-link" href="{{ route('master-data.units.index') }}">Unidades</a>
            <a class="nav-link" href="{{ route('master-data.tax-rates.index') }}">Impuestos</a>
            <a class="nav-link" href="{{ route('master-data.suppliers.index') }}">Proveedores</a>
            <a class="nav-link" href="{{ route('master-data.products.index') }}">Productos</a>
            <a class="nav-link" href="{{ route('master-data.warehouses.index') }}">Almacenes</a>
        </div>
    </div>
</nav>

<main class="container pb-5">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <p class="mb-2"><strong>Se encontraron errores de validación:</strong></p>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>
</body>
</html>
