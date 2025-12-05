<?php


namespace App\Http\Controllers;

use App\Models\PaymentLink;
use Illuminate\Http\Request;

class PaymentWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $ref = $request->reference;
        $status = $request->status; // "success" | "failed"

        $link = PaymentLink::where('reference', $ref)->first();

        if (!$link) {
            return response()->json(["error" => "Reference not found"], 404);
        }

        if ($status === "success") {
            $link->status = "paid";
        } else {
            $link->status = "canceled";
        }

        $link->save();

        return response()->json(["message" => "ok"]);
    }
}

