<?php
// Connexion à la base de données
$bdd = new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8', 'root', '');

// Traitement de la suppression multiple
if(isset($_POST['delete_selected']) && !empty($_POST['selected_services'])) {
    $selected_ids = $_POST['selected_services'];
    $placeholders = implode(',', array_fill(0, count($selected_ids), '?'));
    
    try {
        $stmt = $bdd->prepare("DELETE FROM services WHERE id_serv IN ($placeholders)");
        $stmt->execute($selected_ids);
        
        $message = count($selected_ids) . " service(s) supprimé(s) avec succès!";
        echo '<script>alert("'.$message.'"); window.location.href = "services.php";</script>';
        exit();
    } catch(PDOException $e) {
        $error = "Erreur lors de la suppression : " . $e->getMessage();
    }
}

// Récupération de tous les noms de services pour la liste déroulante
$nomsServices = $bdd->query('SELECT id_serv, nom FROM services ORDER BY nom ASC');

// Récupération des services selon les filtres
$serviceSelectionne = isset($_GET['service']) ? $_GET['service'] : '';
$recherche = isset($_GET['recherche']) ? trim($_GET['recherche']) : '';

// Construction de la requête SQL
$where = [];
$params = [];

if($serviceSelectionne) {
    $where[] = "id_serv = :id_service";
    $params[':id_service'] = $serviceSelectionne;
}

if(!empty($recherche)) {
    $where[] = "nom LIKE :recherche"; // Recherche uniquement sur le nom du service
    $params[':recherche'] = '%'.$recherche.'%';
}

$whereClause = !empty($where) ? ' WHERE '.implode(' AND ', $where) : '';
$req = $bdd->prepare("SELECT id_serv, nom, description, prix, durée FROM services $whereClause ORDER BY nom ASC");

foreach($params as $key => $value) {
    $req->bindValue($key, $value);
}

