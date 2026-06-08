<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BilleteraResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'saldo_total' => $this->saldo_total,
            'saldo_pendiente' => $this->saldo_pendiente,
            'saldo_pagado' => $this->saldo_pagado,
            'transacciones' => $this->when(
                $this->relationLoaded('user') && isset($this->transacciones),
                fn () => $this->transacciones
            ),
        ];
    }
}
