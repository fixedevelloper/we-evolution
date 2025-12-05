<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PaymentLink;

class PayLinkPayment extends Component
{
    public $link;
    public $name;
    public $phone;
    public $email;
    public $loading = false;

    protected $rules = [
        'name' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
    ];

    public function mount($linkId)
    {
        $this->link = PaymentLink::findOrFail($linkId);

        $this->name = $this->link->name;
        $this->phone = $this->link->phone;
        $this->email = $this->link->email;
    }

    public function pay($method)
    {
        $this->validate();

        $this->loading = true;

        // Redirection vers ton endpoint paiement avec les infos du client
        $query = http_build_query([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email
        ]);

        if($method === 'om') {
            $url = route('pay.om', $this->link->reference) . '?' . $query;
        } elseif($method === 'mtn') {
            $url = route('pay.mtn', $this->link->reference) . '?' . $query;
        } elseif($method === 'card') {
            $url = route('pay.card', $this->link->reference) . '?' . $query;
        } else {
            $url = '#';
        }

        return redirect()->to($url);
    }

    public function render()
    {
        return view('livewire.pay-link-payment');
    }
}
