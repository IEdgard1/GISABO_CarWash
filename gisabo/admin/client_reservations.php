<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8', 'root', '');

if (!isset($_SESSION['id_client'])) {
    header("Location: login_cl.php");
    exit();
}

$reservations = $bdd->prepare("SELECT r.*, s.nom as service_nom, s.prix, s.durée 
                              FROM reservations r 
                              JOIN services s ON r.id_serv = s.id_serv 
                              WHERE r.id_client = ? 
                              ORDER BY r.date_reservation DESC");
$reservations->execute([$_SESSION['id_client']]);
$client_reservations = $reservations->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mes Réservations - Gisabo CarWash</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 0 auto; padding: 20px; }
        .logout-btn { 
            background: #e74c3c; color: white; border: none; padding: 8px 15px; 
            cursor: pointer; text-decoration: none; border-radius: 4px; float: right;
        }
        .header-container { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .reservation-section { background: #f9f9f9; padding: 20px; border-radius: 5px; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #2ecc71; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
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
        <h3>Historique de mes Réservations</h3>
        <?php if (count($client_reservations) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Prix</th>
                        <th>Durée</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($client_reservations as $reservation): ?>
                    <tr>
                        <td><?= htmlspecialchars($reservation['service_nom']) ?></td>
                        <td><?= date('d/m/Y', strtotime($reservation['date_reservation'])) ?></td>
                        <td><?= substr($reservation['heure_reservation'], 0, 5) ?></td>
                        <td>
                            <?php 
                            // Conversion du prix en float avant formatage
                            $prix = (float) str_replace([' ', ','], ['', '.'], $reservation['prix']);
                            echo number_format($prix, 0, ',', ' ') . ' FBu';
                            ?>
                        </td>
                        <td><?= $reservation['durée'] ?> min</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune réservation trouvée.</p>
        <?php endif; ?>
    </div>
</body>
</html>