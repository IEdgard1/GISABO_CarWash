<?php
session_start();
include('connexion_db.php'); // Fichier de connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Requête pour vérifier l'utilisateur
    $sql = "SELECT id_empl, nom_complet, mot_de_passe FROM employés WHERE nom_complet = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérification du mot de passe et du rôle
    if ($user && password_verify($password,$user['mot_de_passe'])) {
        if ($user['rôle'] === 'admin') {
            $_SESSION['user_id'] = $user['id_empl'];
            $_SESSION['username'] = $user['nom_complet'];
            $_SESSION['rôle'] = $user['rôle'];
            header('Location: dashboard.php'); // Redirection pour les admins
            exit();
        } else {
            header('Location: accueille.php'); // Redirection pour les non-admins
            exit();
        }
    } else {
        // Échec de l'authentification
        $_SESSION['error'] = "Nom d'utilisateur ou mot de passe incorrect.";
        header('Location: Login.php');
        exit();
    }
}
?>