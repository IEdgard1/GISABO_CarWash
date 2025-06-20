<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8', 'root', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $bdd->prepare("SELECT * FROM clients WHERE email = ?");
    $stmt->execute([$email]);
    $client = $stmt->fetch();
    
    if ($client && password_verify($password, $client['mot_de_passe'])) {
        $_SESSION['id_client'] = $client['id_client'];
        $_SESSION['nom_client'] = $client['Nom_complet'];
        header("Location: reservation.php");
        exit();
    } else {
        $erreur = "Email ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion - Gisabo CarWash</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 8px; box-sizing: border-box; }
        button { background: #007BFF; color: white; border: none; padding: 10px 15px; cursor: pointer; }
        .success { color: green; }

        
    </style>
</head>
<body>
    <h2>Connexion</h2>
    <?php 
    if (isset($_GET['inscription']) && $_GET['inscription'] === 'success') {
        echo '<p class="success">Inscription réussie ! Veuillez vous connecter.</p>';
    }
    if (isset($erreur)) echo "<p style='color:red'>$erreur</p>"; 
    ?>
    
    <form method="POST">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Se connecter</button>
    </form>
    
    <p>Pas encore inscrit ? <a href="inscription.php">Créez un compte</a></p>
</body>
</html>