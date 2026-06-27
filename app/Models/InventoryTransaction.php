<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryTransaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'transaction_type_id',
        'transaction_number',
        'budget',
        'realization',
        'transaction_date',
        'notes',
        'evidence',
    ];

    protected function casts(): array
    {
        return [
            'budget' => 'decimal:2',
            'realization' => 'decimal:2',
            'transaction_date' => 'date',
        ];
    }

    public function transactionType(): BelongsTo
    {
        return $this->belongsTo(TransactionType::class)->withTrashed();
    }
}
