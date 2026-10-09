<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use App\Enums\UserStatus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Registrar nuevo usuario
     */
    public function register(array $data): User
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'] ?? null,
            'phone_number' => $data['phone_number'] ?? null,
            'password' => Hash::make($data['password']),
            'status' => 'active',
            'aml_status' => 'clear',
            'two_factor_enabled' => false,
        ]);

        // Asignar rol de customer por defecto
        $customerRole = Role::where('name', 'customer')->firstOrFail();
        $user->roles()->attach($customerRole->id);

        // Crear perfil KYC básico
        $user->kycProfile()->create([
            'level' => 1,
            'status' => 'pending',
            'phone_verified' => false,
            'email_verified' => false,
        ]);

        event(new Registered($user));

        return $user;
    }

    /**
     * Login de usuario
     */
    public function login(string $identifier, string $password): ?User
    {
        // Buscar por email o teléfono
        $user = User::where('email', $identifier)
            ->orWhere('phone_number', $identifier)
            ->first();

        if (!$user || !Hash::check($password, $user->password)) {
            // Registrar intento fallido
            $this->recordFailedLoginAttempt($identifier);
            throw ValidationException::withMessages([
                'credentials' => 'Invalid credentials.',
            ]);
        }

        // Validar que la cuenta esté activa
        if ($user->status !== 'active') {
            throw ValidationException::withMessages([
                'account' => 'Account is not active.',
            ]);
        }

        // Validar que no esté bloqueada por AML
        if ($user->aml_status === 'blocked') {
            throw ValidationException::withMessages([
                'account' => 'Account is blocked.',
            ]);
        }

        // Limpiar intentos fallidos
        $this->clearFailedLoginAttempts($identifier);

        // Registrar login
        $user->update(['last_login_at' => now()]);

        return $user;
    }

    /**
     * Generar token de autenticación
     */
    public function generateToken(User $user): string
    {
        return $user->createToken('fintech-token', ['*'])->plainTextToken;
    }

    /**
     * Validar 2FA
     */
    public function verify2FA(User $user, string $code): bool
    {
        if (!$user->two_factor_enabled) {
            return true;
        }

        // Aquí iría la lógica real de TOTP/SMS
        $cachedCode = Cache::get("2fa_code_{$user->id}");
        return $cachedCode === $code;
    }

    /**
     * Registrar intento fallido de login
     */
    private function recordFailedLoginAttempt(string $identifier): void
    {
        $key = "failed_login_{$identifier}";
        $attempts = Cache::get($key, 0);
        Cache::put($key, $attempts + 1, now()->addMinutes(15));
    }

    /**
     * Limpiar intentos fallidos
     */
    private function clearFailedLoginAttempts(string $identifier): void
    {
        $key = "failed_login_{$identifier}";
        Cache::forget($key);
    }

    /**
     * Validar límite de intentos fallidos
     */
    public function isLockedOut(string $identifier): bool
    {
        $key = "failed_login_{$identifier}";
        return Cache::get($key, 0) >= 5;
    }

    /**
     * Logout
     */
    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }
}
