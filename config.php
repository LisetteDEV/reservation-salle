<?php
$host = "localhost";
$dbname = "reservation_salle";
$username = "root";
$password = "";
//$host = "sql306.infinityfree.com";
//$dbname = "if0_41205385_sallepro";
//$username = "if0_41205385";
//$password = "17375624";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}



?>
