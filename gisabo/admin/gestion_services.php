<?php 
$bdd = new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8','root','');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>gestion_services</title>
    <style>
        /* Style pour la barre de recherche */
        .search-container {
            margin: 20px 0;
            display: flex;
            justify-content: flex-end;
        }
        
        .search-box {
            display: flex;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .search-input {
            flex: 1;
            padding: 8px 12px;
            border: none;
            outline: none;
        }
        
        .search-button {
            background: #007BFF;
            color: white;
            border: none;
            padding: 0 15px;
            cursor: pointer;
        }
        
        .search-button:hover {
            background: #0056b3;
        }
        
        /* Style pour les messages */
        .message {
            padding: 10px;
            margin: 10px 0;
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
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<body>
    <?php include("main.php"); ?>
    
    <div class="container">
        <h1>Gestion des Services</h1>
        
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2>Liste des services</h2>
                <a href="+services.php" class="btn">Ajouter un service</a>
            </div>
            
            <!-- Barre de recherche ajoutée ici -->
            <div class="search-container">
                <form method="GET" action="" class="search-box">
                    <input type="text" name="search" class="search-input" 
                           placeholder="Rechercher un service..." 
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    <button type="submit" class="search-button">🔍</button>
                </form>
            </div>
            
            <?php
            // Gestion de la suppression
            if(isset($_GET['del'])) {
                $recpdel = $_GET['del'];
                $delserv = $bdd->query("DELETE FROM services WHERE id_serv = '$recpdel'");
            }
            
            // Construction de la requête avec recherche
            $sql = "SELECT * FROM services";
            $params = [];
            
            if(isset($_GET['search']) && !empty($_GET['search'])) {
                $searchTerm = '%' . $_GET['search'] . '%';
                $sql .= " WHERE nom LIKE :search 
                         OR description LIKE :search 
                         OR durée_estimee LIKE :search 
                         OR prix LIKE :search";
                $params[':search'] = $searchTerm;
            }
            
            $sql .= " ORDER BY id_serv";
            
            // Exécution de la requête
            $query = $bdd->prepare($sql);
            $query->execute($params);
            $services = $query->fetchAll();
            $count = count($services);
            
            // Affichage des messages
            if(isset($_GET['search'])) {
                if($count === 0) {
                    echo '<div class="message no-results">Aucun service trouvé pour "' . htmlspecialchars($_GET['search']) . '"</div>';
                } else {
                    echo '<div class="results-count">' . $count . ' service(s) trouvé(s) pour "' . htmlspecialchars($_GET['search']) . '"</div>';
                }
            }
            ?>
            
            <?php if($count > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Description</th>
                        <th>Durée</th>
                        <th>Prix</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($services as $service): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($service["nom"]); ?></td>
                        <td><?php echo htmlspecialchars($service["description"]); ?></td>
                        <td><?php echo htmlspecialchars($service["durée"]); ?></td>
                        <td><?php echo htmlspecialchars($service["prix"]); ?></td>
                        <td>
                            <a href="modifier_services.php?id=<?php echo htmlspecialchars($service['id_serv']); ?>" class="btn">Modifier</a>
                            <a href="gestion_services.php?del=<?php echo htmlspecialchars($service['id_serv']); ?>" 
                               class="btn btn-danger"
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?')">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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