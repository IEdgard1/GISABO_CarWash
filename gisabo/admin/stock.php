<?php 
$bdd = new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8','root','');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des stocks</title>
    <style>
        /* Styles existants... */
        .btn, .btn1 {
            display: inline-block;
            padding: 6px 12px;
            margin: 2px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn {
            background-color: #dc3545;
            color: white;
            border: 1px solid #dc3545;
        }
        
        .btn:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        
        .btn1 {
            background-color: #28a745;
            color: white;
            border: 1px solid #28a745;
        }
        
        .btn1:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
.stock-faible {
    color: #dc3545; /* Rouge */
    font-weight: bold;
    background-color: #f8d7da; /* Fond rouge clair */
    padding: 3px 8px;
    border-radius: 4px;
}
.stock-moyen {
    color: #ffc107; /* Orange */
    font-weight: bold;
    background-color: #fff3cd; /* Fond orange clair */
    padding: 3px 8px;
    border-radius: 4px;
}
.stock-bon {
    color: #28a745; /* Vert */
    font-weight: bold;
    background-color: #d4edda; /* Fond vert clair */
    padding: 3px 8px;
    border-radius: 4px;
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
        
        /* Autres styles existants... */
    </style>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include("main.php"); ?>
    
    <div class="container">
        <h1>Gestion des Stocks</h1>
        
        <?php
        // Calcul des produits en stock critique
        $criticalStock = $bdd->query("SELECT COUNT(*) FROM produits WHERE quantité <= 5")->fetchColumn();
        if($criticalStock > 0): ?>
        <div class="alert alert-warning">
            <strong>Attention !</strong> <?php echo $criticalStock; ?> produit(s) sont en stock critique.
        </div>   
        <?php endif; ?>
        
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2>Liste des produits</h2>
                <a href="+produit.php" class="btn1">+ Ajouter un produit</a>
            </div>
            
            <!-- Barre de recherche -->
            <div class="search-container">
                <form method="GET" action="" class="search-box">
                    <input type="text" name="search" class="search-input" 
                           placeholder="Rechercher un produit..." 
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    <button type="submit" class="search-button">🔍</button>
                </form>
            </div>
            
            <?php
            // Gestion de la suppression
            if(isset($_GET['del'])) {
                $recprod = $_GET['del'];
                $delprod = $bdd->prepare("DELETE FROM produits WHERE id_prod = ?");
                $delprod->execute([$recprod]);
                
                // Redirection pour éviter la resoumission
                header("Location: stock.php");
                exit();
            }
            
            // Construction de la requête avec recherche
            $sql = "SELECT * FROM produits";
            $where = [];
            $params = [];
            
            if(isset($_GET['search']) && !empty($_GET['search'])) {
                $searchTerm = '%' . $_GET['search'] . '%';
                $where[] = "(nom LIKE :search OR categorie LIKE :search OR fournisseur LIKE :search)";
                $params[':search'] = $searchTerm;
            }
            
            if(!empty($where)) {
                $sql .= " WHERE " . implode(" AND ", $where);
            }
            
            $sql .= " ORDER BY id_prod DESC LIMIT 5";
            
            // Exécution de la requête
            $query = $bdd->prepare($sql);
            $query->execute($params);
            $produits = $query->fetchAll();
            $count = count($produits);
            
            // Affichage des messages de recherche
            if(isset($_GET['search'])) {
                if($count === 0) {
                    echo '<div class="search-message no-results">Aucun produit trouvé pour "' . htmlspecialchars($_GET['search']) . '"</div>';
                } else {
                    echo '<div class="search-message has-results">' . $count . ' produit(s) trouvé(s) pour "' . htmlspecialchars($_GET['search']) . '"</div>';
                }
            }
            ?>
            
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Quantité</th>
                        <th>Fournisseur</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($produits as $produit): 
                        // Détermination du statut du stock
                        $quantite = $produit['quantité'];
                        if($quantite <= 5) {
                            $statutClass = 'stock-critical';
                            $statutText = 'Critique';
                        } elseif($quantite <= 10) {
                            $statutClass = 'stock-warning';
                            $statutText = 'Attention';
                        } else {
                            $statutClass = 'stock-normal';
                            $statutText = 'Normal';
                        }
                    ?>
                    <tr>
    <td><?php echo htmlspecialchars($produit["nom"]); ?></td>
    <td><?php echo htmlspecialchars($produit["categorie"]); ?></td>
    <td><?php echo htmlspecialchars($produit["quantité"]); ?></td>
    <td><?php echo htmlspecialchars($produit["fournisseur"]); ?></td>
    <td>
        <?php
        $quantite = $produit['quantité'];
        if($quantite <= 5) {
            echo '<span style="color: red; font-weight: bold;">Faible</span>';
        } elseif($quantite <= 10) {
            echo '<span style="color: orange; font-weight: bold;">Moyen</span>';
        } else {
            echo '<span style="color: green; font-weight: bold;">Bon</span>';
        }
        ?>
    </td>
    <td>
        <a href="stock.php?del=<?php echo htmlspecialchars($produit['id_prod']); ?>" 
           class="btn"
           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">Supprimer</a>
        <a href="modifier_produit.php?id=<?php echo htmlspecialchars($produit['id_prod']); ?>" class="btn1">Modifier</a>
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