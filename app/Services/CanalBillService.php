<?php


namespace App\Services;


use App\Http\Helpers\Helpers;
use App\Models\Bill;
use Illuminate\Http\Request;

class CanalBillService
{

    public function createBill($request)
    {
        // Appel API Canal+ si tu veux vérifier le client
        // $details = CanalApi::checkSubscriber($request->customer_reference);

        $metadata = [
            'bouquet' => $request->bouquet,
            'period' => $request->period
        ];

        return Bill::create([
            'user_id' => $request->user_id,
            'type' => 'canal',
            'customer_reference' => $request->customer_reference,
            'metadata' => $metadata,
            'amount' => $request->amount, // ou API Canal+
            'status' => 'pending'
        ]);
    }

}
