<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Auth\Admin;
use App\Models\Auth\LoginAttempt;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();

            $user = Admin::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            return response()->created([
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
        } catch (\Exception $e) {
            return response()->error('Lỗi khi đăng ký: ' . $e->getMessage(), 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $validated = $request->validated();
            $email = $validated['email'];
            $password = $validated['password'];
            $remember = $validated['remember_me'];
            $recentFailedAttempts = LoginAttempt::where('email', $email)
                ->where('successful', false)
                ->where('attempted_at', '>=', Carbon::now()->subMinutes(15))
                ->count();

            if ($recentFailedAttempts >= 5) {
                LoginAttempt::logAttempt($email, $request, false, 'Too many attempts');
                return response()->error('Quá nhiều lần đăng nhập sai. Vui lòng thử lại sau 15 phút.', 429);
            }

            $user = Admin::where('email', $email)->first();

            if (!$user || !Hash::check($password, $user->password)) {
                LoginAttempt::logAttempt($email, $request, false, 'Invalid credentials');
                return response()->unauthorized('Sai tài khoản hoặc mật khẩu');
            }

            if ($user->status !== "2") {
                LoginAttempt::logAttempt($email, $request, false, 'Account not activated');
                return response()->unauthorized('Tài khoản chưa được kích hoạt');
            }

            LoginAttempt::logAttempt($email, $request, true);

            $expiresAt = $remember ? now()->addDays(7) : now()->addHours(2);
            $cookieMinutes = $remember ? (60 * 24 * 7) : 120;

            $token = $user->createToken('admin-token', ['*'], $expiresAt)->plainTextToken;
            $user['access_token'] = $token;
            return response()->success([
                'user' => $user,
            ], 'Đăng nhập thành công');
        } catch (\Exception $e) {
            LoginAttempt::logAttempt($request->input('email'), $request, false, 'Server error');
            return response()->error('Lỗi server, vui lòng thử lại ', 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            $user->currentAccessToken()->delete();

            return response()->deleted('Đăng xuất thành công');
        } catch (\Exception $e) {
            return response()->error('Lỗi khi đăng xuất: ' . $e->getMessage(), 500);
        }
    }

    public function getLoginAttempts(Request $request)
    {
        try {
            $user = $request->user();

            $attempts = LoginAttempt::where('email', $user->email)
                ->orderBy('attempted_at', 'desc')
                ->limit(20)
                ->get();

            return response()->success($attempts, 'Lịch sử đăng nhập');
        } catch (\Exception $e) {
            return response()->error('Lỗi khi lấy lịch sử đăng nhập', 500);
        }
    }
}
