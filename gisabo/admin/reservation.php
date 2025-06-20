<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8', 'root', '');

// Vérification connexion
if (!isset($_SESSION['id_client'])) {
    header("Location: login_cl.php");
    exit();
}

// Gestion de la déconnexion
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login_cl.php");
    exit();
}

// Récupérer les informations du client
$client = $bdd->prepare("SELECT * FROM clients WHERE id_client = ?");
$client->execute([$_SESSION['id_client']]);
$client_info = $client->fetch();

// Récupérer les services disponibles
$services = $bdd->query("SELECT * FROM services")->fetchAll();

// Récupérer les réservations du client
$reservations = $bdd->prepare("SELECT r.*, s.nom as service_nom, s.prix, s.durée 
                              FROM reservations r 
                              JOIN services s ON r.id_serv = s.id_serv 
                              WHERE r.id_client = ? 
                              ORDER BY r.date_reservation DESC");
$reservations->execute([$_SESSION['id_client']]);
$client_reservations = $reservations->fetchAll();

// Traitement du formulaire de réservation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserver'])) {
    // Vérification que tous les champs sont présents
    if (!isset($_POST['service']) || !isset($_POST['date']) || !isset($_POST['heure'])) {
        $erreur = "Veuillez remplir tous les champs du formulaire";
    } else {
        $id_service = $_POST['service']; 
        $date = $_POST['date'];
        $heure = $_POST['heure'];
        
        try {
            $stmt = $bdd->prepare("INSERT INTO reservations (id_client, id_serv, date_reservation, heure_reservation) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_SESSION['id_client'], $id_service, $date, $heure]);
            
            $success = "Réservation effectuée avec succès !";
            // Recharger les réservations après ajout
            $reservations->execute([$_SESSION['id_client']]);
            $client_reservations = $reservations->fetchAll();
        } catch (PDOException $e) {
            $erreur = "Erreur lors de la réservation : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Espace Client - Gisabo CarWash</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        select, input { width: 100%; padding: 10px; margin-bottom: 15px; }
        button { background: #2ecc71; color: white; border: none; padding: 12px 20px; cursor: pointer; }
        .service-card { border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 5px; }
        .logout-btn { 
            background: #e74c3c; 
            color: white; 
            border: none; 
            padding: 8px 15px; 
            cursor: pointer; 
            text-decoration: none;
            border-radius: 4px;
            float: right;
        }
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .info-section, .reservation-section {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #2ecc71;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .tab-container {
            margin-bottom: 20px;
        }
        .nav-button {
            padding: 10px 20px;
            background: #2ecc71;
            color: white;
            border: none;
            cursor: pointer;
            margin-right: 5px;
            text-decoration: none;
            display: inline-block;
            border-radius: 4px;
        }
        .nav-button:hover {
            background: #27ae60;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <h2>Espace Client - Gisabo CarWash</h2>
        <a href="index.php" class="logout-btn">Déconnexion</a>
    </div>

    <div class="tab-container">
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