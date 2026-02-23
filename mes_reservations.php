<?php
session_start();
include 'navbar.php';
require 'config.php';

if(!isset($_SESSION['user'])){
    header("Location: connexion.php");
    exit();
}

// Annuler une réservation
if(isset($_GET['annuler'])){
    $id = intval($_GET['annuler']);
    $pdo->prepare("DELETE FROM reservations WHERE id = ? AND utilisateur_id = ? AND statut = 'en attente'")
        ->execute([$id, $_SESSION['user']['id']]);
    header("Location: mes_reservations.php?annule=1");
    exit();
}

$stmt = $pdo->prepare("
    SELECT r.id, r.statut, c.date, c.heure_debut, c.heure_fin, c.type_activite
    FROM reservations r
    JOIN creneaux c ON r.creneau_id = c.id
    WHERE r.utilisateur_id = ?
    ORDER BY c.date DESC
");
$stmt->execute([$_SESSION['user']['id']]);
$reservations = $stmt->fetchAll();

$total   = count($reservations);
$attente = count(array_filter($reservations, fn($r) => $r['statut'] === 'en attente'));
$val     = count(array_filter($reservations, fn($r) => $r['statut'] === 'validée'));
$ref     = count(array_filter($reservations, fn($r) => $r['statut'] === 'refusée'));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mes Réservations - SallePro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #f4f6f9;
        }
        main { flex: 1; }

        .page-hero {
            background: linear-gradient(135deg, #240046, #7400b8);
            color: #fff;
            padding: 3rem 0 2rem;
            margin-bottom: 2.5rem;
        }
        .page-hero h2 { font-weight: 800; font-size: 2rem; }

        .stats-bar {
            background: #fff;
            border-radius: 12px;
            padding: 1.2rem 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            margin-bottom: 2rem;
        }
        .stat-item { text-align: center; }
        .stat-item .number { font-size: 1.6rem; font-weight: 800; }
        .stat-item .label  { font-size: 0.75rem; color: #999; text-transform: uppercase; }

        .reservation-card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            transition: transform 0.2s;
            overflow: hidden;
        }
        .reservation-card:hover { transform: translateY(-3px); }

        .card-top-bar          { height: 5px; }
        .card-top-bar.attente  { background-color: #f0a500; }
        .card-top-bar.validee  { background-color: #007200; }
        .card-top-bar.refusee  { background-color: #c0392b; }

        .badge-statut {
            font-size: 0.8rem;
            padding: 0.4rem 0.9rem;
            border-radius: 50px;
            font-weight: 600;
        }
        .badge-statut.attente { background-color: #fff3cd; color: #856404; }
        .badge-statut.validee { background-color: #d1e7dd; color: #0a3622; }
        .badge-statut.refusee { background-color: #f8d7da; color: #842029; }

        .info-label { font-size: 0.72rem; text-transform: uppercase; color: #aaa; font-weight: 600; }
        .info-value { font-size: 0.95rem; font-weight: 700; color: #2c3e50; }

        .empty-state { text-align: center; padding: 5rem 1rem; }
        .empty-state .icon { font-size: 4rem; margin-bottom: 1rem; }
    </style>
</head>
<body>


<br> <br> <br>
<main>

    <!-- Hero -->
    <div class="page-hero">
        <div class="container">
            <h2> Mes Réservations</h2>
            <p class="mb-0 opacity-75">
                Bonjour <strong><?= htmlspecialchars($_SESSION['user']['nom']) ?></strong>, voici l'historique de vos réservations.
            </p>
        </div>
    </div>

    <div class="container">

        <!-- Alertes -->
        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-3">
                 Réservation effectuée avec succès ! En attente de validation.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['annule'])): ?>
            <div class="alert alert-warning alert-dismissible fade show rounded-3">
                 Réservation annulée avec succès.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Stats -->
        <?php if($total > 0): ?>
        <div class="stats-bar d-flex justify-content-around flex-wrap gap-3">
            <div class="stat-item">
                <div class="number" style="color:#7400b8"><?= $total ?></div>
                <div class="label">Total</div>
            </div>
            <div class="stat-item">
                <div class="number" style="color:#f0a500"><?= $attente ?></div>
                <div class="label">En attente</div>
            </div>
            <div class="stat-item">
                <div class="number" style="color:#007200"><?= $val ?></div>
                <div class="label">Validées</div>
            </div>
            <div class="stat-item">
                <div class="number" style="color:#c0392b"><?= $ref ?></div>
                <div class="label">Refusées</div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Cards réservations -->
        <?php if(empty($reservations)): ?>
            <div class="empty-state">
                <div class="icon"></div>
                <h4 class="fw-bold">Aucune réservation</h4>
                <p class="text-muted">Vous n'avez pas encore réservé de créneau.</p>
                <a href="index.php" class="btn btn-sm mt-2"
                   style="background-color:#7400b8; color:#fff; border-radius:50px; padding:0.6rem 2rem;">
                    Réserver maintenant
                </a>
            </div>
        <?php else: ?>

        <div class="row g-4">
            <?php foreach($reservations as $r):
                $sc = match($r['statut']){
                    'en attente' => 'attente',
                    'validée'    => 'validee',
                    'refusée'    => 'refusee',
                    default      => ''
                };
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card reservation-card h-100">
                    <div class="card-top-bar <?= $sc ?>"></div>
                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge-statut <?= $sc ?>"><?= ucfirst($r['statut']) ?></span>
                            <small class="text-muted">#<?= $r['id'] ?></small>
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <div class="info-label">Activité</div>
                                <div class="info-value"><?= htmlspecialchars($r['type_activite']) ?></div>
                            </div>
                            <div class="col-6">
                                <div class="info-label">Date</div>
                                <div class="info-value"><?= htmlspecialchars($r['date']) ?></div>
                            </div>
                            <div class="col-6">
                                <div class="info-label">Début</div>
                                <div class="info-value"><?= htmlspecialchars($r['heure_debut']) ?></div>
                            </div>
                            <div class="col-6">
                                <div class="info-label">Fin</div>
                                <div class="info-value"><?= htmlspecialchars($r['heure_fin']) ?></div>
                            </div>
                        </div>

                        <?php if($r['statut'] === 'en attente'): ?>
                        <div class="mt-3">
                            <a href="?annuler=<?= $r['id'] ?>"
                               class="btn btn-outline-danger btn-sm w-100"
                               onclick="return confirm('Annuler cette réservation ?')">
                                Annuler la réservation
                            </a>
                        </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php endif; ?>

    </div>
</main>

<!-- ══ FOOTER ══ -->
<footer style="background-color: #240046; margin-top: 3rem;">
    <div style="max-width:1200px; margin:0 auto; padding:4rem 1rem; text-align:center;">
        <h3 style="color:#ffffff; font-weight:700; letter-spacing:1px;">SallePro</h3>
        <br><br>
        <p style="color:#1c1c1c; background-color:#ffffff; font-size:0.9rem; font-weight:700; border:none; border-radius:50px; padding:0.8rem 1rem; display:inline-block; letter-spacing:0.5px; font-family:'Trebuchet MS',sans-serif;">
            Plateforme de gestion des réservations de salle
        </p>
        <br><br>
        <p style="color:#f8f9fa; font-size:0.8rem; margin-bottom:0;">
            © <span id="year"></span> SallePro. Tous droits réservés.
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById("year").textContent = new Date().getFullYear();
</script>
</body>
</html>