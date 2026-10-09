<?php

namespace App\Services;

use App\Models\User;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Agency;
use App\Enums\TransactionType;
use App\Enums\TransactionStatus;
use Illuminate\Support\Str;

class AccountService
{
    /**
     * Crear cuenta virtual para usuario
     */
    public function createAccount(User $user, ?Agency $agency = null): Account
    {
        // Generar número de cuenta único
        $accountNumber = $this->generateAccountNumber();

        $account = $user->account()->create([
            'account_number' => $accountNumber,
            'iban' => $this->generateIban($accountNumber),
            'balance' => 0,
            'available_balance' => 0,
            'blocked_balance' => 0,
            'currency' => config('fintech.currency'),
            'status' => 'active',
            'created_by_agency_id' => $agency?->id,
            'opened_at' => now(),
        ]);

        return $account;
    }

    /**
     * Depositar dinero inicial en la cuenta
     */
    public function depositInitialFunds(
        User $user,
        float $amount,
        string $description = 'Initial deposit',
        ?User $createdByUser = null,
        ?Agency $agency = null
    ): Transaction
    {
        // Validar que el usuario tenga cuenta
        $account = $user->account ?? $this->createAccount($user, $agency);

        // Calcular comisión (sin comisión para depósitos iniciales)
        $fee = 0;
        $netAmount = $amount - $fee;

        // Crear transacción
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'account_id' => $account->id,
            'type' => TransactionType::DEPOSIT->value,
            'amount' => $amount,
            'fee' => $fee,
            'net_amount' => $netAmount,
            'currency' => $account->currency,
            'status' => TransactionStatus::COMPLETED->value,
            'description' => $description,
            'reference_number' => $this->generateReferenceNumber(),
            'metadata' => [
                'created_by_user_id' => $createdByUser?->id,
                'created_by_agency_id' => $agency?->id,
                'deposit_type' => 'initial',
                'ip_address' => request()->ip(),
                'device' => request()->userAgent(),
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'fraud_score' => 0,
            'fraud_status' => 'clean',
            'completed_at' => now(),
        ]);

        // Acreditar en cuenta
        $account->credit($netAmount);

        // Registrar auditoría
        app(AuditService::class)->log(
            $createdByUser ?? $user,
            'transaction_created',
            'Transaction',
            $transaction->id,
            ['type' => 'deposit', 'amount' => $amount]
        );

        return $transaction;
    }

    /**
     * Obtener saldo disponible
     */
    public function getAvailableBalance(User $user): float
    {
        return $user->account?->available_balance ?? 0;
    }

    /**
     * Validar si cuenta puede transaccionar
     */
    public function canTransact(User $user): bool
    {
        if (!$user->account) {
            return false;
        }

        return $user->account->canTransact();
    }

    /**
     * Generar número de cuenta
     */
    private function generateAccountNumber(): string
    {
        do {
            // Formato: REEN + año + mes + random
            $number = 'REEN' . date('Ym') . str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (Account::where('account_number', $number)->exists());

        return $number;
    }

    /**
     * Generar IBAN
     */
    private function generateIban(string $accountNumber): string
    {
        // IBAN format: CM (Cameroon) + check digits + account
        // Simplified version
        return 'CM' . str_pad(random_int(0, 99), 2, '0', STR_PAD_LEFT) . $accountNumber;
    }

    /**
     * Generar número de referencia único
     */
    private function generateReferenceNumber(): string
    {
        return 'REF' . date('YmdHis') . Str::upper(Str::random(6));
    }
}
