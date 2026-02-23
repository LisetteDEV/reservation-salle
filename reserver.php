<?php
session_start(); // ← manquant
require 'config.php';

// Remplace verifierConnexion() par ceci :
if(!isset($_SESSION['user'])){
    header("Location: connexion.php");
    exit();
}

if(isset($_GET['id'])){

    $creneau_id = intval($_GET['id']);
    $utilisateur_id = $_SESSION['user']['id'];

    // Vérifier si déjà réservé
    $check = $pdo->prepare("SELECT * FROM reservations WHERE utilisateur_id = ? AND creneau_id = ?");
    $check->execute([$utilisateur_id, $creneau_id]);

    if($check->rowCount() == 0){

        $stmt = $pdo->prepare("INSERT INTO reservations(utilisateur_id, creneau_id) VALUES(?, ?)");
        $stmt->execute([$utilisateur_id, $creneau_id]);

        header("Location: mes_reservations.php?success=1");
        exit();

    } else {
        echo "Vous avez déjà réservé ce créneau.";
    }
}
?>