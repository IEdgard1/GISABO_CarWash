<?php
// Connexion à la base de données
$bdd = new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8', 'root', '');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 1. Récupération des horaires
$queryHoraires = $bdd->query("SELECT * FROM horaires");
$horaires = [];
while ($row = $queryHoraires->fetch(PDO::FETCH_ASSOC)) {
    $horaires[$row['jour']] = $row;
}

// 2. Récupération des paramètres généraux
$queryParams = $bdd->query("SELECT * FROM parametre WHERE id_par = 1");
$parametres = $queryParams->fetch(PDO::FETCH_ASSOC) ?: [];

// 3. Récupération des images de la galerie
$queryGalerie = $bdd->query("SELECT * FROM galerie ORDER BY date_ajout DESC");
$galerie = $queryGalerie->fetchAll(PDO::FETCH_ASSOC);

// 4. Récupération des images dans la corbeille
$queryCorbeille = $bdd->query("SELECT * FROM corbeille ORDER BY date_suppression DESC");
$corbeille = $queryCorbeille->fetchAll(PDO::FETCH_ASSOC);

// Jours de la semaine
$jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];

// Traitement du formulaire des paramètres généraux
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit_settings'])) {
        try {
            $stmt = $bdd->prepare("
                INSERT INTO parametre (id_par, nom_site, adresse, telephone, email)
                VALUES (1, :nom, :adresse, :tel, :email)
                ON DUPLICATE KEY UPDATE
                    nom_site = VALUES(nom_site),
                    adresse = VALUES(adresse),
                    telephone = VALUES(telephone),
                    email = VALUES(email)
            ");
            
            $stmt->execute([
                ':nom' => $_POST['company-name'],
                ':adresse' => $_POST['company-address'],
                ':tel' => $_POST['company-phone'],
                ':email' => $_POST['company-email']
            ]);
            
            // Recharger les paramètres
            $queryParams = $bdd->query("SELECT * FROM parametre WHERE id_par = 1");
            $parametres = $queryParams->fetch(PDO::FETCH_ASSOC);
            
            $successSettings = true;
        } catch (PDOException $e) {
            $errorSettings = "Erreur : " . $e->getMessage();
        }
    }
    
    // Traitement de l'upload d'image
    if (isset($_POST['submit_image'])) {
        try {
            $titre = $_POST['image_title'];
            $categorie = $_POST['image_category'];
            
            if ($_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/galerie/';
                $webPath = '/uploads/galerie/';
                
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileName = uniqid() . '_' . basename($_FILES['image_file']['name']);
                $destination = $uploadDir . $fileName;
                $webDestination = $webPath . $fileName;
                
                // Vérification de l'extension
                $allowedExtensions = ['jpg', 'jpeg', 'png'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                if (in_array($fileExtension, $allowedExtensions)) {
                    if (move_uploaded_file($_FILES['image_file']['tmp_name'], $destination)) {
                        $stmt = $bdd->prepare("INSERT INTO galerie (titre, categorie, image_path) VALUES (:titre, :categorie, :image_path)");
                        $stmt->execute([
                            ':titre' => $titre,
                            ':categorie' => $categorie,
                            ':image_path' => $webDestination
                        ]);
                        
                        $successImage = "Image ajoutée avec succès!";
                        // Actualiser la galerie
                        $queryGalerie = $bdd->query("SELECT * FROM galerie ORDER BY date_ajout DESC");
                        $galerie = $queryGalerie->fetchAll(PDO::FETCH_ASSOC);
                    } else {
                        $errorImage = "Erreur lors de l'upload.";
                    }
                } else {
                    $errorImage = "Seuls les JPG, JPEG et PNG sont autorisés.";
                }
            } else {
                $errorImage = "Erreur de téléchargement.";
            }
        } catch (PDOException $e) {
            $errorImage = "Erreur : " . $e->getMessage();
        }
    }
}

