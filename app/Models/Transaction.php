<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Transaction extends Model
{
    use HasUuids;

    protected $table = 'transactions';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    // Nonaktifkan updated_at jika tidak digunakan
    const UPDATED_AT = null;

    // Nonaktifkan timestamps agar tidak insert created_at/updated_at
    public $timestamps = false;

    protected $fillable = [
        'invoice_id',
        'payment_gateway',
        'payment_gateway_reference_id',
        'amount_paid',
        'payment_method',
        'transaction_date',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'transaction_date' => 'datetime',
    ];

    protected $attributes = [
        'payment_gateway' => 'midtrans',
    ];

    // Boot method untuk auto-fill transaction_date
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($transaction) {
            if (!$transaction->transaction_date) {
                $transaction->transaction_date = now();
            }
        });
    }

    // Relationships
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class, 'transaction_id');
    }
}
