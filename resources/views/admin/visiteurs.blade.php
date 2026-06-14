@extends('layouts.admin')

@section('titre', 'Visiteurs inscrits')

@section('contenu')

<div class="card mb-3 filter-bar">
    <div class="card-body py-2 px-3">
        <form method="GET" action="{{ route('admin.visiteurs') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="background:var(--ita-red);border-color:var(--ita-red);color:white;">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="q" class="form-control"
                           placeholder="Nom, email, entreprise…"
                           value="{{ $recherche ?? '' }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="statut" class="form-select form-select-sm">
                    <option value="">Tous les statuts</option>
                    <option value="present" {{ request('statut') === 'present' ? 'selected' : '' }}>Présents</option>
                    <option value="absent"  {{ request('statut') === 'absent'  ? 'selected' : '' }}>Non scannés</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary w-100">Filtrer</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.visiteurs') }}" class="btn btn-sm btn-outline-secondary w-100">Réinit.</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header card-header-ita d-flex align-items-center justify-content-between">
        <span>
            <i class="bi bi-people me-2" style="color:var(--ita-red);"></i>
            {{ $visiteurs->total() }} visiteur(s)
        </span>
        <a href="{{ route('admin.visiteurs.export') }}"
           class="btn btn-sm"
           style="background:var(--ita-red);color:white;font-family:'Barlow Condensed',sans-serif;font-weight:700;letter-spacing:.05em;text-transform:uppercase;border:none;font-size:.75rem;">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i>Export CSV
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Nom complet</th>
                        <th>Entreprise</th>
                        <th>Fonction</th>
                        <th>Email</th>
                        <th>Inscription</th>
                        <th>Statut</th>
                        <th>1er scan</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($visiteurs as $v)
                        <tr>
                            <td class="ps-3 text-muted small">{{ $v->id }}</td>
                            <td>
                                <div class="nom-visiteur">{{ $v->prenom }} {{ $v->nom }}</div>
                                @if($v->pays)
                                    <small class="text-muted" style="font-size:.7rem;">{{ $v->pays }}</small>
                                @endif
                            </td>
                            <td>{{ $v->entreprise ?? '—' }}</td>
                            <td style="color:#666;">{{ $v->fonction ?? '—' }}</td>
                            <td>
                                <a href="mailto:{{ $v->email }}" class="text-decoration-none" style="font-size:.8rem;color:#444;">
                                    {{ $v->email }}
                                </a>
                            </td>
                            <td style="font-size:.8rem;color:#888;">{{ $v->registered_at->format('d/m H:i') }}</td>
                            <td>
                                @if($v->estPresent())
                                    <span class="badge-present">Présent</span>
                                @else
                                    <span class="badge-absent">Absent</span>
                                @endif
                            </td>
                            <td class="heure-scan" style="font-size:.85rem;">
                                {{ $v->first_scanned_at?->format('H:i:s') ?? '—' }}
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="{{ route('visiteur.qr', $v->token) }}" target="_blank"
                                       class="btn btn-sm"
                                       style="background:var(--ita-light);border:1px solid var(--ita-border);color:var(--ita-dark);"
                                       title="Voir QR">
                                        <i class="bi bi-qr-code"></i>
                                    </a>
                                    <form action="{{ route('admin.visiteur.email', $v) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm"
                                                style="background:var(--ita-light);border:1px solid var(--ita-border);color:var(--ita-blue);"
                                                title="Renvoyer email"
                                                onclick="return confirm('Renvoyer le QR code à {{ $v->email }} ?')">
                                            <i class="bi bi-envelope"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.visiteur.supprimer', $v) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm"
                                                style="background:var(--ita-light);border:1px solid var(--ita-border);color:var(--ita-red);"
                                                title="Supprimer"
                                                onclick="return confirm('Supprimer {{ $v->nom_complet }} ?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-2" style="color:var(--ita-red);opacity:.4;"></i>
                                Aucun visiteur trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($visiteurs->hasPages())
        <div class="card-footer bg-white">{{ $visiteurs->links() }}</div>
    @endif
</div>

@endsection
