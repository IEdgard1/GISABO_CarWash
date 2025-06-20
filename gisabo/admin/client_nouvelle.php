<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8', 'root', '');

if (!isset($_SESSION['id_client'])) {
    header("Location: login_cl.php");
    exit();
}

$services = $bdd->query("SELECT * FROM services")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserver'])) {
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
        } catch (PDOException $e) {
            $erreur = "Erreur lors de la réservation : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Nouvelle Réservation - Gisabo CarWash</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 0 auto; padding: 20px; }
        .logout-btn { 
            background: #e74c3c; color: white; border: none; padding: 8px 15px; 
            cursor: pointer; text-decoration: none; border-radius: 4px; float: right;
        }
        .header-container { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .reservation-section { background: #f9f9f9; padding: 20px; border-radius: 5px; margin-bottom: 30px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        select, input { width: 100%; padding: 10px; margin-bottom: 15px; }
        button { background: #2ecc71; color: white; border: none; padding: 12px 20px; cursor: pointer; }
        .service-card { border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 5px; }
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
        <a href="index.php" class="logout-btn">Déconnexion</a>
    </div>

    <div style="margin-bottom: 20px;">
        <a href="client_infos.php" class="nav-button">Mes Informations</a>
        <a href="client_reservations.php" class="nav-button">Mes Réservations</a>
        <a href="client_nouvelle.php" class="nav-button">Nouvelle Réservation</a>
    </div>

    <div class="reservation-section">
        <h3>Nouvelle Réservation</h3>
        
        <?php 
        if (isset($success)) echo "<p style='color:green'>$success</p>";
        if (isset($erreur)) echo "<p style='color:red'>$erreur</p>"; 
        ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Choisissez un service :</label>
                <?php foreach ($services as $service): ?>
                <div class="service-card">
                    <input type="radio" name="service" id="service<?= $service['id_serv'] ?>" value="<?= $service['id_serv'] ?>" required>
                    <label for="service<?= $service['id_serv'] ?>">
                        <strong><?= htmlspecialchars($service['nom']) ?></strong> - 
                        <?php 
                        // Conversion du prix en float avant formatage
                        $prix = (float) str_replace([' ', ','], ['', '.'], $service['prix']);
                        echo number_format($prix, 0, ',', ' ') . ' FBu'; 
                        ?> - 
                        <?= $service['durée'] ?> min
                    </label>
                    <p><?= htmlspecialchars($service['description']) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="form-group">
                <label for="date">Date :</label>
                <input type="date" id="date" name="date" min="<?= date('Y-m-d') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="heure">Heure :</label>
                <input type="time" id="heure" name="heure" min="08:00" max="18:00" required>
            </div>
            
            <button type="submit" name="reserver">Confirmer la réservation</button>
        </form>
    </div>
</body>
</html>