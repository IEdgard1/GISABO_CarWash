<?php 
$bdd = new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8', 'root', '');

// Gestion de la recherche
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$where = '';
if (!empty($search)) {
    $where = " WHERE nom LIKE :search OR description LIKE :search";
}
$query = "SELECT id_serv, nom, description, prix, durée FROM services" . $where . " ORDER BY id_serv ASC";
$req = $bdd->prepare($query);
if (!empty($search)) {
    $req->bindValue(':search', '%'.$search.'%');
}
$req->execute();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gisabo CarWash - Services</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
    --primary: #3498db;
    --secondary: #2980b9;
    --accent: #e74c3c;
    --light: #ecf0f1;
    --dark: #2c3e50;
    --success: #2ecc71;
}
        /* Styles pour la barre de recherche */
        .search-container {
            margin: 20px auto;
            max-width: 600px;
            display: flex;
            gap: 10px;
        }
        .search-input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .search-button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .search-button:hover {
            background-color: #45a049;
        }
        .reset-button {
            padding: 10px 15px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        /* Styles pour l'affichage des services */
        .services-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .service-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }
        .service-name {
            color: #2a6496;
            margin-top: 0;
        }
        .service-price {
            font-weight: bold;
            color: #e67e22;
        }
        .service-duration {
            color: #666;
            font-style: italic;
        }
        .no-results {
            text-align: center;
            padding: 40px;
            font-size: 18px;
            color: #666;
        }
        /* Dans votre fichier CSS */
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
    padding-left: 270px;
     color:white;
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
    padding-right:20px;
}

.social-icons .fa-facebook { color:rgb(255, 255, 255); }
.social-icons .fa-instagram { color:rgb(252, 249, 250); }
.social-icons .fa-youtube { color:rgb(252, 249, 249); }
    </style>
</head>
<body>
    <?php include("main1.php") ?>

    <section class="hero">
        <div class="hero-content">
            <h1>Votre voiture comme neuve en un rien de temps</h1>
            <p class="message">Profitez de nos services de lavage auto haut de gamme avec des produits écologiques et un service irréprochable.</p>
            <a href="inscription.php" class="btn">Réserver maintenant</a>
        </div>
    </section>

    <div class="services-container">
        <h2>Nos Services</h2>
        
        <!-- Barre de recherche fonctionnelle -->
        <form method="get" action="" class="search-container">
            <input type="text" name="search" class="search-input" 
                   placeholder="Rechercher un service..." 
                   value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="search-button">Rechercher</button>
            <?php if (!empty($search)): ?>
                <a href="?" class="reset-button">Réinitialiser</a>
            <?php endif; ?>
        </form>

        <!-- Affichage des résultats -->
        <?php if ($req->rowCount() > 0): ?>
            <?php while ($service = $req->fetch()): ?>
                <?php 
                // Formatage du prix
                $prix = (float)preg_replace('/[^0-9.]/', '', $service['prix']);
                $prix_formate = number_format($prix, 0, ',', ' ');
                ?>
                
                <div class="service-card">
                    <h3 class="service-name"><?= htmlspecialchars($service['nom']) ?></h3>
                    <p class="service-price">Prix: <?= $prix_formate ?> FBU</p>
                    <p class="service-duration">Durée: <?= htmlspecialchars($service['durée']) ?></p>
                    <p><?= htmlspecialchars($service['description']) ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-results">
                Aucun service trouvé<?= !empty($search) ? ' pour "'.htmlspecialchars($search).'"' : '' ?>
            </div>
        <?php endif; ?>
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