<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accréditation ITA 2026</title>
    @include('emails._styles')
</head>
<body>

<div class="wrapper">

    <div class="header">
        <div class="header-top">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td style="vertical-align:middle;padding-right:16px;width:56px;">
                        <img src="{{ url('/img/logo/ita_logo.png') }}"
                             alt="ITA" width="56" height="56"
                             style="display:block;border-radius:3px;">
                    </td>
                    <td style="vertical-align:middle;">
                        <span class="header-flag">Industrial <span>Transformation</span> Africa</span>
                        <div class="header-sub">
                            <span>29 sept – 1er oct 2026</span>
                            <span class="header-sep">|</span>
                            <span>OFEC – Casablanca, Maroc</span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="header-bar"></div>
    </div>

    <div class="body">

        <div class="salutation">Bonjour {{ $visiteur->prenom }} {{ $visiteur->nom }},</div>

        <div class="message">
            Votre accréditation à <strong>Industrial Transformation Africa 2026</strong> a bien été enregistrée.
            Votre badge d'accès personnel est prêt — présentez le QR code ci-dessous à l'entrée le jour de l'événement.
        </div>

        <div class="badge-recap">
            <div class="badge-name">{{ $visiteur->prenom }} {{ $visiteur->nom }}</div>
            @if($visiteur->entreprise)
                <div class="badge-company">{{ $visiteur->entreprise }}</div>
            @endif
            @if($visiteur->fonction)
                <div class="badge-detail">{{ $visiteur->fonction }}</div>
            @endif
            @if($visiteur->pays)
                <div class="badge-detail">{{ $visiteur->pays }}</div>
            @endif
        </div>

        <div class="qr-section">
            <div class="qr-title">🔲 Votre QR code d'accès personnel</div>
            @if(isset($qrCodeCid))
                <img src="{{ $qrCodeCid }}" alt="QR Code ITA 2026" width="200" height="200">
            @else
                <img src="{{ route('visiteur.qr', $visiteur->token) }}" alt="QR Code ITA 2026" width="200" height="200">
            @endif
            <div class="qr-instruction">
                Présentez ce QR code à l'entrée de l'OFEC pour imprimer votre badge.
            </div>
        </div>

        <div class="event-info">
            <table>
                <tr><td>Événement</td><td>Industrial Transformation Africa 2026</td></tr>
                <tr><td>Dates</td><td>29 septembre – 1er octobre 2026</td></tr>
                <tr><td>Lieu</td><td>OFEC – Casablanca, Maroc</td></tr>
            </table>
        </div>

        <div class="cta">
            <a href="{{ route('visiteur.confirmation', $visiteur->token) }}">
                Afficher mon badge en ligne →
            </a>
        </div>

        <p style="color:#888;font-size:12px;text-align:center;">
            Si vous n'avez pas effectué cette inscription, ignorez cet email.
        </p>

    </div>

    <div class="footer">
        <span class="footer-brand">ITA 2026</span> &nbsp;·&nbsp;
        Industrial Transformation Africa &nbsp;·&nbsp; OFEC – Casablanca, Maroc
    </div>

</div>

</body>
</html>
