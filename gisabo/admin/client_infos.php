<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8', 'root', '');

if (!isset($_SESSION['id_client'])) {
    header("Location: login_cl.php");
    exit();
}

$client = $bdd->prepare("SELECT * FROM clients WHERE id_client = ?");
$client->execute([$_SESSION['id_client']]);
$client_info = $client->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mes Informations - Gisabo CarWash</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 0 auto; padding: 20px; }
        .logout-btn { 
            background: #e74c3c; color: white; border: none; padding: 8px 15px; 
            cursor: pointer; text-decoration: none; border-radius: 4px; float: right;
        }
        .header-container { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .info-section { background: #f9f9f9; padding: 20px; border-radius: 5px; margin-bottom: 30px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .info-item { margin-bottom: 10px; }
        .info-label { font-weight: bold; color: #555; }
        .nav-button {
            padding: 10px 20px; background: #2ecc71; color: white; border: none; cursor: pointer;
            margin-right: 5px; text-decoration: none; display: inline-block; border-radius: 4px;
        }
        .nav-button:hover { background: #27ae60; }
    </style>
</head>
<body>
    <div class="header-container">
        <h2>Espace Client - Gisabo CarWash</h2>
        <a href="?logout" class="logout-btn">Déconnexion</a>
    </div>

    <div style="margin-bottom: 20px;">
        <a href="client_infos.php" class="nav-button">Mes Informations</a>
        <a href="client_reservations.php" class="nav-button">Mes Réservations</a>
        <a href="client_nouvelle.php" class="nav-button">Nouvelle Réservation</a>
    </div>

    <div class="info-section">
        <h3>Mes Informations Personnelles</h3>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Nom Complet:</span>
                <p><?= htmlspecialchars($client_info['Nom_complet']) ?></p>
            </div>
            <div class="info-item">
                <span class="info-label">Email:</span>
                <p><?= htmlspecialchars($client_info['email']) ?></p>
            </div>
            <div class="info-item">
                <span class="info-label">Téléphone:</span>
                <p><?= htmlspecialchars($client_info['téléphone'] ?? 'Non renseigné') ?></p>
            </div>
            <div class="info-item">
                <span class="info-label">Mot de passe:</span>
                <p>•••••••• (caché pour sécurité)</p>
            </div>
        </div>
    </div>
</body>
</html>