<?php

namespace App\Models\Tenant;

use App\Models\TenantModel;
use App\Models\Tenant\User;

class PropChallenge extends TenantModel
{
    protected $fillable = [
        'user_id',
        'plan_size',
        'status',
        'phase',
        'account_login',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'phase' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
