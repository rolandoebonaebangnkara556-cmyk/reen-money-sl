<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'status' => $this->status,
            'aml_status' => $this->aml_status,
            'kyc_level' => optional($this->kycProfile)->level,
            'kyc_status' => optional($this->kycProfile)->status,
            'account' => new AccountResource($this->account),
            'roles' => $this->roles->pluck('name'),
            'last_login_at' => $this->last_login_at?->toIso8601String(),
        ];
    }
}
