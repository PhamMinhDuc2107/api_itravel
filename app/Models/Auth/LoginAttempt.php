<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
  use HasFactory;

  protected $fillable = [
    'email',
    'ip_address',
    'user_agent',
    'successful',
    'failure_reason',
    'attempted_at'
  ];

  protected $casts = [
    'successful' => 'boolean',
    'attempted_at' => 'datetime'
  ];

  // Scopes for easy querying
  public function scopeSuccessful($query)
  {
    return $query->where('successful', true);
  }

  public function scopeFailed($query)
  {
    return $query->where('successful', false);
  }

  public function scopeRecent($query, $minutes = 15)
  {
    return $query->where('attempted_at', '>=', now()->subMinutes($minutes));
  }

  public function scopeForEmail($query, $email)
  {
    return $query->where('email', $email);
  }

  public function scopeForIp($query, $ip)
  {
    return $query->where('ip_address', $ip);
  }

  // Static method để log attempt (được dùng trong AuthController)
  public static function logAttempt($email, $request, $successful = false, $reason = null)
  {
    return static::create([
      'email' => $email,
      'ip_address' => $request->ip(),
      'user_agent' => $request->userAgent(),
      'successful' => $successful,
      'failure_reason' => $reason,
      'attempted_at' => now()
    ]);
  }

  // Helper methods
  public static function getRecentFailedAttempts($email, $minutes = 15)
  {
    return static::forEmail($email)
      ->failed()
      ->recent($minutes)
      ->count();
  }

  public static function getRecentFailedAttemptsForIp($ip, $minutes = 15)
  {
    return static::forIp($ip)
      ->failed()
      ->recent($minutes)
      ->count();
  }
}