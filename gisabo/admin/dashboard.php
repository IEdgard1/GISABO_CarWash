<?php
$bdd = new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8','root','');
session_start();
include 'config.php';

if (!isset($_SESSION['id_empl'])) {
    header("Location: login.php");
    exit();
}

$employe_id = $_SESSION['id_empl'];
$stmt = $conn->prepare("SELECT nom_complet, rôle FROM employés WHERE id_empl = ?");
$stmt->execute([$employe_id]);
$employe = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
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

<h1>Bienvenue, <?php echo htmlspecialchars($employe['nom_complet']); ?>!</h1>
   
<?php include("main.php"); ?>

<div class="container">
    <h1>Tableau de bord</h1>
    
    <div class="card">
    <h2>Statistiques journalières</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Lavages</th>
                <th>Réservations</th>
                <th>Revenus</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $req_stats = $bdd->prepare("
                SELECT 
                    DATE(reservations.date_reservation),
                    COUNT(DISTINCT tâches_attribuées.id_tache),
                    COUNT(DISTINCT reservations.id_reserv),
                    SUM(services.prix)
                FROM reservations
                LEFT JOIN tâches_attribuées ON DATE(tâches_attribuées.date_tache) = DATE(reservations.date_reservation)
                LEFT JOIN services ON reservations.id_serv = services.id_serv
                WHERE reservations.date_reservation >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                GROUP BY DATE(reservations.date_reservation)
                ORDER BY DATE(reservations.date_reservation) DESC
            ");
            $req_stats->execute();
            $stats = $req_stats->fetchAll(PDO::FETCH_NUM);
            
            foreach($stats as $stat):
            ?>
            <tr>
                <td><?= date('d/m/Y', strtotime($stat[0])) ?></td>
                <td><?= $stat[1] ?></td>
                <td><?= $stat[2] ?></td>
                <td><?= number_format($stat[3] ?? 0, 0, ',', ' ') ?> FBu</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
    
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2>Dernières réservations</h2>
            <div class="search-container">
                <form method="GET" action="" class="search-box">
                    <input type="text" name="search" class="search-input" 
                           placeholder="Rechercher une réservation..." 
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    <button type="submit" class="search-button">🔍</button>
                </form>
            </div>
        </div>
        
        <?php
        // Requête  pour affiche les reservations
       $sql = "SELECT reservations.id_reserv, Nom_complet AS Nom_complet, clients.email, clients.téléphone, 
               services.nom AS service_nom, reservations.date_reservation, reservations.heure_reservation
        FROM reservations
        JOIN clients ON reservations.id_client = clients.id_client
        JOIN services ON reservations.id_serv = services.id_serv";
        
        $params = [];   
           // recherche
        
        if(isset($_GET['search']) && !empty($_GET['search'])) {
            $searchTerm = '%' . $_GET['search'] . '%';
            $sql .= " WHERE clients.Nom_complet LIKE :search 
                     OR clients.email LIKE :search 
                     OR clients.téléphone LIKE :search 
                     OR services.nom LIKE :search
                     OR reservations.date_reservation LIKE :search
                     OR reservations.heure_reservation LIKE :search";
            $params[':search'] = $searchTerm;
        }
        
        $sql .= " ORDER BY reservations.id_reserv DESC LIMIT 5";
        
        $query = $bdd->prepare($sql);
        $query->execute($params);
        $reservations = $query->fetchAll(PDO::FETCH_ASSOC);
        $count = count($reservations);
        
        if(isset($_GET['search'])) {
            if($count === 0) {
                echo '<div class="search-message no-results">Aucune réservation trouvée pour "' . htmlspecialchars($_GET['search']) . '"</div>';
            } else {
                echo '<div class="search-message has-results">' . $count . ' réservation(s) trouvée(s) pour "' . htmlspecialchars($_GET['search']) . '"</div>';
            }
        }
        ?>
        
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
                        <a href="gestion_reservation.php?del=<?php echo htmlspecialchars($reservation['id_reserv']); ?>" 
                           class="btn btn-danger"
                           onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">Annuler</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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