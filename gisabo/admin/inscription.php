<?php
$bdd = new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8', 'root', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $bdd->prepare("INSERT INTO clients (Nom_complet, email, téléphone, mot_de_passe) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $email, $telephone, $password]);
        
        header("Location: login_cl.php?inscription=success");
        exit();
    } catch (PDOException $e) {
        $erreur = "Erreur d'inscription : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inscription - Gisabo CarWash</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 8px; box-sizing: border-box; }
        button { background: #007BFF; color: white; border: none; padding: 10px 15px; cursor: pointer; }
         .inscr-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 450px;
        }
        .h2{
            padding: 0 10px;
        }
    </style>
</head>
<body>

<div class="inscr-box">
    <h2>Inscription</h2>
      <?php if (isset($erreur)) echo "<p style='color:red'>$erreur</p>"; ?>

   

         <form method="POST">
        <div class="form-group">
            <label>Nom complet</label>
            <input type="text" name="nom" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Téléphone</label>
            <input type="tel" name="telephone" required>
        </div>
        <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">S'inscrire</button>
    </form>
    
    <p>Déjà inscrit ? <a href="login_cl.php">Connectez-vous</a></p>
</div> 
    
</body>
</html>