<?php

namespace App\Models\Master;

use App\Models\MasterModel;
use App\Models\Master\Tenant;
use App\Models\Master\Subscription;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class License extends MasterModel
{
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
