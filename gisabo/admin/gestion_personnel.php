<?php 
$bdd = new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8','root','');

if (isset($_GET['success'])) {
    echo '<div class="message" style="background-color:#d4edda;color:#155724;">Employé modifié avec succès!</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>gestion_personnel</title>
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
        }
        
        .no-results {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .results-count {
            font-style: italic;
            color: #666;
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
</head>
<body>
    <?php include("main.php"); ?>
    
    <div class="container">
        <h1>Gestion du Personnel</h1>
        
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2>Liste des employés</h2>
                <a href="+amploye.php" class="btn">Ajouter un employé</a>
            </div>
            
            <!-- Barre de recherche -->
            <div class="search-container">
                <form method="GET" action="" class="search-box">
                    <input type="text" name="search" class="search-input" 
                           placeholder="Rechercher un employé..." 
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    <button type="submit" class="search-button">🔍</button>
                </form>
            </div>
            
            <?php
            // Gestion de la suppression
            if(isset($_GET['del'])) {
                $recpdel = $_GET['del'];
                $delempl = $bdd->query("DELETE FROM employés WHERE id_empl = '$recpdel'");
            }
            
            // Construction de la requête avec recherche
            $sql = "SELECT * FROM employés";
            $params = [];
            
            if(isset($_GET['search']) && !empty($_GET['search'])) {
                $searchTerm = '%' . $_GET['search'] . '%';
                $sql .= " WHERE nom_complet LIKE :search 
                         OR email LIKE :search 
                         OR telephone LIKE :search 
                         OR rôle LIKE :search 
                         OR mot_de_passe LIKE :search
                         OR adresse LIKE :search";
                $params[':search'] = $searchTerm;
            }
            
            $sql .= " ORDER BY id_empl";
            
            // Exécution de la requête
            $query = $bdd->prepare($sql);
            $query->execute($params);
            $results = $query->fetchAll();
            $count = count($results);
            
            // Affichage des messages
            if(isset($_GET['search'])) {
                if($count === 0) {
                    echo '<div class="message no-results">Aucun employé trouvé pour "' . htmlspecialchars($_GET['search']) . '"</div>';
                } else {
                    echo '<div class="results-count">' . $count . ' résultat(s) trouvé(s) pour "' . htmlspecialchars($_GET['search']) . '"</div>';
                }
            }
            ?>
            
            <?php if($count > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Telephone</th>
                        <th>Password</th>
                        <th>Rôle</th>
                        <th>Adresse</th>
                        <th>Disponibilite</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($results as $affichageData): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($affichageData["nom_complet"]); ?></td>
                        <td><?php echo htmlspecialchars($affichageData["email"]); ?></td>
                        <td><?php echo htmlspecialchars($affichageData["telephone"]); ?></td>
                        <td><?php echo !empty($affichageData["mot_de_passe"]) ? "*****" : "Non défini"; ?></td>
                        <td><?php echo htmlspecialchars($affichageData["rôle"]); ?></td>
                        <td><?php echo htmlspecialchars($affichageData["adresse"]); ?></td>
                        <td><span class="btn btn-success">Disponible</span></td>
                        <td>
                            <a href="gestion_personnel.php?del=<?php echo htmlspecialchars($affichageData['id_empl']); ?>" 
                               class="btn btn-danger"
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet employé ?')">Supprimer</a>
                            <a href="modifier_employe.php?id=<?php echo htmlspecialchars($affichageData['id_empl']); ?>" class="btn">Modifier</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
        
        <div class="card">
            <h2>Attribution des tâches</h2>
            
            <form method="POST">
                <div class="form-group">
                    <label for="task-employee">Employé</label>
                    <select id="task-employee" name="task-employee">
                        <option value="">-- Sélectionner un employé --</option>
                        <?php
                        $Affichagepers = $bdd->query('SELECT * FROM employés');
                        while($affichageData = $Affichagepers->fetch()):
                        ?>
                        <option value="<?php echo htmlspecialchars($affichageData["id_empl"]); ?>">
                            <?php echo htmlspecialchars($affichageData["nom_complet"]); ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="task-type">Type de tâche</label>
                    <select id="task-type" name="task-type">
                        <option value="">-- Sélectionner une tâche --</option>
                        <?php
                        $Affichageserv = $bdd->query('SELECT * FROM services');
                        while($affichageData = $Affichageserv->fetch()):
                        ?>
                        <option value="<?php echo htmlspecialchars($affichageData["id_serv"]); ?>">
                            <?php echo htmlspecialchars($affichageData["nom"]); ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="task-details">Détails</label>
                    <textarea id="task-details" name="task-details" rows="3" placeholder="Détails de la tâche..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="task-priority">Priorité</label>
                    <input type="text" name="task-priority">
                </div>
                
                <button type="submit" name="submit" class="btn">Assigner</button>
            </form>

            <?php
            if(isset($_POST["submit"])) {
                $recp_nom = $_POST["task-employee"];
                $recp_type = $_POST["task-type"];
                $recp_detail = $_POST["task-details"];
                $recp_prior = $_POST["task-priority"];

                $stmt = $bdd->prepare("INSERT INTO tâches_attribuées (name_empl, type_de_tache, detail, priorite) VALUES (?, ?, ?, ?)");
                $stmt->execute([$recp_nom, $recp_type, $recp_detail, $recp_prior]);
                
                echo '<div class="message" style="background-color:#d4edda;color:#155724;">Tâche attribuée avec succès!</div>';
            }
            ?>
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