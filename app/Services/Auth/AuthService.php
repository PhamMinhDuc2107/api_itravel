<?php

    namespace App\Services\Auth;

    use App\Models\Auth\Admin;
    use App\Models\Auth\LoginAttempt;
    use App\Repositories\Contracts\AdminRepositoryInterface;
    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;

    class AuthService
    {
        protected AdminRepositoryInterface $adminRepo;

        public function __construct(AdminRepositoryInterface $adminRepo)
        {
            $this->adminRepo = $adminRepo;
        }

        public function register(array $validated): \Illuminate\Http\JsonResponse
        {
            $user = $this->adminRepo->create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            return response()->created([
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
        }

        public function login(array $credentials, Request $request): \Illuminate\Http\JsonResponse
        {
            $email = $credentials['email'];
            $password = $credentials['password'];
            $remember = $credentials['remember_me'] ?? false;

            $recentFailedAttempts = LoginAttempt::where('email', $email)
                ->where('successful', false)
                ->where('attempted_at', '>=', Carbon::now()->subMinutes(15))
                ->count();

            if ($recentFailedAttempts >= 5) {
                LoginAttempt::logAttempt($email, $request, false, 'Too many attempts');
                return response()->error('Quá nhiều lần đăng nhập sai. Vui lòng thử lại sau 15 phút.', 429);
            }

            $user = $this->adminRepo->findByEmail($email);

            if (!$user || !Hash::check($password, $user->password)) {
                LoginAttempt::logAttempt($email, $request, false, 'Invalid credentials');
                return response()->unauthorized('Sai tài khoản hoặc mật khẩu');
            }

            if ($user->status !== Admin::ADMIN_STATUS_ACTIVE) {
                LoginAttempt::logAttempt($email, $request, false, 'Account not activated');
                return response()->unauthorized('Tài khoản chưa được kích hoạt');
            }

            LoginAttempt::logAttempt($email, $request, true);

            $expiresAt = $remember ? now()->addDays(7) : now()->addHours(2);
            $token = $user->createToken('admin-token', ['*'], $expiresAt)->plainTextToken;

            return response()->success([
                'user' => $user,
                'access_token' => $token,
            ], 'Đăng nhập thành công');
        }


        public function logout(Admin $user): \Illuminate\Http\JsonResponse
        {
            $user->currentAccessToken()?->delete();
            return response()->deleted('Đăng xuất thành công');
        }

        public function getLoginAttempts(Admin $user): \Illuminate\Http\JsonResponse
        {
            $attempts = LoginAttempt::where('email', $user->email)
                ->orderByDesc('attempted_at')
                ->limit(20)
                ->get();

            return response()->success($attempts, 'Lịch sử đăng nhập');
        }
    }
