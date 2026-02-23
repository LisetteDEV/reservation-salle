<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Créneaux disponibles</title>
<link rel="stylesheet" href="style.css">
</head>
<style>
  /* ══ NAVBAR ══ */
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

</style>
<body >

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>