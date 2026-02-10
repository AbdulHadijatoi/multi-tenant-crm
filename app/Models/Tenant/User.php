<?php

namespace App\Models\Tenant;

use App\Models\TenantModel;
use App\Models\Tenant\Wallet;
use App\Models\Tenant\TradingAccount;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'tenant';

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

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
        return $this->hasOne(\App\Models\Tenant\Wallet::class);
    }

    public function tradingAccounts()
    {
        return $this->hasMany(\App\Models\Tenant\TradingAccount::class);
    }

    /**
     * Get the role that owns the user.
     */
    public function roleModel()
    {
        return $this->belongsTo(\Spatie\Permission\Models\Role::class, 'role_id');
    }
}
