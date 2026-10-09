<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccountResource;
use App\Http\Resources\TransactionResource;
use App\Services\AccountService;
use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    public function __construct(
        private AccountService $accountService,
    ) {}

    /**
     * Obtener cuenta del usuario autenticado
     */
    public function show(): JsonResponse
    {
        $user = auth()->user();
        $account = $user->account;

        if (!$account) {
            return response()->json([
                'message' => 'Account not found',
            ], 404);
        }

        return response()->json([
            'account' => new AccountResource($account),
        ], 200);
    }

    /**
     * Obtener saldo disponible
     */
    public function getBalance(): JsonResponse
    {
        $user = auth()->user();
        $balance = $this->accountService->getAvailableBalance($user);

        return response()->json([
            'available_balance' => $balance,
            'total_balance' => $user->account?->balance ?? 0,
            'blocked_balance' => $user->account?->blocked_balance ?? 0,
        ], 200);
    }

    /**
     * Obtener historial de transacciones
     */
    public function getTransactions(): JsonResponse
    {
        $user = auth()->user();
        $transactions = $user->transactions()
            ->with(['recipient', 'account'])
            ->latest()
            ->paginate(20);

        return response()->json([
            'transactions' => TransactionResource::collection($transactions),
        ], 200);
    }
}
