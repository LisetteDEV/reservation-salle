<?php
session_start();
require '../config.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){
    header("Location: ../connexion.php");
    exit();
}

//  Statistiques pour les cards
$total_reservations = $pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn();
$en_attente         = $pdo->query("SELECT COUNT(*) FROM reservations WHERE statut = 'en attente'")->fetchColumn();
$validees           = $pdo->query("SELECT COUNT(*) FROM reservations WHERE statut = 'validée'")->fetchColumn();
$refusees           = $pdo->query("SELECT COUNT(*) FROM reservations WHERE statut = 'refusée'")->fetchColumn();
$total_creneaux     = $pdo->query("SELECT COUNT(*) FROM creneaux")->fetchColumn();
$total_users        = $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role = 'user'")->fetchColumn();

//  Données pour Chart 1 : Réservations par statut (Doughnut)
$stats_statut = [$en_attente, $validees, $refusees];

//  Données pour Chart 2 : Réservations par activité (Bar)
$activites = $pdo->query("
    SELECT c.type_activite, COUNT(r.id) as total
    FROM reservations r
    JOIN creneaux c ON r.creneau_id = c.id
    GROUP BY c.type_activite
")->fetchAll();

$labels_activites = json_encode(array_column($activites, 'type_activite'));
$data_activites   = json_encode(array_column($activites, 'total'));

//  Données pour Chart 3 : Réservations par mois (Line)
$par_mois = $pdo->query("
    SELECT DATE_FORMAT(c.date, '%M %Y') as mois, COUNT(r.id) as total
    FROM reservations r
    JOIN creneaux c ON r.creneau_id = c.id
    GROUP BY DATE_FORMAT(c.date, '%Y-%m')
    ORDER BY MIN(c.date) ASC
    LIMIT 6
")->fetchAll();

$labels_mois = json_encode(array_column($par_mois, 'mois'));
$data_mois   = json_encode(array_column($par_mois, 'total'));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SallePro - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Styles.css">
    <style>
        .stat-card {
            border-radius: 12px;
            border: none;
            color: #fff;
            padding: 1.2rem;
        }
        .stat-card.purple { background: linear-gradient(135deg, #7400b8, #9b59b6); }
        .stat-card.green  { background: linear-gradient(135deg, #007200, #27ae60); }
        .stat-card.orange { background: linear-gradient(135deg, #f0a500, #e67e22); }
        .stat-card.red    { background: linear-gradient(135deg, #c0392b, #e74c3c); }
        .stat-card.blue   { background: linear-gradient(135deg, #2980b9, #3498db); }
        .stat-card.dark   { background: linear-gradient(135deg, #2c3e50, #34495e); }
        .stat-number { font-size: 2rem; font-weight: 800; }
        .stat-label  { font-size: 0.85rem; opacity: 0.9; }
        .chart-card  { border-radius: 12px; border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.07); }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>

<!-- ══ SIDEBAR ══ -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <span>SallePro</span>
        <small>Administration</small>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section-label">Principal</div>
        <a href="home.php" class="sidebar-link active">
            <div class="icon"></div>Tableau de bord
        </a>
        <a href="creneaux.php" class="sidebar-link">
            <div class="icon"></div>Créneaux
        </a>
        <a href="reservations.php" class="sidebar-link">
            <div class="icon"></div>Réservations
        </a>
        <div class="nav-section-label">Compte</div>
        <a href="../deconnexion.php" class="sidebar-link">
            <div class="icon"></div>Déconnexion
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-avatar">AD</div>
            <div class="sidebar-user-info">
                <strong>Administrateur</strong>
                <span><?= htmlspecialchars($_SESSION['user']['email']) ?></span>
            </div>
        </div>
    </div>
</aside>

<!-- ══ TOPBAR ══ -->
<header class="topbar">
    <div class="topbar-left">
        <button class="btn-toggle" onclick="toggleSidebar()"></button>
        <div>
            <div class="page-title">Tableau de bord</div>
            
        </div>
    </div>
    <div class="topbar-right">
        <div class="topbar-profile">
            <a class="btn btn-outline-light btn-sm" href="../index.php">
                        <span>SallePro</span>

          </a>
        </div>
    </div>
</header>

<!-- ══ MAIN CONTENT ══ -->
<main class="main-content">

    <div class="page-header">
        <p>Voici un aperçu de l'activité de la plateforme.</p>
    </div>

    <!-- ── Cards statistiques ── -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card stat-card purple">
                <div class="stat-number"><?= $total_reservations ?></div>
                <div class="stat-label">Total réservations</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card stat-card orange">
                <div class="stat-number"><?= $en_attente ?></div>
                <div class="stat-label">En attente</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card stat-card green">
                <div class="stat-number"><?= $validees ?></div>
                <div class="stat-label">Validées</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card stat-card red">
                <div class="stat-number"><?= $refusees ?></div>
                <div class="stat-label">Refusées</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card stat-card blue">
                <div class="stat-number"><?= $total_creneaux ?></div>
                <div class="stat-label">Créneaux</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card stat-card dark">
                <div class="stat-number"><?= $total_users ?></div>
                <div class="stat-label">Utilisateurs</div>
            </div>
        </div>
    </div>

    <!-- ── Graphiques ── -->
    <div class="row g-4">

        <!-- Doughnut : statuts -->
        <div class="col-md-4">
            <div class="card chart-card p-4">
                <h6 class="fw-bold mb-3">Réservations par statut</h6>
                <canvas id="chartStatut"></canvas>
            </div>
        </div>

        <!-- Bar : par activité -->
        <div class="col-md-8">
            <div class="card chart-card p-4">
                <h6 class="fw-bold mb-3">Réservations par activité</h6>
                <canvas id="chartActivite"></canvas>
            </div>
        </div>

        <!-- Line : par mois -->
        <div class="col-md-12">
            <div class="card chart-card p-4">
                <h6 class="fw-bold mb-3">Évolution des réservations par mois</h6>
                <canvas id="chartMois"></canvas>
            </div>
        </div>

    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Toggle sidebar
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('overlay').classList.toggle('open');
    }

    // ── Chart 1 : Doughnut statuts
    new Chart(document.getElementById('chartStatut'), {
        type: 'doughnut',
        data: {
            labels: ['En attente', 'Validées', 'Refusées'],
            datasets: [{
                data: <?= json_encode($stats_statut) ?>,
                backgroundColor: ['#f0a500', '#007200', '#c0392b'],
                borderWidth: 2
            }]
        },
        options: {
            plugins: { legend: { position: 'bottom' } },
            cutout: '65%'
        }
    });

    // ── Chart 2 : Bar activités
    new Chart(document.getElementById('chartActivite'), {
        type: 'bar',
        data: {
            labels: <?= $labels_activites ?>,
            datasets: [{
                label: 'Réservations',
                data: <?= $data_activites ?>,
                backgroundColor: '#7400b8',
                borderRadius: 6
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // ── Chart 3 : Line par mois
    new Chart(document.getElementById('chartMois'), {
        type: 'line',
        data: {
            labels: <?= $labels_mois ?>,
            datasets: [{
                label: 'Réservations',
                data: <?= $data_mois ?>,
                borderColor: '#7400b8',
                backgroundColor: 'rgba(116,0,184,0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#7400b8'
            }]
        },
        options: {
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // Sidebar active
    document.querySelectorAll('.sidebar-link').forEach(link => {
        link.addEventListener('click', function(e) {
            if (this.href === '#' || this.href.endsWith('#')) e.preventDefault();
            document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });
</script>
</body>
</html>