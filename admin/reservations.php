<?php
session_start();
require '../config.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){
    header("Location: ../connexion.php");
    exit();
}

// VALIDER UNE RÉSERVATION
if(isset($_GET['valider'])){
    $id = intval($_GET['valider']);

    $info = $pdo->prepare("
        SELECT u.nom, u.email, c.date, c.heure_debut, c.heure_fin, c.type_activite
        FROM reservations r
        JOIN utilisateurs u ON r.utilisateur_id = u.id
        JOIN creneaux c ON r.creneau_id = c.id
        WHERE r.id = ?
    ");
    $info->execute([$id]);
    $res = $info->fetch();

    $pdo->prepare("UPDATE reservations SET statut = 'validée' WHERE id = ?")->execute([$id]);

    // Simulation email
    $sujet = "Votre réservation est confirmée - SallePro";
    $corps = "
        Bonjour {$res['nom']},\n
        Votre réservation a été VALIDÉE !\n
        Date     : {$res['date']}\n
        Horaire  : {$res['heure_debut']} → {$res['heure_fin']}\n
        Activité : {$res['type_activite']}\n\n
        Merci et à bientôt sur SallePro !
    ";
    $headers = "From: noreply@sallepro.fr";
    @mail($res['email'], $sujet, $corps, $headers);

    header("Location: reservations.php");
    exit();
}

// REFUSER UNE RÉSERVATION
if(isset($_GET['refuser'])){
    $id = intval($_GET['refuser']);

    $info = $pdo->prepare("
        SELECT u.nom, u.email, c.date, c.heure_debut, c.type_activite
        FROM reservations r
        JOIN utilisateurs u ON r.utilisateur_id = u.id
        JOIN creneaux c ON r.creneau_id = c.id
        WHERE r.id = ?
    ");
    $info->execute([$id]);
    $res = $info->fetch();

    $pdo->prepare("UPDATE reservations SET statut = 'refusée' WHERE id = ?")->execute([$id]);

    // Simulation email
    $sujet = "Votre réservation a été refusée - SallePro";
    $corps = "
        Bonjour {$res['nom']},\n
        Nous sommes désolés, votre réservation a été REFUSÉE.\n
        Date     : {$res['date']}\n
        Début    : {$res['heure_debut']}\n
        Activité : {$res['type_activite']}\n\n
        Vous pouvez réserver un autre créneau sur SallePro.
    ";
    $headers = "From: noreply@sallepro.fr";
    @mail($res['email'], $sujet, $corps, $headers);

    header("Location: reservations.php");
    exit();
}

// RÉCUPÉRER TOUTES LES RÉSERVATIONS
$filtre = $_GET['filtre'] ?? 'tous';

$sql = "
    SELECT r.id, r.statut,
           u.nom, u.email,
           c.date, c.heure_debut, c.heure_fin, c.type_activite
    FROM reservations r
    JOIN utilisateurs u ON r.utilisateur_id = u.id
    JOIN creneaux c ON r.creneau_id = c.id
";

if($filtre !== 'tous'){
    $sql .= " WHERE r.statut = " . $pdo->quote($filtre);
}
$sql .= " ORDER BY c.date DESC";

$reservations = $pdo->query($sql)->fetchAll();

$total    = $pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn();
$attente  = $pdo->query("SELECT COUNT(*) FROM reservations WHERE statut = 'en attente'")->fetchColumn();
$validees = $pdo->query("SELECT COUNT(*) FROM reservations WHERE statut = 'validée'")->fetchColumn();
$refusees = $pdo->query("SELECT COUNT(*) FROM reservations WHERE statut = 'refusée'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Réservations - Admin SallePro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Styles.css">
    <style>
        .badge-attente { background-color: #f0a500; color: #fff; }
        .badge-validee { background-color: #007200; color: #fff; }
        .badge-refusee { background-color: #c0392b; color: #fff; }
        .card-stat { border-left: 5px solid; border-radius: 8px; }
        .card-stat.attente { border-color: #f0a500; }
        .card-stat.validee { border-color: #007200; }
        .card-stat.refusee { border-color: #c0392b; }
        .card-stat.total   { border-color: #7400b8; }
        .card-header-custom { background-color: #7400b8; color: #fff; }
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
        <a href="home.php" class="sidebar-link">
            <div class="icon"></div>Tableau de bord
        </a>
        <a href="creneaux.php" class="sidebar-link">
            <div class="icon"></div>Créneaux
        </a>
        <a href="reservations.php" class="sidebar-link active">
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
            <div class="page-title">Réservations</div>
            <div class="breadcrumb-mini">
                <span>SallePro</span> <span>›</span>
                <span style="color: var(--primary);">Réservations</span>
            </div>
        </div>
    </div>
    <div class="topbar-right">
        <div class="topbar-profile">
            <div class="topbar-avatar">AD</div>
            <a class="btn btn-outline-light btn-sm" href="../index.php">
                <span>SallePro</span>
            </a>
        </div>
    </div>
</header>

<!-- ══ MAIN CONTENT ══ -->
<main class="main-content">

    <div class="page-header mb-3">
        <p>Gérez et validez les réservations des utilisateurs.</p>
    </div>

    <!-- Cards statistiques -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card card-stat total p-3 shadow-sm">
                <div class="text-muted small">Total</div>
                <div class="fw-bold fs-4" style="color:#7400b8"><?= $total ?></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-stat attente p-3 shadow-sm">
                <div class="text-muted small">En attente</div>
                <div class="fw-bold fs-4" style="color:#f0a500"><?= $attente ?></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-stat validee p-3 shadow-sm">
                <div class="text-muted small">Validées</div>
                <div class="fw-bold fs-4" style="color:#007200"><?= $validees ?></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-stat refusee p-3 shadow-sm">
                <div class="text-muted small">Refusées</div>
                <div class="fw-bold fs-4" style="color:#c0392b"><?= $refusees ?></div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="mb-3">
        <a href="?filtre=tous"       class="btn btn-sm <?= $filtre==='tous'       ? 'btn-dark'    : 'btn-outline-dark'    ?>">Toutes</a>
        <a href="?filtre=en attente" class="btn btn-sm <?= $filtre==='en attente' ? 'btn-warning' : 'btn-outline-warning' ?>">En attente</a>
        <a href="?filtre=validée"    class="btn btn-sm <?= $filtre==='validée'    ? 'btn-success' : 'btn-outline-success' ?>">Validées</a>
        <a href="?filtre=refusée"    class="btn btn-sm <?= $filtre==='refusée'    ? 'btn-danger'  : 'btn-outline-danger'  ?>">Refusées</a>
    </div>

    <!-- Tableau -->
    <div class="card shadow-sm">
        <div class="card-header card-header-custom">
            <strong>Liste des réservations</strong>
        </div>
        <div class="card-body p-0">
            <?php if(empty($reservations)): ?>
                <div class="text-center p-5 text-muted">Aucune réservation trouvée.</div>
            <?php else: ?>
            <table class="table table-bordered table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Client</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Activité</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($reservations as $r): ?>
                    <tr>
                        <td><?= $r['id'] ?></td>
                        <td><?= htmlspecialchars($r['nom']) ?></td>
                        <td><?= htmlspecialchars($r['email']) ?></td>
                        <td><?= htmlspecialchars($r['date']) ?></td>
                        <td><?= htmlspecialchars($r['heure_debut']) ?></td>
                        <td><?= htmlspecialchars($r['heure_fin']) ?></td>
                        <td><?= htmlspecialchars($r['type_activite']) ?></td>
                        <td>
                            <?php
                            $classe = match($r['statut']){
                                'en attente' => 'badge-attente',
                                'validée'    => 'badge-validee',
                                'refusée'    => 'badge-refusee',
                                default      => ''
                            };
                            ?>
                            <span class="badge <?= $classe ?>"><?= ucfirst($r['statut']) ?></span>
                        </td>
                        <td>
                            <?php if($r['statut'] === 'en attente'): ?>
                                <a href="?valider=<?= $r['id'] ?>"
                                   class="btn btn-success btn-sm"
                                   onclick="return confirm('Valider cette réservation ?')">
                                   Valider
                                </a>
                                <a href="?refuser=<?= $r['id'] ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Refuser cette réservation ?')">
                                   Refuser
                                </a>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('overlay').classList.toggle('open');
    }
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