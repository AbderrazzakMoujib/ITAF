<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Badge - {{ $visiteur->nom_complet }}</title>
    <style>
        @page {
            size: 7cm 4cm;
            margin: 0;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            width: 7cm;
            height: 4cm;
            overflow: hidden;
            background: white;
            font-family: Arial, Helvetica, sans-serif;
        }
        .badge {
            width: 7cm;
            height: 4cm;
            display: flex;
            flex-direction: row;
            background: white;
        }
        .badge-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 6px 4px 6px 6px;
        }
        .badge-nom {
            font-size: 13px;
            font-weight: bold;
            color: #0A1628;
            text-transform: uppercase;
            line-height: 1.2;
            margin-bottom: 5px;
        }
        .badge-entreprise {
            font-size: 8px;
            font-weight: bold;
            color: #0A1628;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .badge-fonction {
            font-size: 7px;
            color: #555555;
        }
        .badge-qr {
            width: 2.2cm;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4px;
        }
        .badge-qr img {
            width: 100%;
            height: auto;
            display: block;
        }
    </style>
</head>
<body>
<div class="badge">
    <div class="badge-info">
        <div class="badge-nom">{{ strtoupper($visiteur->prenom . ' ' . $visiteur->nom) }}</div>
        @if($visiteur->entreprise)
        <div class="badge-entreprise">{{ strtoupper($visiteur->entreprise) }}</div>
        @endif
        @if($visiteur->fonction)
        <div class="badge-fonction">{{ $visiteur->fonction }}</div>
        @endif
    </div>
    <div class="badge-qr">
        <img src="{{ route('visiteur.qr', $visiteur->token) }}" alt="QR">
    </div>
</div>
<script>
    window.onload = function() {
        window.print();
        setTimeout(function() { window.close(); }, 1000);
    };
</script>
</body>
</html>
