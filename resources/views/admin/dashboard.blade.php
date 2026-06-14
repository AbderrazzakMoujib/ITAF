@extends('layouts.admin')

@section('titre', 'Tableau de bord')

@section('contenu')

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-inscrits">
            <div class="stat-icon"><i class="bi bi-people"></i></div>
            <div class="stat-valeur" id="stat-inscrits">{{ $totalInscrits }}</div>
            <div class="stat-label">Total inscrits</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-presents">
            <div class="stat-icon"><i class="bi bi-person-check"></i></div>
            <div class="stat-valeur" id="stat-presents">{{ $totalPresents }}</div>
            <div class="stat-label">Présents</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-absents">
            <div class="stat-icon"><i class="bi bi-person-x"></i></div>
            <div class="stat-valeur" id="stat-absents">{{ $totalAbsents }}</div>
            <div class="stat-label">Non scannés</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-taux">
            <div class="stat-icon"><i class="bi bi-percent"></i></div>
            <div class="stat-valeur" id="stat-taux">{{ $tauxPresence }}%</div>
            <div class="stat-label">Taux de présence</div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body py-2">
        <div class="d-flex justify-content-between mb-1">
            <span style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:.8rem;text-transform:uppercase;letter-spacing:.06em;color:#444;">
                Taux de présence global
            </span>
            <span style="font-family:'Barlow Condensed',sans-serif;font-weight:800;color:var(--ita-red);">
                {{ $tauxPresence }}%
            </span>
        </div>
        <div class="progress" style="height:10px;border-radius:3px;background:var(--ita-light);">
            <div class="progress-bar" role="progressbar" style="width:{{ $tauxPresence }}%"></div>
        </div>
        <div class="d-flex justify-content-between mt-1">
            <span class="small text-muted">{{ $totalPresents }} présents</span>
            <span class="small text-muted">{{ $totalInscrits }} inscrits</span>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header card-header-ita d-flex align-items-center justify-content-between">
                <span><i class="bi bi-bar-chart-fill me-2"></i>Scans par heure</span>
                <span class="text-muted">Rafraîchissement auto 30s</span>
            </div>
            <div class="card-body">
                <canvas id="graphique-scans" height="110"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header card-header-ita">
                <i class="bi bi-clock-history me-2"></i>Dernières inscriptions
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($derniersInscrits as $v)
                        <li class="list-group-item d-flex align-items-center gap-2 py-2 px-3">
                            <div class="avatar-circle">{{ strtoupper(substr($v->prenom,0,1)) }}</div>
                            <div class="overflow-hidden flex-grow-1">
                                <div class="nom-visiteur text-truncate" style="font-size:.85rem;">{{ $v->nom_complet }}</div>
                                <div class="text-muted" style="font-size:.7rem;">{{ $v->registered_at->diffForHumans() }}</div>
                            </div>
                            @if($v->estPresent())
                                <span class="badge-present ms-auto">Présent</span>
                            @endif
                        </li>
                    @empty
                        <li class="list-group-item text-muted small">Aucune inscription récente.</li>
                    @endforelse
                </ul>
            </div>
            <div class="card-footer bg-white text-center border-top">
                <a href="{{ route('admin.visiteurs') }}" class="link-ita">
                    Voir tous les visiteurs →
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    window.ITA_SCANS_PAR_HEURE = @json($scansParHeure);
    window.ITA_STATS_URL       = "{{ route('admin.stats') }}";
</script>
<script src="/js/admin-dashboard.js"></script>
@endpush
