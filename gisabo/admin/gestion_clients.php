<?php 
$bdd = new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8','root','');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Clients</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Styles existants */
        .search-container { margin: 20px 0; }
        .search-box { display: flex; border: 1px solid #ddd; border-radius: 4px; overflow: hidden; max-width: 400px; }
        .search-input { flex: 1; padding: 8px 12px; border: none; outline: none; }
        .search-button { background: #007BFF; color: white; border: none; padding: 0 15px; cursor: pointer; }
        .search-button:hover { background: #0056b3; }
        
        /* Nouveaux styles pour le footer */
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

        /* Style pour le champ document */
        .file-input-container {
            margin: 15px 0;
        }

        .file-input-label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .file-input-info {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <?php include("main.php"); ?>
    
    <div class="container">
        <h1>Gestion des Clients</h1>
        
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2>Liste des clients</h2>
                <a href="ajoutclient.php" class="btn">Ajouter un client</a>
            </div>
            
            <div class="search-container">
                <form method="GET" action="" class="search-box">
                    <input type="text" name="search" class="search-input" 
                           placeholder="Rechercher un client..." 
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    <button type="submit" class="search-button">🔍 Rechercher</button>
                </form>
            </div>
    
            <?php
            if(isset($_GET['del'])) {
                $recpdel = $_GET['del'];
                $delclient = $bdd->prepare("DELETE FROM clients WHERE id_client = ?");
                $delclient->execute([$recpdel]);
            }
            
            if(isset($_GET['success'])) {
                echo '<div class="alert alert-success">Client modifié avec succès!</div>';
            }
            ?>
            
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Téléphone</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM clients";
                    
                    if(isset($_GET['search']) && !empty($_GET['search'])) {
                        $search = '%' . $_GET['search'] . '%';
                        $sql .= " WHERE Nom_complet LIKE :search 
                                 OR email LIKE :search 
                                 OR téléphone LIKE :search";
                        $query = $bdd->prepare($sql);
                        $query->bindParam(':search', $search);
                        $query->execute();
                    } else {
                        $sql .= " ORDER BY id_client DESC LIMIT 5";
                        $query = $bdd->query($sql);
                    }
                    
                    while($affichageData = $query->fetch()):
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($affichageData["Nom_complet"]); ?></td>
                        <td><?php echo htmlspecialchars($affichageData["email"]); ?></td>
                        <td><?php echo !empty($affichageData["mot_de_passe"]) ? "*****" : "Non défini"; ?></td>
                        <td><?php echo htmlspecialchars($affichageData["téléphone"]); ?></td>
                        <td>
                            <a href="gestion_clients.php?del=<?php echo htmlspecialchars($affichageData['id_client']); ?>" class="btn btn-danger">Supprimer</a>
                            <a href="modifier_client.php?id=<?php echo htmlspecialchars($affichageData['id_client']); ?>" class="btn">Modifier</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <div class="card">
            <h2>Envoyer une notification par email</h2>
            
            <form method="POST" action="envoyer_notification.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="notification-client">Sélectionner un client</label>
                    <select id="notification-client" name="client_id" required>
                        <option value="">-- Sélectionner un client --</option>
                        <option value="all">Tous les clients</option>
                        <?php
                        $clients = $bdd->query('SELECT id_client, Nom_complet, email FROM clients WHERE email IS NOT NULL');
                        while($client = $clients->fetch()):
                        ?>
                        <option value="<?php echo htmlspecialchars($client["id_client"]); ?>" data-email="<?php echo htmlspecialchars($client["email"]); ?>">
                            <?php echo htmlspecialchars($client["Nom_complet"]); ?> (<?php echo htmlspecialchars($client["email"]); ?>)
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="notification-subject">Sujet</label>
                    <input type="text" id="notification-subject" name="subject" placeholder="Sujet de l'email" required>
                </div>
                
                <div class="form-group">
                    <label for="notification-message">Message</label>
                    <textarea id="notification-message" name="message" rows="5" placeholder="Votre message..." required></textarea>
                </div>
                
                <div class="file-input-container">
                    <label for="document" class="file-input-label">Joindre un document (PDF ou DOCX uniquement)</label>
                    <input type="file" id="document" name="document" accept=".pdf,.docx">
                    <p class="file-input-info">Formats acceptés: .pdf, .docx (Taille max: 5MB)</p>
                </div>
                
                <button type="submit" class="btn">Envoyer l'email</button>
            </form>
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