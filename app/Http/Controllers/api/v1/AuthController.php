<?php

    namespace App\Http\Controllers\Api\V1;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Auth\LoginRequest;
    use App\Http\Requests\Auth\RegisterRequest;
    use Illuminate\Http\Request;
    use App\Services\Auth\AuthService;

    class AuthController extends Controller
    {
        protected AuthService $authService;

        public function __construct(AuthService $auth)
        {
            $this->authService = $auth;
        }

        public function login(LoginRequest $request)
        {
            return $this->authService->login($request->validated(), $request);
        }

        public function register(RegisterRequest $request)
        {
            return $this->authService->register($request->validated());
        }

        public function logout(Request $request)
        {
            return $this->authService->logout($request->user());
        }
    }
