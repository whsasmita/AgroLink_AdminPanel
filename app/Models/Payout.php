<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    use HasUuids;

    protected $table = 'payouts';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'transaction_id',
        'payee_id',
        'payee_type',
        'amount',
        'status',
        'released_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'released_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'pending_disbursement',
    ];

    // Boot method untuk auto-fill released_at
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($payout) {
            if (!$payout->released_at) {
                $payout->released_at = now();
            }
        });
    }

    // Relationships
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'payee_id')
            ->where('payee_type', 'worker');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'payee_id')
            ->where('payee_type', 'driver');
    }

    // Polymorphic relationship alternative
    public function payee()
    {
        if ($this->payee_type === 'worker') {
            return $this->worker();
        } elseif ($this->payee_type === 'driver') {
            return $this->driver();
        }
        return null;
    }
}
