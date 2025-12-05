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
    public $paymentMethod = ''; // nouveau champ select
    public $loading = false;
    public $paymentSuccess = false;
    public $paymentMessage = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'email' => 'required|email|max:255',
        'paymentMethod' => 'required|in:om,mtn,card',
    ];

    public function mount($link)
    {
        // Récupérer l'objet PayLink
        $this->link = PaymentLink::query()->findOrFail($link);

        $this->name = $this->link->name ?? '';
        $this->phone = $this->link->phone ?? '';
        $this->email = $this->link->email ?? '';
    }


    public function pay()
    {
        $this->validate();

        $this->loading = true;
        $this->paymentSuccess = false;
        $this->paymentMessage = '';

        // Ici tu peux appeler ton service interne pour lancer le paiement
        // Simulons un traitement
        sleep(1); // simulant un traitement réseau
        // Exemple de réponse simulée
        $success = true;

        if ($success) {
            $this->paymentSuccess = true;
            $this->paymentMessage = "Paiement via " . strtoupper($this->paymentMethod) . " initié avec succès pour {$this->link->reference}.";
            $this->link->status='paid';
            $this->link->save();
        } else {
            $this->paymentSuccess = false;
            $this->paymentMessage = "Erreur lors du paiement.";
            $this->link->status='canceled';
            $this->link->save();
        }

        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.pay-link-payment');
    }
}

