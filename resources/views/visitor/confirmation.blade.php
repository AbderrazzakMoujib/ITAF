@extends('layouts.app')

@section('titre', 'Accréditation confirmée — ITA 2026')

@section('contenu')

<div class="hero-confirmed">
    <div class="container">
        <div class="d-flex flex-column align-items-center text-center gap-3">
            <img src="/img/logo/content-2025-12-23-10-27-07.png"
                 alt="ITA 2026"
                 style="height:110px;width:auto;border-radius:4px;">
            <div>
                <div class="confirmed-label" style="justify-content:center;">
                    <i class="bi bi-check-circle-fill"></i>Accréditation confirmée
                </div>
                <div class="welcome-name">{{ $visiteur->prenom }} {{ $visiteur->nom }}</div>
                <div class="event-meta">
                    29 sept – 1er oct 2026
                    <span class="sep">|</span>
                    OFEC – Casablanca, Maroc
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="row g-4">

                <div class="col-md-4">
                    <div class="qr-card">
                        <div class="qr-label">
                            <i class="bi bi-qr-code me-1"></i>Badge d'accès
                        </div>
                        <img src="{{ route('visiteur.qr', $visiteur->token) }}"
                             alt="QR Code {{ $visiteur->nom_complet }}">
                        <a href="{{ route('visiteur.telecharger-qr', $visiteur->token) }}"
                           class="btn-download">
                            <i class="bi bi-download me-1"></i>Télécharger
                        </a>
                    </div>
                </div>

                <div class="col-md-8">

                    <div class="badge-recap">
                        <div class="badge-name">{{ $visiteur->prenom }} {{ $visiteur->nom }}</div>
                        @if($visiteur->entreprise)
                            <div class="badge-company">{{ $visiteur->entreprise }}</div>
                        @endif
                        @if($visiteur->fonction)
                            <div class="badge-detail"><i class="bi bi-briefcase me-1"></i>{{ $visiteur->fonction }}</div>
                        @endif
                        @if($visiteur->pays)
                            <div class="badge-detail mt-1">
                                <span style="background:var(--ita-light);border:1px solid var(--ita-border);border-radius:3px;padding:2px 8px;font-size:.8rem;">
                                    <i class="bi bi-globe me-1"></i>{{ $visiteur->pays }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="email-alert">
                        <i class="bi bi-envelope-check me-2" style="color:var(--ita-blue);"></i>
                        <strong>Email envoyé !</strong> Votre QR code a été transmis à
                        <strong>{{ $visiteur->email }}</strong>. Vérifiez vos spams si besoin.
                    </div>

                    <div class="section-title">Le jour de l'événement</div>

                    <div class="step-card">
                        <div class="step-num">1</div>
                        <div>
                            <div class="step-label"><i class="bi bi-qr-code-scan me-1"></i>Présentez votre QR code</div>
                            <div class="step-desc">À l'entrée du kiosque d'accueil — OFEC Casablanca.</div>
                        </div>
                    </div>
                    <div class="step-card">
                        <div class="step-num">2</div>
                        <div>
                            <div class="step-label"><i class="bi bi-printer me-1"></i>Badge imprimé automatiquement</div>
                            <div class="step-desc">Votre badge personnalisé est imprimé en quelques secondes.</div>
                        </div>
                    </div>
                    <div class="step-card">
                        <div class="step-num">3</div>
                        <div>
                            <div class="step-label"><i class="bi bi-door-open me-1"></i>Accédez à l'événement</div>
                            <div class="step-desc">Portez votre badge visible tout au long des 3 jours.</div>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ route('visiteur.formulaire', $visiteur->event->slug) }}"
                           style="color:var(--ita-blue);font-size:.875rem;">
                            <i class="bi bi-person-plus me-1"></i>Inscrire un collègue
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
