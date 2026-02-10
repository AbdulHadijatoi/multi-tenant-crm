<?php

namespace App\Models\Tenant;

use App\Models\TenantModel;
use App\Models\Tenant\User;

class Transaction extends TenantModel
{
    protected $fillable = [
        'user_id', 'type', 'amount', 'currency', 'status', 'description', 'method', 'reference_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
