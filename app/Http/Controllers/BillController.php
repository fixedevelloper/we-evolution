<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\MeterReading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BillController extends Controller
{
    /**
     * Lister toutes les factures d’un utilisateur
     * GET /api/bills?user_id=xx
     */
    public function index(Request $request)
    {
        $userId = $request->query('user_id');

        $bills = Bill::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($bills, 200);
    }

    /**
     * Détails d’une facture
     * GET /api/bills/{id}
     */
    public function show($id)
    {
        $bill = Bill::with('meterReading')->findOrFail($id);

        return response()->json($bill, 200);
    }

    /**
     * Créer une facture depuis OCR ou info manuelle
     * POST /api/bills
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|string',
            'user_id' => 'required|integer',
            'account_number' => 'nullable|string',
            'meter_number' => 'nullable|string',
            'index_value' => 'nullable|integer',
            'raw_text' => 'nullable|string',
            'provider' => 'required|string',
        ]);

        // Si relevé OCR → créer meter_reading
        $meterReading = null;
        if (!empty($data['meter_number']) && !empty($data['index_value'])) {
            $meterReading = MeterReading::create([
                'user_id' => $data['user_id'],
                'type' => $data['type'],
                'meter_number' => $data['meter_number'],
                'current_index' => $data['index_value'],
                'raw_text' => $data['raw_text'] ?? null,
                'status' => MeterReading::STATUS_PENDING,
            ]);
        }

        $bill = Bill::create([
            'user_id' => $data['user_id'],
            'type' => $data['type'],
            'account_number' => $data['account_number'] ?? null,
            'meter_reading_id' => $meterReading->id ?? null,
            'previous_index' => $meterReading->previous_index ?? null,
            'current_index' => $meterReading->current_index ?? null,
            'consumption' => $meterReading ? $meterReading->consumption : null,
            'amount' => 0,
            'currency' => 'XAF',
            'description' => null,
            'status' => Bill::STATUS_PENDING,
            'operator' => $data['provider'],
        ]);

        return response()->json([
            'message' => 'Bill created successfully',
            'bill' => $bill
        ], 201);
    }

    /**
     * Valider la facture auprès de l’opérateur (ENEO, CAMWATER, Canal+)
     * POST /api/bills/{id}/validate
     */
    public function validateBill($id)
    {
        $bill = Bill::with('meterReading')->findOrFail($id);

        if (!in_array($bill->status, [Bill::STATUS_PENDING])) {
            return response()->json([
                'error' => 'Bill already validated or paid.'
            ], 400);
        }

        // Appel service opérateur (mock)
        $response = Http::post("https://operator-api.test/validate", [
            'type' => $bill->type,
            'meter_number' => $bill->meter_number,
            'account_number' => $bill->account_number,
            'index_value' => $bill->current_index,
            'provider' => $bill->operator
        ]);

        if (!$response->successful()) {
            return response()->json([
                'error' => 'Cannot validate bill at operator.'
            ], 400);
        }

        $amount = $response->json('amount');

        $bill->update([
            'amount' => $amount,
            'status' => Bill::STATUS_PENDING_PAYMENT
        ]);

        if ($bill->meterReading) {
            $bill->meterReading->update(['status' => MeterReading::STATUS_VALIDATED]);
        }

        return response()->json([
            'message' => 'Bill validated successfully',
            'bill' => $bill
        ], 200);
    }

    /**
     * Payer une facture
     * POST /api/bills/{id}/pay
     */
    public function pay($id, Request $request)
    {
        $bill = Bill::findOrFail($id);

        if ($bill->status !== Bill::STATUS_PENDING_PAYMENT) {
            return response()->json([
                'error' => 'Bill not ready for payment.'
            ], 400);
        }

        $method = $request->input('method', 'om'); // om, mtn, card

        // Appel service paiement opérateur (mock)
        $response = Http::post("https://operator-api.test/pay", [
            'bill_id' => $bill->id,
            'amount' => $bill->amount,
            'provider' => $bill->operator,
            'method' => $method
        ]);

        if (!$response->successful()) {
            return response()->json([
                'error' => 'Payment failed'
            ], 400);
        }

        $transactionReference = $response->json('transaction_id');

        $bill->update([
            'status' => Bill::STATUS_PAID,
            'payment_reference' => $transactionReference,
            'paid_at' => now()
        ]);

        return response()->json([
            'message' => 'Bill paid successfully',
            'bill' => $bill
        ], 200);
    }
}
