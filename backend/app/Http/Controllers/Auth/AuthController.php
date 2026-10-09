<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Services\AccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService,
        private AccountService $accountService,
    ) {}

    /**
     * Registrar nuevo usuario
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            // Crear usuario
            $user = $this->authService->register($request->validated());

            // Crear cuenta virtual automáticamente
            $this->accountService->createAccount($user);

            // Generar token
            $token = $this->authService->generateToken($user);

            return response()->json([
                'message' => 'User registered successfully',
                'user' => new UserResource($user),
                'token' => $token,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Login de usuario
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            // Validar que no esté bloqueado
            if ($this->authService->isLockedOut($request->identifier)) {
                return response()->json([
                    'message' => 'Too many login attempts. Please try again later.',
                ], 429);
            }

            // Realizar login
            $user = $this->authService->login(
                $request->identifier,
                $request->password
            );

            // Verificar 2FA si está habilitado
            if ($user->two_factor_enabled) {
                return response()->json([
                    'message' => '2FA verification required',
                    'user_id' => $user->id,
                    'requires_2fa' => true,
                ], 200);
            }

            // Generar token
            $token = $this->authService->generateToken($user);

            return response()->json([
                'message' => 'Login successful',
                'user' => new UserResource($user),
                'token' => $token,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Login failed',
                'errors' => $e->errors(),
            ], 401);
        }
    }

    /**
     * Verificar 2FA
     */
    public function verify2FA(Request $request): JsonResponse
    {
        $user = User::findOrFail($request->user_id);

        if ($this->authService->verify2FA($user, $request->code)) {
            $token = $this->authService->generateToken($user);

            return response()->json([
                'message' '2FA verified',
                'user' => new UserResource($user),
                'token' => $token,
            ], 200);
        }

        return response()->json([
            'message' => 'Invalid 2FA code',
        ], 401);
    }

    /**
     * Logout
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout(auth()->user());

        return response()->json([
            'message' => 'Logged out successfully',
        ], 200);
    }
}
