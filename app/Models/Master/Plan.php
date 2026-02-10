<?php

namespace App\Models\Master;

use App\Models\MasterModel;
use App\Models\Master\Subscription;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends MasterModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'price',
        'billing_cycle',
        'max_users',
        'max_storage',
        'features',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'max_storage' => 'integer',
        'features' => 'array',
    ];

    /**
     * Get the subscriptions for this plan.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
