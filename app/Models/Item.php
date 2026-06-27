<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'item_type_id',
        'code',
        'name',
        'unit',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function itemType(): BelongsTo
    {
        return $this->belongsTo(ItemType::class)->withTrashed();
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }
}
