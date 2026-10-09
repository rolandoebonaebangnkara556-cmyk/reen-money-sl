<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'account_number' => $this->account_number,
            'iban' => $this->iban,
            'balance' => (float) $this->balance,
            'available_balance' => (float) $this->available_balance,
            'blocked_balance' => (float) $this->blocked_balance,
            'currency' => $this->currency,
            'status' => $this->status,
            'opened_at' => $this->opened_at?->toIso8601String(),
        ];
    }
}
