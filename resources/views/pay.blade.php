<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement sécurisé</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .pay-card {
            max-width: 450px;
            margin: 50px auto;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .brand-header {
            background: linear-gradient(45deg, #1e3fa8, #3f51b5);
            color: white;
            padding: 25px;
            text-align: center;
        }

        .btn-pay {
            height: 50px;
            font-size: 16px;
            font-weight: 600;
        }

        .expired-box {
            background: #ffe2e2;
            border-left: 4px solid #ff4c4c;
            padding: 12px 15px;
            border-radius: 6px;
        }

        .loader {
            display: none;
        }
    </style>
</head>

<body>
<div class="pay-card bg-white">

    <div class="brand-header">
        <h4 class="mb-0">Paiement sécurisé</h4>
    </div>
    <div class="p-4">

        <!-- Message d'expiration -->
        @if($link->isExpired())
            <div class="expired-box mb-4">
                <strong>Ce lien de paiement a expiré.</strong><br>
                Veuillez contacter le commerçant pour un nouveau lien.
            </div>
        @else
<livewire:pay-link-payment linkId="{{$link->id}}"/>
        @endif
    </div>
</div>
{{--<div class="pay-card bg-white">

    <div class="brand-header">
        <h4 class="mb-0">Paiement sécurisé</h4>
    </div>

    <div class="p-4">

        <!-- Message d'expiration -->
        @if($link->isExpired())
            <div class="expired-box mb-4">
                <strong>Ce lien de paiement a expiré.</strong><br>
                Veuillez contacter le commerçant pour un nouveau lien.
            </div>
        @else

            <div class="text-center mb-4">
                <h3 class="fw-bold">
                    {{ number_format($link->amount, 0, ',', ' ') }} {{ $link->currency }}
                </h3>
                <p class="text-muted">{{ $link->description }}</p>

                <p class="small text-muted">
                    Expire le : <strong>{{ $link->expires_at->format('d/m/Y H:i') }}</strong>
                </p>
            </div>

            <hr>

            <!-- Boutons de paiement -->
            <div class="mt-4">

                <button class="btn btn-warning w-100 btn-pay mb-3" onclick="payWithOM()">
                    Orange Money
                </button>

                <button class="btn btn-warning w-100 btn-pay mb-3" style="background:#ffc107; border:none;"
                        onclick="payWithMTN()">
                    MTN Mobile Money
                </button>

                <button class="btn btn-primary w-100 btn-pay" onclick="payWithCard()">
                    Carte bancaire
                </button>

            </div>

    @endif

    <!-- Loader -->
        <div class="text-center mt-4 loader">
            <div class="spinner-border text-primary"></div>
            <p class="mt-2">Traitement en cours...</p>
        </div>

    </div>
</div>--}}


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function showLoader() {
        document.querySelector('.loader').style.display = 'block';
    }

    function payWithOM() {
        showLoader();

        // Remplacer par ta route backend .
        window.location.href = "/pay/om/{{ $link->reference }}";
    }

    function payWithMTN() {
        showLoader();

        window.location.href = "/pay/mtn/{{ $link->reference }}";
    }

    function payWithCard() {
        showLoader();

        window.location.href = "/pay/card/{{ $link->reference }}";
    }
</script>

</body>
</html>
