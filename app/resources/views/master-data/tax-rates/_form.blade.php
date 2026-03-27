<div class="mb-3">
    <label class="form-label" for="code">Código</label>
    <input type="text" id="code" name="code" class="form-control" value="{{ old('code', $taxRate->code ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label" for="name">Nombre</label>
    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $taxRate->name ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label" for="rate_percent">Porcentaje</label>
    <input type="number" step="0.01" min="0" id="rate_percent" name="rate_percent" class="form-control" value="{{ old('rate_percent', $taxRate->rate_percent ?? '') }}" required>
</div>
<div class="form-check mb-3">
    <input type="hidden" name="is_active" value="0">
    <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" {{ old('is_active', $taxRate->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Activo</label>
</div>
<button class="btn btn-primary" type="submit">Guardar</button>
<a class="btn btn-secondary" href="{{ route('master-data.tax-rates.index') }}">Cancelar</a>
