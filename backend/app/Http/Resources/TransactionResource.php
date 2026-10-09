<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'amount' => (float) $this->amount,
            'fee' => (float) $this->fee,
            'net_amount' => (float) $this->net_amount,
            'status' => $this->status,
            'description' => $this->description,
            'reference_number' => $this->reference_number,
            'recipient' => $this->recipient ? [
                'id' => $this->recipient->id,
                'name' => $this->recipient->first_name . ' ' . $this->recipient->last_name,
                'account_number' => $this->recipient->account?->account_number,
            ] : null,
            'completed_at' => $this->completed_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
