<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


/**
 * User Model
 * Main user table untuk semua role
 */
class User extends Authenticatable
{
    use HasUuids, Notifiable, HasFactory;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'role',
        'profile_picture',
        'is_active',
        'email_verified',
        'phone_verified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'email_verified' => 'boolean',
        'phone_verified' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    protected $attributes = [
        'is_active' => true,
        'email_verified' => false,
        'phone_verified' => false,
    ];

    // Relationships
    public function farmer(): HasOne
    {
        return $this->hasOne(Farmer::class, 'user_id');
    }

    public function worker(): HasOne
    {
        return $this->hasOne(Worker::class, 'user_id');
    }

    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class, 'user_id');
    }

    // Helper methods
    public function isFarmer(): bool
    {
        return $this->role === 'farmer';
    }

    public function isWorker(): bool
    {
        return $this->role === 'worker';
    }

    public function isDriver(): bool
    {
        return $this->role === 'driver';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Disable remember_token behavior as the column doesn't exist
    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value): void
    {
        // no-op: we don't persist remember tokens
    }
}

/**
 * Farmer Model
 * Profile details untuk petani
 */
class Farmer extends Model
{
    protected $table = 'farmers';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'address',
        'additional_info',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'farmer_id');
    }

    public function farmLocations(): HasMany
    {
        return $this->hasMany(FarmLocation::class, 'farmer_id');
    }
}

/**
 * Worker Model
 * Profile details untuk pekerja
 */
class Worker extends Model
{
    protected $table = 'workers';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'skills',
        'hourly_rate',
        'national_id',
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
        'daily_rate',
        'address',
        'availability_schedule',
        'current_location_lat',
        'current_location_lng',
        'rating',
        'review_count',
        'total_jobs_completed',
    ];

    protected $casts = [
        'skills' => 'array',
        'availability_schedule' => 'array',
        'hourly_rate' => 'decimal:2',
        'daily_rate' => 'decimal:2',
        'current_location_lat' => 'decimal:8',
        'current_location_lng' => 'decimal:8',
        'rating' => 'decimal:2',
        'review_count' => 'integer',
        'total_jobs_completed' => 'integer',
    ];

    protected $attributes = [
        'rating' => 0,
        'review_count' => 0,
        'total_jobs_completed' => 0,
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function projectApplications(): HasMany
    {
        return $this->hasMany(ProjectApplication::class, 'worker_id');
    }

    public function projectAssignments(): HasMany
    {
        return $this->hasMany(ProjectAssignment::class, 'worker_id');
    }

    public function workerAvailability(): HasMany
    {
        return $this->hasMany(WorkerAvailability::class, 'worker_id');
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(Payout::class, 'payee_id')
            ->where('payee_type', 'worker');
    }
}

/**
 * Driver Model
 * Profile details untuk driver/ekspedisi
 */
class Driver extends Model
{
    protected $table = 'drivers';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'address',
        'pricing_scheme',
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
        'vehicle_types',
        'rating',
        'review_count',
        'total_deliveries',
        'current_lat',
        'current_lng',
    ];

    protected $casts = [
        'pricing_scheme' => 'array',
        'vehicle_types' => 'array',
        'rating' => 'decimal:2',
        'review_count' => 'integer',
        'total_deliveries' => 'integer',
        'current_lat' => 'decimal:8',
        'current_lng' => 'decimal:8',
    ];

    protected $attributes = [
        'rating' => 0,
        'review_count' => 0,
        'total_deliveries' => 0,
    ];

    protected $appends = ['distance'];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class, 'driver_id');
    }

    public function driverRoutes(): HasMany
    {
        return $this->hasMany(DriverRoute::class, 'driver_id');
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(Payout::class, 'payee_id')
            ->where('payee_type', 'driver');
    }

    // Accessor for distance (computed field)
    public function getDistanceAttribute()
    {
        return $this->attributes['distance'] ?? 0;
    }

    // Setter for distance
    public function setDistanceAttribute($value)
    {
        $this->attributes['distance'] = $value;
    }
}

/**
 * FarmLocation Model
 * Lokasi lahan pertanian individual
 */
class FarmLocation extends Model
{
    use HasUuids;

    protected $table = 'farm_locations';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    const UPDATED_AT = null;

    protected $fillable = [
        'farmer_id',
        'name',
        'latitude',
        'longitude',
        'area_size',
        'crop_type',
        'irrigation_type',
        'description',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'area_size' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    // Relationships
    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class, 'farmer_id');
    }
}

/**
 * WorkerAvailability Model
 * Jadwal ketersediaan pekerja
 */
class WorkerAvailability extends Model
{
    use HasUuids;

    protected $table = 'worker_availabilities';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    const UPDATED_AT = null;

    protected $fillable = [
        'worker_id',
        'available_date',
        'available_start_time',
        'available_end_time',
        'is_booked',
        'booking_type',
        'notes',
    ];

    protected $casts = [
        'available_date' => 'date',
        'available_start_time' => 'datetime',
        'available_end_time' => 'datetime',
        'is_booked' => 'boolean',
    ];

    protected $attributes = [
        'is_booked' => false,
    ];

    // Relationships
    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'worker_id');
    }

    // Scope untuk available slots
    public function scopeAvailable($query)
    {
        return $query->where('is_booked', false);
    }

    // Scope untuk tanggal tertentu
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('available_date', $date);
    }
}