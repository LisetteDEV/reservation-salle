<?php
session_start();
require 'config.php';

$message = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['mot_de_passe'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

   if($user && password_verify($password, $user['mot_de_passe'])){

    $_SESSION['user'] = $user;

    // Vérification du rôle
    if($user['role'] === 'admin'){
        header("Location: admin/home.php");
    } else {
        header("Location: index.php");
    }

    exit();

}else{
    $message = "Email ou mot de passe incorrect.";
}

}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Connexion - SallePro</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">

</head>

<body class="login-bg">

<div class="container-fluid vh-100">
    <div class="row h-100 justify-content-center align-items-center">

        <div class="col-11 col-sm-8 col-md-6 col-lg-4">

            <div class="card login-card shadow-lg border-0 p-4">

                <div class="text-center mb-4">
                    <h2 class="logo-text">SallePro</h2>
                    <p class="text-muted">Connectez-vous à votre compte</p>
                </div>

                <?php if($message): ?>
                    <div class="alert alert-danger text-center">
                        <?= $message ?>
                    </div>
                <?php endif; ?>

                <form method="POST">

                    <div class="mb-3">
                        <input type="email"
                               name="email"
                               class="form-control form-control-sm"
                               placeholder="Adresse email"
                               required>
                    </div>

                    <div class="mb-3">
                        <input type="password"
                               name="mot_de_passe"
                               class="form-control form-control-sm"
                               placeholder="Mot de passe"
                               required>
                    </div>

                    <button class="btn btn-primary w-100 btn-lg fw-bold">
                        Se connecter
                    </button>

                    <hr>

                    <div class="text-center">
                        <a href="inscription.php"
                           class="btn btn-success w-100 fw-bold">
                           Créer un nouveau compte
                        </a>
                    </div>

                </form>

            </div>

        </div>

    </div>
</div>

</body>
</html>
