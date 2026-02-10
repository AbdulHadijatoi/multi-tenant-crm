<?php

namespace App\Models\Tenant;

use App\Models\TenantModel;
use App\Models\Tenant\User;

class Wallet extends TenantModel
{
    protected $fillable = ['user_id', 'balance', 'locked_balance', 'commission_balance', 'currency'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
