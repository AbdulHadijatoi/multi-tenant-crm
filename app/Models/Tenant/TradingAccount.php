<?php

namespace App\Models\Tenant;

use App\Models\TenantModel;
use App\Models\Tenant\User;

class TradingAccount extends TenantModel
{
    protected $fillable = [
        'user_id',
        'platform',
        'login_id',
        'server_name',
        'type',
        'leverage',
        'balance',
        'equity',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'equity' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
