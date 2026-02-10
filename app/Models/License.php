<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class License extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = null; // Use default connection (Master DB)

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'subscription_id',
        'license_key',
        'status',
        'issued_at',
        'expires_at',
        'grace_until',
        'revoked_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'issued_at' => 'datetime',
        'expires_at' => 'datetime',
        'grace_until' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    /**
     * Get the tenant that owns the license.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the subscription associated with the license.
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Check if license is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' 
            && (!$this->expires_at || $this->expires_at->isFuture())
            && !$this->revoked_at;
    }

    /**
     * Check if license is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if license is in grace period.
     */
    public function isInGracePeriod(): bool
    {
        return $this->grace_until && $this->grace_until->isFuture();
    }
}
