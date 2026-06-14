@extends('layouts.admin')

@section('titre', 'Journal des scans')

@section('contenu')

<div class="card">
    <div class="card-header card-header-ita d-flex align-items-center justify-content-between">
        <span>
            <i class="bi bi-clock-history me-2" style="color:var(--ita-red);"></i>
            {{ $scans->total() }} scan(s) enregistré(s)
        </span>
        @if($evenement)
            <span style="background:var(--ita-red);color:white;font-family:'Barlow Condensed',sans-serif;font-size:.75rem;font-weight:700;letter-spacing:.06em;padding:.2rem .75rem;border-radius:3px;text-transform:uppercase;">
                {{ $evenement->nom }}
            </span>
        @endif
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Visiteur</th>
                        <th>Entreprise</th>
                        <th>Heure du scan</th>
                        <th>Kiosque</th>
                        <th class="text-center">N° scan</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($scans as $scan)
                        <tr>
                            <td class="ps-3 text-muted small">{{ $scan->id }}</td>
                            <td>
                                <div class="nom-visiteur">{{ $scan->visiteur->nom_complet }}</div>
                                <small style="color:#888;font-size:.75rem;">{{ $scan->visiteur->email }}</small>
                            </td>
                            <td>{{ $scan->visiteur->entreprise ?? '—' }}</td>
                            <td>
                                <div class="heure-scan">{{ $scan->scanned_at->format('H:i:s') }}</div>
                                <small class="text-muted" style="font-size:.75rem;">{{ $scan->scanned_at->format('d/m/Y') }}</small>
                            </td>
                            <td style="font-size:.8rem;color:#888;font-family:'Barlow Condensed',sans-serif;">
                                {{ $scan->kiosque_id ?? '—' }}
                            </td>
                            <td class="text-center" style="font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:1.1rem;color:{{ $scan->visiteur->scan_count > 1 ? 'var(--ita-blue)' : 'var(--ita-red)' }};">
                                {{ $scan->visiteur->scan_count }}
                            </td>
                            <td>
                                @if($scan->visiteur->scan_count > 1)
                                    <span class="badge-double">
                                        <i class="bi bi-exclamation-triangle me-1"></i>Double scan
                                    </span>
                                @else
                                    <span class="badge-premier">
                                        <i class="bi bi-check2 me-1"></i>1er scan
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-2" style="color:var(--ita-red);opacity:.4;"></i>
                                Aucun scan enregistré.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($scans->hasPages())
        <div class="card-footer bg-white">{{ $scans->links() }}</div>
    @endif
</div>

@endsection
