<?php 
$bdd = new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8','root','');
session_start();

// Vérification de la session employé
if (!isset($_SESSION['id_empl'])) {
    header("Location: login.php");
    exit();
}
?>

<?php if(isset($_GET['success'])): ?>
    <div class="alert alert-success">Modification effectuée avec succès!</div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Réservations - Gisabo CarWash</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .search-container {
            margin: 20px 0;
            display: flex;
            justify-content: center;
        }
        .search-box {
            display: flex;
            width: 500px;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .search-input {
            flex: 1;
            padding: 10px 15px;
            border: none;
            outline: none;
            font-size: 16px;
        }
        .search-button {
            background: #007BFF;
            color: white;
            border: none;
            padding: 0 20px;
            cursor: pointer;
            font-size: 16px;
        }
        .search-button:hover {
            background: #0056b3;
        }
        .message {
            padding: 12px;
            margin: 15px 0;
            border-radius: 4px;
            text-align: center;
        }
        .no-results {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .results-count {
            font-style: italic;
            color: #666;
            margin: 10px 0;
            text-align: center;
        }
        .section-title {
            margin-top: 30px;
            color: #333;
            border-bottom: 2px solid #007BFF;
            padding-bottom: 5px;
        }
        .card {
            background: white;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        .btn {
            display: inline-block;
            padding: 8px 12px;
            background: #2ecc71;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 5px;
        }
        .btn-danger {
            background: #e74c3c;
        }
        .btn-success {
            background: #2ecc71;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 14px;
        }
        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 30px;
        }
        .social-links {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        .social-links a {
            color: white;
            font-size: 20px;
        }
        .footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
     background-color: var(--dark);
    border-top: 1px solid #e0e0e0;
}

.footer-logo {
    display: flex;
    align-items: center;
    border-radius:10px;
}

.footer-logo img {
    height: 60px;
    margin-right: 100px;
    border-radius:20px;
}

.footer-info p {
    margin: 0;
    font-size: 14px;
    text-align:center;
    padding-left: 190px;
}

.footer-info p:last-child {
    margin-top: 5px;
}

.social-icons {
    display: flex;
    gap: 15px;
}

.social-icons a {
    color: inherit;
    font-size: 34px;
}

.social-icons .fa-facebook { color:rgb(255, 255, 255); }
.social-icons .fa-instagram { color:rgb(252, 249, 250); }
.social-icons .fa-youtube { color:rgb(252, 249, 249); }
    </style>
</head>
<body>
    <?php include("main.php"); ?>
    
    <div class="container">
        <h1>Gestion des Réservations et Paiements</h1>
        
        <!-- Barre de recherche principale -->
        <div class="search-container">
            <form method="GET" action="" class="search-box">
                <input type="text" name="search" class="search-input" 
                       placeholder="Rechercher une réservation ou un paiement..." 
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                <button type="submit" class="search-button">🔍 Rechercher</button>
            </form>
        </div>
        
        <?php
        // Gestion de la suppression
        if(isset($_GET['del'])) {
            $recpdel = $_GET['del'];
            // Vérifier si c'est une réservation ou un paiement
            if(strpos($recpdel, 'reserv_') === 0) {
                $id = str_replace('reserv_', '', $recpdel);
                $bdd->query("DELETE FROM reservations WHERE id_reserv = '$id'");
                $_SESSION['success_message'] = "Réservation supprimée avec succès";
                header("Location: gestion_reservation.php");
                exit();
            } elseif(strpos($recpdel, 'paiement_') === 0) {
                $id = str_replace('paiement_', '', $recpdel);
                $bdd->query("DELETE FROM paiements WHERE id_pai = '$id'");
                $_SESSION['success_message'] = "Paiement supprimé avec succès";
                header("Location: gestion_reservation.php");
                exit();
            }
        }
        
        // Variables pour les résultats
        $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
        
        // Recherche des réservations avec jointure
        $reservations = [];
        $reservationsCount = 0;
        $sqlReserv = "SELECT reservations.id_reserv, Nom_complet AS Nom_complet, clients.email, clients.téléphone, 
               services.nom AS service_nom, reservations.date_reservation, reservations.heure_reservation
        FROM reservations
        JOIN clients ON reservations.id_client = clients.id_client
        JOIN services ON reservations.id_serv = services.id_serv";
        
        if(!empty($searchTerm)) {
            $searchTermLike = '%' . $searchTerm . '%';
            $sqlReserv .= " WHERE c.nom_complet LIKE ? 
                         OR c.email LIKE ? 
                         OR c.telephone LIKE ? 
                         OR s.nom LIKE ?
                         OR r.date_reservation LIKE ?
                         OR r.heure_reservation LIKE ?
                         ";
            $stmtReserv = $bdd->prepare($sqlReserv);
            $stmtReserv->execute(array_fill(0, 7, $searchTermLike));
        } else {
            $stmtReserv = $bdd->query($sqlReserv);
        }
        $reservations = $stmtReserv->fetchAll();
        $reservationsCount = count($reservations);
        
        // Recherche des paiements
        $paiements = [];
        $paiementsCount = 0;
        $sqlPaiement = "SELECT * FROM paiements";
        if(!empty($searchTerm)) {
            $searchTermLike = '%' . $searchTerm . '%';
            $sqlPaiement .= " WHERE nom_cl LIKE ? 
                            OR montant LIKE ? 
                            OR date_paiement LIKE ? 
                            OR adresse LIKE ?";
            $stmtPaiement = $bdd->prepare($sqlPaiement);
            $stmtPaiement->execute(array_fill(0, 5, $searchTermLike));
        } else {
            $stmtPaiement = $bdd->query($sqlPaiement);
        }
        $paiements = $stmtPaiement->fetchAll();
        $paiementsCount = count($paiements);
        
        // Affichage des messages
        if(!empty($searchTerm)) {
            if($reservationsCount === 0 && $paiementsCount === 0) {
                echo '<div class="message no-results">Aucun résultat trouvé pour "' . htmlspecialchars($searchTerm) . '"</div>';
            } else {
                $totalResults = $reservationsCount + $paiementsCount;
                echo '<div class="results-count">' . $totalResults . ' résultat(s) trouvé(s) pour "' . htmlspecialchars($searchTerm) . '"</div>';
            }
        }
        ?>
        
        <!-- Section Réservations -->
        <div class="card">
            <h2 class="section-title">Réservations (<?php echo $reservationsCount; ?>)</h2>
            
            <?php if($reservationsCount > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Service</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($reservations as $reservation): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($reservation["Nom_complet"]); ?></td>
                        <td><?php echo htmlspecialchars($reservation["email"]); ?></td>
                        <td><?php echo htmlspecialchars($reservation["téléphone"]); ?></td>
                        <td><?php echo htmlspecialchars($reservation["service_nom"]); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($reservation["date_reservation"])); ?></td>
                        <td><?php echo substr($reservation["heure_reservation"], 0, 5); ?></td>
                        <td>
                            <a href="modifier_reservation.php?id=<?php echo htmlspecialchars($reservation['id_reserv']); ?>" class="btn">Modifier</a>
                            <a href="gestion_reservation.php?del=reserv_<?php echo htmlspecialchars($reservation['id_reserv']); ?>" 
                               class="btn btn-danger"
                               onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">Annuler</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="message">Aucune réservation trouvée</div>
            <?php endif; ?>
        </div>
        
        <!-- Section Paiements -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2 class="section-title">Paiements (<?php echo $paiementsCount; ?>)</h2>
                <a href="+paiement.php" class="btn">Ajouter un paiement</a>
            </div>
            
            <?php if($paiementsCount > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Montant</th>
                        <th>Date</th>
                        <th>Mode</th>
                        <th>Adresse</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($paiements as $paiement): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($paiement["nom_cl"]); ?></td>
                        <td><?php echo htmlspecialchars($paiement["montant"]); ?> FBu</td>
                        <td><?php echo date('d/m/Y', strtotime($paiement["date_paiement"])); ?></td>
                        <td><?php echo htmlspecialchars($paiement["mode"]); ?></td>
                        <td><?php echo htmlspecialchars($paiement["adresse"]); ?></td>
                        <td><span class="btn-success">Terminé</span></td>
                        <td>
                            <a href="modifier_paiement.php?id=<?php echo htmlspecialchars($paiement['id_pai']); ?>" class="btn">Modifier</a>
                            <a href="gestion_reservation.php?del=paiement_<?php echo htmlspecialchars($paiement['id_pai']); ?>" 
                               class="btn btn-danger"
                               onclick="return confirm('Êtes-vous sûr de vouloir annuler ce paiement ?')">Annuler</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="message">Aucun paiement trouvé</div>
            <?php endif; ?>
        </div>
    </div>

    <footer class="footer">
    <div class="footer-logo">
        <img src="../admin/image1/gisabo.jpeg" alt="Gisabo CarWash Logo">
        <div class="footer-info">
            <p>Gisabo CarWash &copy; 2025 - Tous droits réservés</p>
            <p>123 Kigobe, Bujumbura | Tel: 66371844</p>
        </div>
    </div>
    
    <div class="social-icons">
        <a href="https://www.facebook.com/profile.php?id=61577200785540" target="_blank">
            <i class="fab fa-facebook"></i>
        </a>
        <a href="https://www.instagram.com/gisabocw/" target="_blank">
            <i class="fab fa-instagram"></i>
        </a>
        <a href="https://www.youtube.com/channel/UCfLu1uub_YnL9hb01JMnM8Q" target="_blank">
            <i class="fab fa-youtube"></i>
        </a>
    </div>
</footer>
</body>
</html>