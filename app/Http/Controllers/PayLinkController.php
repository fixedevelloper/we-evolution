<?php


namespace App\Http\Controllers;

use App\Http\Helpers\Helpers;
use App\Http\Resources\PayLinkResource;
use App\Models\PaymentLink;
use App\Services\PayLinkService;
use Illuminate\Http\Request;

class PayLinkController extends Controller
{
    protected $service;

    public function __construct(PayLinkService $service)
    {
        $this->service=$service;
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'nullable|integer',
            'amount' => 'required|numeric|min:1',
            'currency' => 'nullable|string',
            'name' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|string',
            'description' => 'nullable|string',
            'expires_in' => 'nullable|integer',
            'redirect_success' => 'nullable|string',
            'redirect_failed' => 'nullable|string',
        ]);

        $link = $this->service->create($data);

        return Helpers::success([
            'reference' => $link->reference,
            'pay_url' => url('/pay/' . $link->reference),
            'status' => 'pending'
        ]);
    }

    public function show($reference)
    {
        $link = $this->service->getByReference($reference);

        return Helpers::success(new PayLinkResource($link));
    }
    public function list_links(Request $request, $userId)
    {
        // Optional filter : ?status=paid|pending|expired
        $status = $request->query('status');

        $query = PaymentLink::where('user_id', $userId);

        if ($status) {
            $query->where('status', $status);
        }

        $links = $query->orderBy('created_at', 'desc')->get();

        if ($links->isEmpty()) {
            return Helpers::success([], "Aucun lien de paiement trouvé.");
        }

        return Helpers::success($links);
    }


    public function viewPage($reference)
    {
        $link = PaymentLink::where('reference', $reference)->firstOrFail();

        return view('pay', compact('link'));
    }

}

