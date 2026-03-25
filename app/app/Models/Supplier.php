<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supplier extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'supplier_code',
        'legal_name',
        'trade_name',
        'tax_id',
        'email',
        'phone',
        'address_line',
        'city',
        'state',
        'country',
        'postal_code',
        'default_tax_rate_id',
        'is_active',
    ];

    public function defaultTaxRate(): BelongsTo
    {
        return $this->belongsTo(TaxRate::class, 'default_tax_rate_id');
    }
}
