<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'status',
        'login_attempts',
        'locked_until',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'locked_until' => 'datetime',
        'login_attempts' => 'integer',
    ];

    /**
     * Kiểm tra xem tài khoản có bị khóa không
     */
    public function isLocked()
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    /**
     * Tăng số lần đăng nhập thất bại
     */
    public function incrementLoginAttempts()
    {
        $this->increment('login_attempts');
        
        // Khóa tài khoản sau 5 lần thất bại trong 30 phút
        if ($this->login_attempts >= 5) {
            $this->update([
                'locked_until' => now()->addMinutes(30)
            ]);
        }
    }

    /**
     * Reset số lần đăng nhập thất bại
     */
    public function resetLoginAttempts()
    {
        $this->update([
            'login_attempts' => 0,
            'locked_until' => null
        ]);
    }

    /**
     * Accessor để trả về name từ fullname
     */
    public function getNameAttribute()
    {
        return $this->fullname;
    }
} 