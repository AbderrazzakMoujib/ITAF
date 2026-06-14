/* Admin Dashboard — Graphique scans + rafraîchissement stats */

document.addEventListener('DOMContentLoaded', function () {

    const ctx = document.getElementById('graphique-scans');
    if (!ctx) return;

    const donneesInitiales = window.ITA_SCANS_PAR_HEURE || {};

    const graphique = new Chart(ctx.getContext('2d'), {
        type: 'bar',
        data: {
            labels: Object.keys(donneesInitiales),
            datasets: [{
                label: 'Scans',
                data: Object.values(donneesInitiales),
                backgroundColor: 'rgba(232,0,45,0.75)',
                borderColor: '#E8002D',
                borderWidth: 1,
                borderRadius: 3,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#F5F5F5' } },
                x: { grid: { display: false }, ticks: { font: { family: 'Barlow Condensed', weight: '700' } } }
            }
        }
    });

    async function rafraichirStats() {
        try {
            const rep  = await fetch(window.ITA_STATS_URL, { headers: { Accept: 'application/json' } });
            const data = await rep.json();
            document.getElementById('stat-inscrits').textContent = data.total_inscrits;
            document.getElementById('stat-presents').textContent = data.total_presents;
            document.getElementById('stat-absents').textContent  = data.total_inscrits - data.total_presents;
            document.getElementById('stat-taux').textContent     = data.taux_presence + '%';
            graphique.data.labels            = data.scans_par_heure.map(s => s.heure + 'h');
            graphique.data.datasets[0].data  = data.scans_par_heure.map(s => s.nombre);
            graphique.update('none');
        } catch (_) {}
    }

    setInterval(rafraichirStats, 30000);
});
