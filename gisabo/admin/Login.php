<?php
session_start();
$bdd=new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8','root','');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $username = $_POST['username']; // Nouveau champ pour le nom d'utilisateur

    // Requête pour vérifier l'employé
    $stmt = $bdd->prepare("SELECT * FROM employés WHERE email = ?");
    $stmt->execute([$email]);
    $employe = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérification triple : email, mot de passe ET nom d'utilisateur
    if ($employe && $mot_de_passe === $employe['mot_de_passe'] && strtolower($username) === strtolower($employe['nom_complet'])) {
        $_SESSION['id_empl'] = $employe['id_empl'];
        $_SESSION['role'] = $employe['rôle'];
        $_SESSION['nom_complet'] = $employe['nom_complet'];
        
        // Redirection en fonction du rôle
        if ($employe['rôle'] === 'admin') {
            header("Location: dashboard.php");
        } else {
            header("Location: index.php"); // Page pour les employés non-admin
        }
        exit();
    } else {
        $erreur = "Identifiants incorrects ou vous n'avez pas les droits nécessaires !";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - GISABO</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            background: #f4f4f9; 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #6e8efb, #a777e3);
        }
            
        .login-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 350px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        .input-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
            transition: all 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #a777e3;
            outline: none;
            box-shadow: 0 0 5px rgba(167, 119, 227, 0.5);
        }

        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            background: linear-gradient(to right, #6e8efb, #a777e3);
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn:hover {
            background: linear-gradient(to right, #5a7bf9, #9866d8);
            transform: translateY(-2px);
        }
        
        .error-message {
            color: #e74c3c;
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #fde8e8;
            border-radius: 5px;
            border: 1px solid #f5c6cb;
        }
        
        .info-message {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Connexion Admin</h2>
        
        <?php if (isset($erreur)): ?>
            <div class="error-message"><?= $erreur ?></div>
        <?php endif; ?>
        
        <form method="POST">
             <div class="input-group">
                <label for="username">Nom complet</label>
                <input type="text" id="username" name="username" required placeholder="">
            </div>

            <div class="input-group">
                <label for="email">Email professionnel</label>
                <input type="email" name="email" required placeholder="">
            </div>
            
            <div class="input-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="mot_de_passe" required placeholder="••••••••">
            </div>
            
            <button type="submit" class="btn">Se connecter</button>
            
            <div class="info-message">
                Seuls les employés enregistrés peuvent accéder à ce système
            </div>
        </form>
    </div>
</body>
</html>