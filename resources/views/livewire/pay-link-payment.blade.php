<div>
    <div class="card shadow-sm">
        <div class="card-header">
            <p><h4>Payer avec</h4> {{ $link->reference }}</p>
        </div>
        <div class="card-body">

            @if($loading)
                <div class="text-center mb-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            @endif

            <form wire:submit.prevent="pay('om')">

                <div class="mb-3">
                    <label for="name" class="form-label">Nom du client</label>
                    <input type="text" class="form-control" id="name" wire:model.defer="name">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Téléphone</label>
                    <input type="text" class="form-control" id="phone" wire:model.defer="phone">
                    @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" wire:model.defer="email">
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

                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-warning" wire:click="pay('om')" @disabled($loading)>
                        Payer avec Orange Money
                    </button>
                    <button type="button" class="btn btn-success" wire:click="pay('mtn')" @disabled($loading)>
                        Payer avec MTN Mobile Money
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="pay('card')" @disabled($loading)>
                        Payer par Carte
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
