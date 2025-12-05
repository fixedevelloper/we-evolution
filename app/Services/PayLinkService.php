<?php


namespace App\Services;

use App\Models\PaymentLink;
use Illuminate\Support\Str;

class PayLinkService
{
    public function create(array $data)
    {
        $reference = Str::uuid()->toString();

        return PaymentLink::create([
            'reference' => $reference,
            'user_id' => $data['user_id'] ?? null,
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'amount' => $data['amount'],
            'currency' => $data['currency'] ?? 'XAF',
            'description' => $data['description'] ?? null,
            'expires_at' => now()->addHours($data['expires_in'] ?? 24),
            'redirect_url_success' => $data['redirect_success'] ?? null,
            'redirect_url_failed' => $data['redirect_failed'] ?? null,
        ]);
    }

    public function getByReference(string $reference)
    {
        return PaymentLink::where('id', $reference)->firstOrFail();
    }

}
