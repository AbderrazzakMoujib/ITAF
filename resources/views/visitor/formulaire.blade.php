@extends('layouts.app')

@section('titre', 'Inscription — Industrial Transformation Africa')

@section('contenu')

<div class="page-hero">
    <div class="container">
        <div class="d-flex flex-column align-items-center text-center gap-3">
            <img src="/img/logo/content-2025-12-23-10-27-07.png"
                 alt="Industrial Transformation Africa 2026"
                 style="height:130px;width:auto;border-radius:4px;">
            <div>
                <div class="event-title">Industrial <span>Transformation</span> Africa</div>
                <div class="event-meta">
                    <i class="bi bi-calendar3"></i>&nbsp; 29 septembre – 1er octobre 2026
                    <span class="sep">|</span>
                    <i class="bi bi-geo-alt"></i>&nbsp; OFEC – Casablanca, Maroc
                </div>
                <div class="event-meta" style="opacity:.6;margin-top:.2rem;font-size:.75rem;">
                    Automation &amp; Smart Manufacturing Exhibition
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="d-flex align-items-center gap-2 mb-3">
                <div style="width:4px;height:28px;background:var(--ita-red);border-radius:2px;"></div>
                <h4 class="mb-0" style="font-family:'Barlow Condensed',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--ita-dark);">
                    Formulaire d'accréditation
                </h4>
            </div>

            <div class="form-card shadow-sm">
                <div class="section-title">
                    <i class="bi bi-person me-2"></i>Informations personnelles
                </div>

                <form action="{{ route('visiteur.store', $evenement->slug) }}" method="POST" novalidate>
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="prenom" class="form-label">Prénom <span class="required-star">*</span></label>
                            <input type="text" class="form-control @error('prenom') is-invalid @enderror"
                                   id="prenom" name="prenom" value="{{ old('prenom') }}"
                                   placeholder="Mohammed" required autofocus>
                            @error('prenom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="nom" class="form-label">Nom <span class="required-star">*</span></label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror"
                                   id="nom" name="nom" value="{{ old('nom') }}"
                                   placeholder="El Idrissi" required>
                            @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email professionnel <span class="required-star">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}"
                               placeholder="votre@entreprise.ma" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text" style="color:#888;font-size:.8rem;">
                            <i class="bi bi-envelope me-1"></i>Votre QR code sera envoyé à cette adresse.
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="entreprise" class="form-label">Entreprise / Organisation</label>
                            <input type="text" class="form-control @error('entreprise') is-invalid @enderror"
                                   id="entreprise" name="entreprise" value="{{ old('entreprise') }}"
                                   placeholder="OCP Group">
                            @error('entreprise')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="fonction" class="form-label">Fonction / Poste</label>
                            <input type="text" class="form-control @error('fonction') is-invalid @enderror"
                                   id="fonction" name="fonction" value="{{ old('fonction') }}"
                                   placeholder="Directeur Général">
                            @error('fonction')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input type="text" inputmode="numeric" pattern="[0-9]*"
                                   class="form-control @error('telephone') is-invalid @enderror"
                                   id="telephone" name="telephone" value="{{ old('telephone') }}"
                                   placeholder="0612345678"
                                   oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                            @error('telephone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="pays" class="form-label">Pays</label>
                            <select class="form-select @error('pays') is-invalid @enderror" id="pays" name="pays">
                                <option value="">— Sélectionnez —</option>
                                @foreach(['Maroc','France','Belgique','Espagne','Sénégal',"Côte d'Ivoire",'Tunisie','Algérie','Autre'] as $p)
                                    <option value="{{ $p }}" {{ old('pays') == $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                            @error('pays')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-4" style="border:1px solid #DDDDDD;border-left:4px solid var(--ita-red);border-radius:4px;padding:16px 18px;background:#fafafa;">
                        <div class="form-check align-items-start d-flex gap-2">
                            <input class="form-check-input mt-1 flex-shrink-0 @error('cndp_consent') is-invalid @enderror"
                                   type="checkbox" name="cndp_consent" id="cndp_consent"
                                   value="1" {{ old('cndp_consent') ? 'checked' : '' }} required>
                            <label class="form-check-label small" for="cndp_consent" style="color:#444;line-height:1.5;">
                                J'accepte que mes données personnelles soient traitées conformément à la
                                <strong style="color:var(--ita-dark);">loi 09-08</strong> relative à la protection
                                des personnes physiques à l'égard du traitement des données à caractère personnel
                                (<strong style="color:var(--ita-dark);">CNDP – Maroc</strong>). Ces données sont
                                collectées uniquement pour la gestion de mon accréditation à l'événement ITA 2026
                                et ne seront pas communiquées à des tiers.
                                <span class="required-star">*</span>
                            </label>
                        </div>
                        @error('cndp_consent')
                            <div class="text-danger small mt-2" style="padding-left:1.5rem;">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="bi bi-qr-code"></i>Valider mon inscription
                    </button>
                </form>

                <div class="privacy-note">
                    <i class="bi bi-shield-check me-1"></i>
                    Vos données sont protégées conformément à la loi 09-08 (CNDP – Maroc) et utilisées uniquement pour la gestion de l'accès à l'événement ITA.
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
