<?php

namespace App\Http\Resources\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    
    private array $types = ['C' => 'Crédito', 'D' => 'Débito', 'P' => 'Pix'];

    public function toArray(Request $request): array
    {
        $paid = $this->paid;
        return [
            'user' => [
                "firstName" => $this->user->firstName,
                "lastName" => $this->user->lastName,
                "fullName" => $this->user->firstName . ' ' . $this->user->lastName,
                "email" => $this->user->email
            ],
            'type' => $this->types[$this->type],
            'paid' => $paid ? 'Pago' : 'Não Pago',
            'value' =>'R$ ' . number_format($this->value, 2, ',','.'),
            'paymentDate' => $paid ? Carbon::parse($this->payment_date)->format('Y/m/d') : NULL,
            'paymentSince' => $paid ? Carbon::parse($this->payment_date)->diffForHumans() : NULL
        ];
    }
}
