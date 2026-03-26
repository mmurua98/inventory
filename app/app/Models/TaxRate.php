<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaxRate extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'name',
        'rate_percent',
        'is_active',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'tax_rate_id');
    }

    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class, 'default_tax_rate_id');
    }
}
