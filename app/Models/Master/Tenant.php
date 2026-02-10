<?php

namespace App\Models\Master;

use App\Models\MasterModel;
use App\Models\Master\License;
use App\Models\Master\Subscription;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends MasterModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'domain',
        'db_name',
        'db_username',
        'db_password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'db_password',
    ];

    /**
     * Get all subscriptions for this tenant.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get all licenses for this tenant.
     */
    public function licenses(): HasMany
    {
        return $this->hasMany(License::class);
    }
}
