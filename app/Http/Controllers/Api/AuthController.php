<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\PinLoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(private AuthService $authService)
    {
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->loginByEmail(
            $request->email,
            $request->password,
        );

        if (! $result) {
            return $this->error(trans('auth.login_failed'), 401);
        }

        return $this->success($result, trans('auth.login_success'));
    }

    public function loginByPin(PinLoginRequest $request)
    {
        $result = $this->authService->loginByPin($request->pin_code);

        if (! $result) {
            return $this->error(trans('auth.pin_failed'), 401);
        }

        return $this->success($result, trans('auth.login_success'));
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return $this->success(null, trans('auth.logout_success'));
    }

    public function me(Request $request)
    {
        $user = $request->user()->load('role.abilities');

        return $this->success(new UserResource($user));
    }
}
