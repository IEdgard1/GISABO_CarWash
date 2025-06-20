<?php
$host = 'localhost';
$dbname = 'gisabo_db';
$username = 'root';  // Remplacez par votre utilisateur MySQL
$password = '';      // Remplacez par votre mot de passe MySQL

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>