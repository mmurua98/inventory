<div class="mb-3"><label class="form-label" for="code">Código</label><input class="form-control" id="code" name="code" value="{{ old('code', $warehouse->code ?? '') }}" required></div>
<div class="mb-3"><label class="form-label" for="name">Nombre</label><input class="form-control" id="name" name="name" value="{{ old('name', $warehouse->name ?? '') }}" required></div>
<div class="mb-3"><label class="form-label" for="location">Ubicación</label><input class="form-control" id="location" name="location" value="{{ old('location', $warehouse->location ?? '') }}"></div>
<div class="form-check mb-3"><input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" {{ old('is_active', $warehouse->is_active ?? true) ? 'checked' : '' }}><label class="form-check-label" for="is_active">Activo</label></div>
<button type="submit" class="btn btn-primary">Guardar</button> <a href="{{ route('master-data.warehouses.index') }}" class="btn btn-secondary">Cancelar</a>
