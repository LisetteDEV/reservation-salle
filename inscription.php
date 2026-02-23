<?php

session_start();
require 'config.php'; // ← manquant !
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);

    $stmt=$pdo->prepare("INSERT INTO utilisateurs(nom,email,mot_de_passe) VALUES(?,?,?)");
    $stmt->execute([$nom,$email,$password]);

    //  Connexion automatique
    $_SESSION['user'] = [
        'id' => $pdo->lastInsertId(),
        'nom' => $nom,
        'email' => $email,
        'role' => 'user'
    ];

    header("Location: index.php");
    exit();
}
?>


<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>

<body class="bg-gradient">

<div class="container d-flex justify-content-center align-items-center vh-100">

<div class="card p-4 shadow-lg form-card">
<h4 class="text-center mb-4">Créer un compte</h4>

<form method="POST">
  <div class="mb-3">
    <label class="form-label">Nom complet</label>
    <input type="text" name="nom" class="form-control form-control-sm" required>
  </div>

  <div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" class="form-control form-control-sm" required>
  </div>

  <div class="mb-3">
    <label>Mot de passe</label>
    <input type="password" name="mot_de_passe" class="form-control form-control-sm " required>
  </div>

  <button class="btn btn-primary w-100 btn-lg">S'inscrire</button>

  <p class="text-center mt-3">
    Déjà inscrit ? <a href="connexion.php">Se connecter</a>
  </p>
</form>

</div>
</div>
</body>
</html>
