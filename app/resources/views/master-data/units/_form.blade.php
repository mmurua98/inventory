<div class="mb-3">
    <label for="code" class="form-label">Código</label>
    <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $unit->code ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="name" class="form-label">Nombre</label>
    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $unit->name ?? '') }}" required>
</div>
<button type="submit" class="btn btn-primary">Guardar</button>
<a href="{{ route('master-data.units.index') }}" class="btn btn-secondary">Cancelar</a>
