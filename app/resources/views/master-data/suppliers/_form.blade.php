<div class="row g-3">
    <div class="col-md-4"><label class="form-label" for="supplier_code">Código</label><input class="form-control" id="supplier_code" name="supplier_code" value="{{ old('supplier_code', $supplier->supplier_code ?? '') }}" @if(isset($supplier) && $supplier->exists) readonly @endif required></div>
    <div class="col-md-8"><label class="form-label" for="legal_name">Razón social</label><input class="form-control" id="legal_name" name="legal_name" value="{{ old('legal_name', $supplier->legal_name ?? '') }}" required></div>
    <div class="col-md-6"><label class="form-label" for="trade_name">Nombre comercial</label><input class="form-control" id="trade_name" name="trade_name" value="{{ old('trade_name', $supplier->trade_name ?? '') }}"></div>
    <div class="col-md-6"><label class="form-label" for="tax_id">RFC/NIF</label><input class="form-control" id="tax_id" name="tax_id" value="{{ old('tax_id', $supplier->tax_id ?? '') }}"></div>
    <div class="col-md-6"><label class="form-label" for="email">Correo</label><input type="email" class="form-control" id="email" name="email" value="{{ old('email', $supplier->email ?? '') }}"></div>
    <div class="col-md-6"><label class="form-label" for="phone">Teléfono</label><input class="form-control" id="phone" name="phone" value="{{ old('phone', $supplier->phone ?? '') }}"></div>
    <div class="col-12"><label class="form-label" for="address_line">Dirección</label><input class="form-control" id="address_line" name="address_line" value="{{ old('address_line', $supplier->address_line ?? '') }}"></div>
    <div class="col-md-3"><label class="form-label" for="city">Ciudad</label><input class="form-control" id="city" name="city" value="{{ old('city', $supplier->city ?? '') }}"></div>
    <div class="col-md-3"><label class="form-label" for="state">Estado</label><input class="form-control" id="state" name="state" value="{{ old('state', $supplier->state ?? '') }}"></div>
    <div class="col-md-3"><label class="form-label" for="country">País</label><input class="form-control" id="country" name="country" value="{{ old('country', $supplier->country ?? '') }}"></div>
    <div class="col-md-3"><label class="form-label" for="postal_code">Código postal</label><input class="form-control" id="postal_code" name="postal_code" value="{{ old('postal_code', $supplier->postal_code ?? '') }}"></div>
    <div class="col-md-6">
        <label class="form-label" for="default_tax_rate_id">Impuesto predeterminado</label>
        <select class="form-select" id="default_tax_rate_id" name="default_tax_rate_id">
            <option value="">Sin impuesto predeterminado</option>
            @foreach($taxRates as $taxRate)
                <option value="{{ $taxRate->id }}" @selected((string) old('default_tax_rate_id', $supplier->default_tax_rate_id ?? '') === (string) $taxRate->id)>
                    {{ $taxRate->name }} ({{ $taxRate->code }})
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 d-flex align-items-end"><div class="form-check mb-2"><input type="hidden" name="is_active" value="0"><input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" {{ old('is_active', $supplier->is_active ?? true) ? 'checked' : '' }}><label class="form-check-label" for="is_active">Activo</label></div></div>
</div>
<div class="mt-3"><button class="btn btn-primary" type="submit">Guardar</button> <a class="btn btn-secondary" href="{{ route('master-data.suppliers.index') }}">Cancelar</a></div>
