<?php
$bdd = new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8','root','');
session_start();
include 'config.php';

if (!isset($_SESSION['id_empl'])) {
    header("Location: login.php");
    exit();
}

// Récupérer l'ID de la réservation à modifier
$reservation_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$reservation_id) {
    header("Location: dashboard.php");
    exit();
}

// Récupérer les données de la réservation avec jointure sur les tables clients et services
$stmt = $bdd->prepare("
    SELECT 
        r.*, 
        c.nom_complet AS client_nom, 
        c.email, 
        c.téléphone,
        s.nom AS service_nom
    FROM reservations r
    JOIN clients c ON r.id_client = c.id_client
    JOIN services s ON r.id_serv = s.id_serv
    WHERE r.id_reserv = ?
");
$stmt->execute([$reservation_id]);
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reservation) {
    header("Location: dashboard.php");
    exit();
}

// Récupérer la liste des services pour le select
$services = $bdd->query("SELECT * FROM services")->fetchAll();

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $service_id = $_POST['service'];
    $date = $_POST['date'];
    $heure = $_POST['heure'];
    $mode_paiement = $_POST['mode_paiement'];
    
    try {
        $update_stmt = $bdd->prepare("UPDATE reservations SET 
                                    id_serv = ?, 
                                    date_reservation = ?, 
                                    heure_reservation = ?, 
                                    mode_paiement = ? 
                                    WHERE id_reserv = ?");
        
        if ($update_stmt->execute([$service_id, $date, $heure, $mode_paiement, $reservation_id])) {
            // Mettre à jour aussi les infos client si nécessaire
            $update_client = $bdd->prepare("UPDATE clients SET 
                                          nom_complet = ?, 
                                          email = ?, 
                                          téléphone = ? 
                                          WHERE id_client = ?");
            $update_client->execute([$nom, $email, $telephone, $reservation['id_client']]);
            
            $_SESSION['success_message'] = "Réservation modifiée avec succès!";
            header("Location: dashboard.php");
            exit();
        }
    } catch (PDOException $e) {
        $error_message = "Erreur lors de la modification: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Réservation</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .card {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="date"],
        input[type="time"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn {
            display: inline-block;
            background: #2ecc71;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-danger {
            background: #e74c3c;
        }
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <?php include("main.php"); ?>
    
    <div class="container">
        <h1>Modifier la Réservation</h1>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        
        <div class="card">
            <form method="POST">
                <div class="form-group">
                    <label for="nom">Nom du client</label>
                    <input type="text" id="nom" name="nom" 
                           value="<?php echo htmlspecialchars($reservation['client_nom'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo htmlspecialchars($reservation['email'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" 
                           value="<?php echo htmlspecialchars($reservation['telephone'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="service">Service</label>
                    <select id="service" name="service" required>
                        <?php foreach ($services as $service): ?>
                        <option value="<?php echo htmlspecialchars($service['id_serv']); ?>"
                            <?php if ($service['id_serv'] == $reservation['id_serv']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($service['nom']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" id="date" name="date" 
                           value="<?php echo htmlspecialchars($reservation['date_reservation'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="heure">Heure</label>
                    <input type="time" id="heure" name="heure" 
                           value="<?php echo htmlspecialchars($reservation['heure_reservation'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="mode_paiement">Mode de paiement</label>
                    <select id="mode_paiement" name="mode_paiement" required>
                        <option value="Espèces" <?php if (($reservation['mode_paiement'] ?? '') == 'Espèces') echo 'selected'; ?>>Espèces</option>
                        <option value="Carte bancaire" <?php if (($reservation['mode_paiement'] ?? '') == 'Carte bancaire') echo 'selected'; ?>>Carte bancaire</option>
                        <option value="Mobile Money" <?php if (($reservation['mode_paiement'] ?? '') == 'Mobile Money') echo 'selected'; ?>>Mobile Money</option>
                    </select>
                </div>
                
                <button type="submit" class="btn">Enregistrer les modifications</button>
                <a href="dashboard.php" class="btn btn-danger">Annuler</a>
            </form>
        </div>
    </div>
    
    <footer>
        <p>Gisabo CarWash &copy; 2025 - Tous droits réservés</p>
        <ul class="social-links">
            <li><a href="#"><i class="fab fa-facebook"></i></a></li>
           