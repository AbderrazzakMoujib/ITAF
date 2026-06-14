<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Kiosque — Aucun événement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#0f172a; color:#f1f5f9; min-height:100vh; display:flex; align-items:center; justify-content:center; font-family:Inter,sans-serif; }
    </style>
</head>
<body>
<div class="text-center">
    <div style="font-size:4rem;">📭</div>
    <h2 class="mt-3 fw-bold">Aucun événement actif</h2>
    <p class="text-muted">Activez un événement depuis le tableau de bord administrateur.</p>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary mt-3">Aller au tableau de bord</a>
</div>
</body>
</html>
