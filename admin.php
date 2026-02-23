<?php
require 'connexion.php';

$nom = "Admin";
$email = "adminlisette@gmail.com";
$password = "admin@2026";
$role = "admin";

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)");
$stmt->execute([$nom, $email, $hash, $role]);

echo "Admin créé avec succès !";
?>