<div class="mb-3">
    <label for="name" class="form-label">Nombre</label>
    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $productCategory->name ?? '') }}" required>
</div>
<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" {{ old('is_active', $productCategory->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Activo</label>
</div>
<button type="submit" class="btn btn-primary">Guardar</button>
<a href="{{ route('master-data.product-categories.index') }}" class="btn btn-secondary">Cancelar</a>
