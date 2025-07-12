<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Carbon;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    public const ADMIN_STATUS_INACTIVE = '1';
    public const ADMIN_STATUS_ACTIVE   = '2';
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function loginAttempts()
    {
        return $this->hasMany(LoginAttempt::class, 'email', 'email');
    }

    public function getRecentFailedAttempts($minutes = 15)
    {
        return LoginAttempt::where('email', $this->email)
            ->where('successful', false)
            ->where('attempted_at', '>=', Carbon::now()->subMinutes($minutes))
            ->count();
    }
}