// Gestion de la suppression vers la corbeille
if (isset($_GET['delete_id'])) {
    try {
        $id = $_GET['delete_id'];
        
        $query = $bdd->prepare("SELECT * FROM galerie WHERE id = ?");
        $query->execute([$id]);
        $image = $query->fetch(PDO::FETCH_ASSOC);
        
        if ($image) {
            // Copie dans la corbeille
            $stmt = $bdd->prepare("INSERT INTO corbeille (titre, categorie, image_path, date_ajout) VALUES (:titre, :categorie, :image_path, :date_ajout)");
            $stmt->execute([
                ':titre' => $image['titre'],
                ':categorie' => $image['categorie'],
                ':image_path' => $image['image_path'],
                ':date_ajout' => $image['date_ajout']
            ]);
            
            // Suppression de la galerie
            $stmt = $bdd->prepare("DELETE FROM galerie WHERE id = ?");
            $stmt->execute([$id]);
            
            $deleteSuccess = true;
            // Actualiser les données
            $queryGalerie = $bdd->query("SELECT * FROM galerie ORDER BY date_ajout DESC");
            $galerie = $queryGalerie->fetchAll(PDO::FETCH_ASSOC);
            $queryCorbeille = $bdd->query("SELECT * FROM corbeille ORDER BY date_suppression DESC");
            $corbeille = $queryCorbeille->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        $deleteError = "Erreur : " . $e->getMessage();
    }
}

// Gestion de la restauration depuis la corbeille
if (isset($_GET['restore_id'])) {
    try {
        $id = $_GET['restore_id'];
        
        $query = $bdd->prepare("SELECT * FROM corbeille WHERE id = ?");
        $query->execute([$id]);
        $image = $query->fetch(PDO::FETCH_ASSOC);
        
        if ($image) {
            // Copie dans la galerie
            $stmt = $bdd->prepare("INSERT INTO galerie (titre, categorie, image_path, date_ajout) VALUES (:titre, :categorie, :image_path, :date_ajout)");
            $stmt->execute([
                ':titre' => $image['titre'],
                ':categorie' => $image['categorie'],
                ':image_path' => $image['image_path'],
                ':date_ajout' => $image['date_ajout']
            ]);
            
            // Suppression de la corbeille
            $stmt = $bdd->prepare("DELETE FROM corbeille WHERE id = ?");
            $stmt->execute([$id]);
            
            $restoreSuccess = true;
            // Actualiser les données
            $queryGalerie = $bdd->query("SELECT * FROM galerie ORDER BY date_ajout DESC");
            $galerie = $queryGalerie->fetchAll(PDO::FETCH_ASSOC);
            $queryCorbeille = $bdd->query("SELECT * FROM corbeille ORDER BY date_suppression DESC");
            $corbeille = $queryCorbeille->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        $restoreError = "Erreur : " . $e->getMessage();
    }
}

// Gestion de la suppression définitive
if (isset($_GET['delete_permanent_id'])) {
    try {
        $id = $_GET['delete_permanent_id'];
        
        $query = $bdd->prepare("SELECT image_path FROM corbeille WHERE id = ?");
        $query->execute([$id]);
        $image = $query->fetch(PDO::FETCH_ASSOC);
        
        if ($image) {
            // Suppression physique du fichier
            $filePath = $_SERVER['DOCUMENT_ROOT'] . $image['image_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            // Suppression de la corbeille
            $stmt = $bdd->prepare("DELETE FROM corbeille WHERE id = ?");
            $stmt->execute([$id]);
            
            $deletePermanentSuccess = true;
            // Actualiser la corbeille
            $queryCorbeille = $bdd->query("SELECT * FROM corbeille ORDER BY date_suppression DESC");
            $corbeille = $queryCorbeille->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        $deletePermanentError = "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres du site</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .card { 
            background: #fff; border-radius: 8px; padding: 20px; 
            margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
        }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { 
            width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; 
        }
        .btn { 
            background: #4CAF50; color: white; border: none; padding: 10px 15px; 
            border-radius: 4px; cursor: pointer; font-size: 16px; margin-top: 10px;
        }
        .btn:hover { background: #45a049; }
        .btn-danger { background: #f44336; }
        .btn-danger:hover { background: #d32f2f; }
        .btn-warning { background: #ff9800; }
        .btn-warning:hover { background: #e68a00; }
        .message { margin: 10px 0; padding: 10px; border-radius: 4px; }
        .success { background: #dff0d8; color: #3c763d; }
        .error { background: #f2dede; color: #a94442; }
        .grid-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 15px; }
        .gallery-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); 
            gap: 15px; 
            margin-top: 20px;
        }
        .gallery-item {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            transition: transform 0.3s;
        }
        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .gallery-item img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 3px;
        }
        .gallery-item p {
            margin: 8px 0;
            font-size: 14px;
        }
        .gallery-item .actions {
            display: flex;
            justify-content: space-between;
            margin-top: 12px;
        }
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-top: 1px solid #e0e0e0;
        }
        .tab-container {
            display: flex;
            margin-bottom: 20px;
        }
        .tab {
            padding: 10px 20px;
            cursor: pointer;
            border: 1px solid #ddd;
            background: #f1f1f1;
        }
        .tab.active {
            background: #fff;
            border-bottom: 1px solid #fff;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
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
        <h1>Paramètres du site</h1>
        
        <!-- Messages -->
        <?php if (isset($successSettings)): ?>
            <div class="message success">Paramètres enregistrés avec succès</div>
        <?php endif; ?>
        <?php if (isset($errorSettings)): ?>
            <div class="message error"><?= htmlspecialchars($errorSettings) ?></div>
        <?php endif; ?>
        <?php if (isset($successImage)): ?>
            <div class="message success"><?= htmlspecialchars($successImage) ?></div>
        <?php endif; ?>
        <?php if (isset($errorImage)): ?>
            <div class="message error"><?= htmlspecialchars($errorImage) ?></div>
        <?php endif; ?>
        <?php if (isset($deleteSuccess)): ?>
            <div class="message success">Image déplacée dans la corbeille</div>
        <?php endif; ?>
        <?php if (isset($deleteError)): ?>
            <div class="message error"><?= htmlspecialchars($deleteError) ?></div>
        <?php endif; ?>
        <?php if (isset($restoreSuccess)): ?>
            <div class="message success">Image restaurée avec succès</div>
        <?php endif; ?>
        <?php if (isset($restoreError)): ?>
            <div class="message error"><?= htmlspecialchars($restoreError) ?></div>
        <?php endif; ?>
        <?php if (isset($deletePermanentSuccess)): ?>
            <div class="message success">Image supprimée définitivement</div>
        <?php endif; ?>
        <?php if (isset($deletePermanentError)): ?>
            <div class="message error"><?= htmlspecialchars($deletePermanentError) ?></div>
        <?php endif; ?>
        
        <!-- Onglets -->
        <div class="tab-container">
            <div class="tab active" onclick="openTab('parametres')">Paramètres</div>
            <div class="tab" onclick="openTab('galerie')">Galerie</div>
            <div class="tab" onclick="openTab('corbeille')">Corbeille</div>
        </div>
        
        <!-- Contenu des onglets -->
        <div id="parametres" class="tab-content active">
            <!-- Paramètres généraux -->
            <div class="card">
                <h2>Paramètres généraux</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="company-name">Nom de l'entreprise</label>
                        <input type="text" id="company-name" name="company-name" 
                               value="<?= htmlspecialchars($parametres['nom_site'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="company-address">Adresse</label>
                        <input type="text" id="company-address" name="company-address" 
                               value="<?= htmlspecialchars($parametres['adresse'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="company-phone">Téléphone</label>
                        <input type="tel" id="company-phone" name="company-phone" 
                               value="<?= htmlspecialchars($parametres['telephone'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="company-email">Email</label>
                        <input type="email" id="company-email" name="company-email" 
                               value="<?= htmlspecialchars($parametres['email'] ?? '') ?>">
                    </div>
                    <button type="submit" name="submit_settings" class="btn">Enregistrer</button>
                </form>
            </div>
            
            <!-- Horaires -->
            <div class="card">
                <h2>Horaires d'ouverture</h2>
                <form method="POST" action="save_hours.php">
                    <?php foreach($jours as $jour): ?>
                    <div class="grid-2col">
                        <div class="form-group">
                            <label for="<?= $jour ?>_open"><?= ucfirst($jour) ?> - Ouverture</label>
                            <input type="time" id="<?= $jour ?>_open" name="<?= $jour ?>_open" 
                                   value="<?= isset($horaires[$jour]) ? substr($horaires[$jour]['heure_ouverture'], 0, 5) : '08:00' ?>">
                        </div>
                        <div class="form-group">
                            <label for="<?= $jour ?>_close"><?= ucfirst($jour) ?> - Fermeture</label>
                            <input type="time" id="<?= $jour ?>_close" name="<?= $jour ?>_close" 
                                   value="<?= isset($horaires[$jour]) ? substr($horaires[$jour]['heure_fermeture'], 0, 5) : '18:00' ?>">
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <button type="submit" name="save_hours" class="btn">Enregistrer les horaires</button>
                </form>
            </div>
        </div>
        
        <div id="galerie" class="tab-content">
            <!-- Galerie -->
            <div class="card">
                <h2>Ajouter une image</h2>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="image_title">Titre</label>
                        <input type="text" id="image_title" name="image_title" required>
                    </div>
                    <div class="form-group">
                        <label for="image_category">Catégorie</label>
                        <select id="image_category" name="image_category" required>
                            <option value="">Choisir une catégorie</option>
                            <option value="inside">Intérieur</option>
                            <option value="Équipe">Équipe</option>
                            <option value="automatique">Automatique</option>
                            <option value="exterieur">Extérieur</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="image_file">Image (JPG/PNG)</label>
                        <input type="file" id="image_file" name="image_file" accept=".jpg,.jpeg,.png" required>
                    </div>
                    <button type="submit" name="submit_image" class="btn">Ajouter</button>
                </form>
            </div>
            
            <div class="card">
                <h2>Galerie d'images</h2>
                <?php if (!empty($galerie)): ?>
                    <div class="gallery-grid">
                        <?php foreach ($galerie as $image): ?>
                            <div class="gallery-item">
                                <img src="<?= htmlspecialchars($image['image_path']) ?>" 
                                     onerror="this.onerror=null;this.src='https://via.placeholder.com/300?text=Image+non+disponible';"
                                     alt="<?= htmlspecialchars($image['titre']) ?>">
                                <p><strong><?= htmlspecialchars($image['titre']) ?></strong></p>
                                <p>Catégorie: <?= htmlspecialchars($image['categorie']) ?></p>
                                <p>Date: <?= date('d/m/Y H:i', strtotime($image['date_ajout'])) ?></p>
                                <div class="actions">
                                    <a href="?delete_id=<?= $image['id'] ?>" class="btn btn-danger" 
                                       onclick="return confirm('Déplacer cette image dans la corbeille?')">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>Aucune image dans la galerie.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <div id="corbeille" class="tab-content">
            <!-- Corbeille -->
            <div class="card">
                <h2>Corbeille</h2>
                <?php if (!empty($corbeille)): ?>
                    <div class="gallery-grid">
                        <?php foreach ($corbeille as $image): ?>
                            <div class="gallery-item">
                                <img src="<?= htmlspecialchars($image['image_path']) ?>" 
                                     onerror="this.onerror=null;this.src='https://via.placeholder.com/300?text=Image+non+disponible';"
                                     alt="<?= htmlspecialchars($image['titre']) ?>">
                                <p><strong><?= htmlspecialchars($image['titre']) ?></strong></p>
                                <p>Catégorie: <?= htmlspecialchars($image['categorie']) ?></p>
                                <p>Ajouté le: <?= date('d/m/Y H:i', strtotime($image['date_ajout'])) ?></p>
                                <p>Supprimé le: <?= date('d/m/Y H:i', strtotime($image['date_suppression'])) ?></p>
                                <div class="actions">
                                    <a href="?restore_id=<?= $image['id'] ?>" class="btn btn-warning"
                                       onclick="return confirm('Restaurer cette image?')">
                                        <i class="fas fa-undo"></i> Restaurer
                                    </a>
                                    <a href="?delete_permanent_id=<?= $image['id'] ?>" class="btn btn-danger"
                                       onclick="return confirm('Supprimer définitivement cette image?')">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>La corbeille est vide.</p>
                <?php endif; ?>
            </div>
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

    <script>
        function openTab(tabName) {
            // Masquer tous les contenus d'onglets
            const tabContents = document.getElementsByClassName('tab-content');
            for (let i = 0; i < tabContents.length; i++) {
                tabContents[i].classList.remove('active');
            }
            
            // Désactiver tous les onglets
            const tabs = document.getElementsByClassName('tab');
            for (let i = 0; i < tabs.length; i++) {
                tabs[i].classList.remove('active');
            }
            
            // Activer l'onglet sélectionné
            document.getElementById(tabName).classList.add('active');
            event.currentTarget.classList.add('active');
        }
    </script>
</body>
</html>