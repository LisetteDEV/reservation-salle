<?php
session_start();
require 'config.php';

if(!isset($_SESSION['user'])){
    header("Location: connexion.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM creneaux ORDER BY date ASC");
$creneaux = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SallePro - Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

body {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    font-family: 'Segoe UI', sans-serif;
    background-color: #f8f9fa;
    padding-top: 72px; /* ← OBLIGATOIRE */
}

main { flex: 1; }/* ══ NAVBAR ══ */
.navbar-custom {
    background-color: #fff;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    padding: 1rem 0;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;        /* ← au lieu de width: 100% */
    z-index: 9999;
}

.navbar-brand-custom {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 800;
    font-size: 1.3rem;
    color: #240046;
    text-decoration: none;
    /* ← AUCUN position ici */
}

        .nav-link-custom {
            color: #555;
            font-weight: 500;
            text-decoration: none;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            transition: color 0.2s;
        }

        .nav-link-custom:hover { color: #7400b8; }

        .nav-link-custom.active {
            color: #7400b8;
            font-weight: 700;
        }

        .btn-connexion {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            color: #555;
            font-weight: 500;
            text-decoration: none;
            padding: 0.4rem 0.8rem;
        }



        /* ══ HERO ══ */
        .hero {
            position: relative;
            height: 480px;
            display: flex;
            background: url('salles.jpg') center/cover no-repeat;
            align-items: center;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, rgba(10, 0, 19, 0.85) 40%, rgba(109, 108, 109, 0.3));
        }

        .hero-content {
            position: relative;
            z-index: 1;
            color: #fff;
            max-width: 600px;
        }

        .hero-content h1 {
            font-size: 2.8rem;
            font-weight: 900;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        .hero-content p {
            font-size: 1.05rem;
            opacity: 0.85;
            margin-bottom: 2rem;
        }

        .btn-hero {
            background-color: #7400b8;
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 0.8rem 2rem;
            font-weight: 700;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            transition: background 0.2s, transform 0.2s;
        }

        .btn-hero:hover {
            background-color: #5c0091;
            color: #fff;
            transform: translateY(-2px);
        }

        /* ══ SECTION CRÉNEAUX ══ */
        .section-title {
            font-size: 1.6rem;
            font-weight: 800;
            color: #240046;
            margin-bottom: 1.5rem;
        }

        /* ══ CARDS ══ */
        .creneau-card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            transition: transform 0.2s, box-shadow 0.2s;
            overflow: hidden;
        }

        .creneau-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(116,0,184,0.15);
        }

        .creneau-card .card-top {
            height: 5px;
            background: linear-gradient(to right, #240046, #7400b8);
        }

        .creneau-card .activite {
            font-size: 1.1rem;
            font-weight: 800;
            color: #240046;
            margin-bottom: 0.8rem;
        }

        .creneau-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.4rem;
        }

        .creneau-info .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #7400b8;
            flex-shrink: 0;
        }

        .btn-reserver {
            background: linear-gradient(135deg, #240046, #7400b8);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 0.6rem 1rem;
            font-weight: 700;
            width: 100%;
            text-decoration: none;
            display: block;
            text-align: center;
            transition: opacity 0.2s, transform 0.2s;
            margin-top: 1rem;
        }

        .btn-reserver:hover {
            opacity: 0.9;
            color: #fff;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

<!-- ══ NAVBAR ══ -->
<nav class="navbar-custom">
    <div class="container d-flex justify-content-between align-items-center">

        <!-- Logo -->
        <a href="index.php" class="navbar-brand-custom">
            <div class="brand-icon"></div>
            SallePro
        </a>

        <!-- Menu desktop -->
        <div class="d-none d-md-flex align-items-center gap-3">
            <a href="index.php" class="nav-link-custom active">Accueil</a>
            <a href="mes_reservations.php" class="nav-link-custom">Mes réservations</a>
            
        </div>

        <!-- Menu mobile -->
        <button class="navbar-toggler d-md-none border-0" type="button"
                data-bs-toggle="collapse" data-bs-target="#mobileMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Bouton hamburger ← ICI dans la navbar -->
        <button class="d-md-none border-0 bg-transparent" type="button"
                data-bs-toggle="collapse" data-bs-target="#mobileMenu"
                style="cursor: pointer; padding: 0.3rem;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                <rect y="4" width="24" height="2.5" rx="2" fill="#240046"/>
                <rect y="11" width="24" height="2.5" rx="2" fill="#240046"/>
                <rect y="18" width="24" height="2.5" rx="2" fill="#240046"/>
            </svg>
        </button>

    </div>

    <!-- Mobile menu -->
    <div class="collapse container d-md-none mt-2" id="mobileMenu">
        
        <div class="d-flex flex-column gap-2 pb-3">
            <a href="index.php" class="nav-link-custom active">Accueil</a>
            <a href="mes_reservations.php" class="nav-link-custom">Mes réservations</a>
            <a href="deconnexion.php" class="nav-link-custom">Déconnexion</a>
        </div>
    </div>
    
</nav>

<main>

    <!-- ══ HERO ══ -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Réservez votre créneau en quelques clics</h1>
                <p>Trouvez et réservez facilement un créneau dans notre salle d'atelier. Conférences, ateliers, formations et bien plus.</p>
                <a href="#creneaux" class="btn-hero">
                     Réserver maintenant
                </a>
            </div>
        </div>
    </section>

    <!-- ══ CRÉNEAUX ══ -->
    <section class="container my-5" id="creneaux">

        <h2 class="section-title">Créneaux disponibles</h2>

        <div class="row g-4">

            <?php if(count($creneaux) > 0): ?>
                <?php foreach($creneaux as $c): ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card creneau-card h-100">
                        <div class="card-top"></div>
                        <div class="card-body p-4">
                            <div class="activite"><?= htmlspecialchars($c['type_activite']) ?></div>
                            <div class="creneau-info">
                                <span class="dot"></span>
                                <span> <?= htmlspecialchars($c['date']) ?></span>
                            </div>
                            <div class="creneau-info">
                                <span class="dot"></span>
                                <span> <?= htmlspecialchars($c['heure_debut']) ?> à <?= htmlspecialchars($c['heure_fin']) ?></span>
                            </div>
                            <a href="reserver.php?id=<?= $c['id'] ?>" class="btn-reserver">
                                Réserver ce créneau
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="alert alert-info">Aucun créneau disponible pour le moment.</div>
                </div>
            <?php endif; ?>

        </div>
    </section>

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

    // Smooth scroll vers les créneaux
    document.querySelector('.btn-hero').addEventListener('click', function(e){
        e.preventDefault();
        document.getElementById('creneaux').scrollIntoView({ behavior: 'smooth' });
    });
</script>
</body>
</html>