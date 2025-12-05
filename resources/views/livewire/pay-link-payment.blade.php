<div>
    <div class="card shadow-sm">
        <div class="card-header text-center">
            <h4>Payer avec <strong>{{ $link->reference }}</strong></h4>
        </div>
        <div class="card-body">

            {{-- Loader --}}
            @if($loading)
                <div class="text-center mb-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            @endif

            {{-- Message succès/erreur --}}
            @if($paymentMessage)
                <div class="alert {{ $paymentSuccess ? 'alert-success' : 'alert-danger' }}">
                    {{ $paymentMessage }}
                </div>
            @endif

            {{-- Formulaire --}}
            <form wire:submit.prevent="pay">
                <div class="mb-3">
                    <label class="form-label">Nom du client</label>
                    <input type="text" class="form-control" wire:model.defer="name" required>
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Téléphone</label>
                    <input type="text" class="form-control" wire:model.defer="phone" required>
                    @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" wire:model.defer="email" required>
                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Montant</label>
                    <input type="text" class="form-control" value="{{ $link->amount }} {{ $link->currency }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" rows="2" disabled>{{ $link->description }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mode de paiement</label>
                    <select class="form-select" wire:model.live="paymentMethod">
                        <option value="">-- Choisir --</option>
                        <option value="om">Orange Money</option>
                        <option value="mtn">MTN Mobile Money</option>
                        <option value="card">Carte Bancaire</option>
                    </select>
                    @error('paymentMethod') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary"
                            {{ empty($paymentMethod) ? 'disabled' : '' }}
                            wire:loading.attr="disabled">
                        Payer
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

