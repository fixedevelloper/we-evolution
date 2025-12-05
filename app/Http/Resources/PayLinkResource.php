<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PayLinkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (string) $this->id,
            'reference' => $this->reference,
            'amount' => (double) $this->amount,
            'currency' => $this->currency,
            'description' => $this->description,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'status' => $this->status,
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'expires_at' => $this->expires_at ? $this->expires_at->toDateTimeString() : null,
            'pay_url' => $this->reference,
        ];
    }
}
