<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryMovement extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'movement_number',
        'movement_type_id',
        'occurred_at',
        'warehouse_id',
        'reference_type',
        'reference_id',
        'status',
        'notes',
        'created_by_user_id',
    ];

    public function movementType(): BelongsTo
    {
        return $this->belongsTo(MovementType::class, 'movement_type_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function lines(): HasMany
    {
        return $this->hasMany(InventoryMovementLine::class, 'inventory_movement_id');
    }
}