$req->execute();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2a6496;
            --secondary-color: #e67e22;
            --error-color: #f44336;
            --dark-bg: #333;
            --light-bg: #f9f9f9;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 30px;
            color: var(--primary-color);
        }
        
        /* Styles pour la zone de filtrage */
        .filter-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 30px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            gap: 20px;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-width: 250px;
            max-width: 400px;
        }
        
        .search-group {
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }
        
        .filter-group label {
            margin-bottom: 8px;
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .service-filter {
            padding: 12px 15px;
            border-radius: 25px;
            border: 2px solid var(--primary-color);
            font-size: 16px;
            background-color: white;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%232a6496' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 15px;
            padding-right: 40px;
            transition: all 0.3s;
            width: 100%;
        }
        
        .service-filter:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(42, 100, 150, 0.2);
        }
        
        .search-input {
            padding: 12px 15px;
            border-radius: 25px;
            border: 2px solid var(--primary-color);
            font-size: 16px;
            flex: 1;
            min-width: 200px;
            transition: all 0.3s;
        }
        
        .search-input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(42, 100, 150, 0.2);
        }
        
        .filter-button {
            padding: 12px 25px;
            border-radius: 25px;
            border: none;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            white-space: nowrap;
        }
        
        .search-button {
            background-color: var(--primary-color);
            color: white;
        }
        
        .search-button:hover {
            background-color: #1d4b75;
        }
        
        .reset-filter {
            background-color: var(--error-color);
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .reset-filter:hover {
            background-color: #d32f2f;
            color: white;
        }
        
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .service-card {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            background-color: var(--light-bg);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
        }
        
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .service-info h3 {
            color: var(--primary-color);
            margin-top: 0;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 10px;
        }
        
        .price {
            font-weight: bold;
            color: var(--secondary-color);
            margin: 10px 0;
            font-size: 18px;
        }
        
        .duration {
            color: #666;
            font-style: italic;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .duration::before {
            content: "⏱️";
        }
        
        .no-results {
            grid-column: 1 / -1;
            text-align: center;
            padding: 40px;
            font-size: 18px;
            color: #666;
        }
        
        /* Styles pour la case à cocher */
        .checkbox-container {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        
        .service-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        /* Styles pour le bouton de suppression multiple */
        .delete-multiple-container {
            margin: 20px 0;
            text-align: right;
        }
        
        .delete-multiple-btn {
            background-color: var(--error-color);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.3s;
        }
        
        .delete-multiple-btn:hover {
            background-color: #d32f2f;
        }
        
        .delete-multiple-btn:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
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
        }
        
        .footer-logo img {
            height: 60px;
            margin-right: 20px;
            border-radius: 10px;
            color:white;
        }
        
        .footer-info {
            text-align: left;
            padding-left:280px;
        }
        
        .footer-info p {
            margin: 5px 0;
            padding-left: 0;
        }
        
        .social-icons a {
            color: white;
            transition: color 0.3s;
        }
        
        .social-icons a:hover {
            color: var(--secondary-color);
        }
        
        @media (max-width: 768px) {
            .filter-container {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-group {
                order: 1;
                margin-bottom: 20px;
                flex-direction: column;
                gap: 10px;
            }
            
            .filter-group {
                order: 2;
            }
            
            .footer {
                flex-direction: column;
                text-align: center;
            }
            
            .footer-logo {
                margin-bottom: 20px;
                justify-content: center;
            }
        }
    </style>
    <script>
        function toggleDeleteButton() {
            const checkboxes = document.querySelectorAll('.service-checkbox');
            const deleteBtn = document.getElementById('delete-multiple-btn');
            let atLeastOneChecked = false;
            
            checkboxes.forEach(checkbox => {
                if(checkbox.checked) {
                    atLeastOneChecked = true;
                }
            });
            
            deleteBtn.disabled = !atLeastOneChecked;
        }
        
        function confirmDelete() {
            const checkboxes = document.querySelectorAll('.service-checkbox:checked');
            if(checkboxes.length > 0) {
                return confirm(`Êtes-vous sûr de vouloir supprimer les ${checkboxes.length} services sélectionnés ?`);
            }
            return false;
        }
    </script>
</head>
<body>
    <?php include("main1.php") ?>
    
    <div class="container">
        <h2 class="section-title">Nos Services Complets</h2>
        
        <!-- Zone de filtrage -->
        <form method="GET" action="" class="filter-container">
            <!-- Groupe de recherche à gauche -->
            <div class="search-group">
                <input type="text" name="recherche" id="recherche" class="search-input" 
                       placeholder="Rechercher un service..." 
                       value="<?= htmlspecialchars($recherche) ?>">
                
                <button type="submit" class="filter-button search-button">
                    <i class="fas fa-search"></i> Rechercher
                </button>
                
                <?php if($serviceSelectionne || !empty($recherche)): ?>
                    <a href="?" class="filter-button reset-filter">
                        <i class="fas fa-times"></i>
                    </a>
                <?php endif; ?>
            </div>
            
            <!-- Liste déroulante à droite -->
            <div class="filter-group">
                <label for="service">Filtrer par service</label>
                <select name="service" id="service" class="service-filter">
                    <option value="">Tous les services</option>
                    <?php 
                    $nomsServices = $bdd->query('SELECT id_serv, nom FROM services ORDER BY nom ASC');
                    while($nomService = $nomsServices->fetch()): 
                    ?>
                        <option value="<?= $nomService['id_serv'] ?>" 
                            <?= ($serviceSelectionne == $nomService['id_serv']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($nomService['nom']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </form>
        
        <!-- Bouton de suppression multiple -->
        <form method="POST" action="" onsubmit="return confirmDelete()">
            <div class="delete-multiple-container">
                <button type="submit" name="delete_selected" id="delete-multiple-btn" class="delete-multiple-btn" disabled>
                    <i class="fas fa-trash-alt"></i> Supprimer les services sélectionnés
                </button>
            </div>
            
            <!-- Résultats -->
            <div class="services-grid">
                <?php if($req->rowCount() > 0): ?>
                    <?php while ($service = $req->fetch()): ?>
                        <?php
                        $prix = (float)preg_replace('/[^0-9.]/', '', $service['prix']);
                        $prix_formate = number_format($prix, 0, ',', ' ');
                        ?>
                        
                        <div class="service-card">
                            <div class="checkbox-container">
                                <input type="checkbox" name="selected_services[]" value="<?= $service['id_serv'] ?>" 
                                       class="service-checkbox" onchange="toggleDeleteButton()">
                            </div>
                            
                            <div class="service-info">
                                <h3><?= htmlspecialchars($service['nom']) ?></h3>
                                <div class="price"><?= $prix_formate ?> FBU</div>
                                <div class="duration"><?= htmlspecialchars($service['durée']) ?></div>
                                <p><?= htmlspecialchars($service['description']) ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-results">
                        <i class="fas fa-info-circle" style="font-size: 24px; margin-bottom: 10px;"></i>
                        <p>Aucun service ne correspond à votre recherche.</p>
                        <a href="?" class="filter-button reset-filter" style="margin-top: 15px;">
                            <i class="fas fa-times"></i> Réinitialiser
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </form>
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