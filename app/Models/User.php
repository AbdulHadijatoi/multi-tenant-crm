<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'user_cred_ref',
        'role_id',
        'role', // trader, ib, admin
        'kyc_status'
    ];

    protected $hidden = [
        'password',
        'user_cred_ref',
        'remember_token',
    ];

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function tradingAccounts()
    {
        return $this->hasMany(TradingAccount::class);
    }

    /**
     * Get the role that owns the user.
     */
    public function roleModel()
    {
        return $this->belongsTo(\Spatie\Permission\Models\Role::class, 'role_id');
    }
}
