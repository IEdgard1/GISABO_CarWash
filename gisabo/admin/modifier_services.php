<?php
// Connexion à la base de données
$bdd = new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8','root','');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Vérifier si on est en mode édition (ID présent dans l'URL)
$isEditMode = isset($_GET['id']);
$service = null;

if($isEditMode) {
    // Récupérer les données du service à modifier
    $service_id = $_GET['id'];
    $stmt = $bdd->prepare("SELECT * FROM services WHERE id_serv = ?");
    $stmt->execute([$service_id]);
    $service = $stmt->fetch();

    if(!$service) {
        header("Location: gestion_services.php");
        exit();
    }
}

// Traitement du formulaire
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nom = $_POST['nom'];
        $description = $_POST['description'];
        $duree = $_POST['duree'];
        $prix = $_POST['prix'];
        
        if($isEditMode) {
            // Mise à jour du service existant
            $sql = "UPDATE services SET 
                    nom = :nom, 
                    description = :description, 
                    durée = :duree, 
                    prix = :prix 
                    WHERE id_serv = :id";
                    
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':id', $service_id);
        } else {
            // Création d'un nouveau service
            $sql = "INSERT INTO services (nom, description, durée, prix) 
                    VALUES (:nom, :description, :duree, :prix)";
                    
            $stmt = $bdd->prepare($sql);
        }
        
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':duree', $duree);
        $stmt->bindParam(':prix', $prix);
        
        $stmt->execute();
        
        header("Location: gestion_services.php?success=1");
        exit();
        
    } catch(PDOException $e) {
        $error_message = "Erreur: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEditMode ? 'Modifier' : 'Ajouter'; ?> un service</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .btn {
            display: inline-block;
            padding: 8px 15px;
            background: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
        
        .btn-danger {
            background: #dc3545;
        }
    </style>
</head>
<body>
    <?php include("main.php"); ?>
    
    <div class="container">
        <h1><?php echo $isEditMode ? 'Modifier' : 'Ajouter'; ?> un service</h1>
        
        <?php if(isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="nom">Nom du service</label>
                <input type="text" id="nom" name="nom" required
                       value="<?php echo $isEditMode ? htmlspecialchars($service['nom']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required><?php 
                    echo $isEditMode ? htmlspecialchars($service['description']) : ''; 
                ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="duree">Durée estimée</label>
                <input type="text" id="duree" name="duree" required
                       value="<?php echo $isEditMode ? htmlspecialchars($service['durée']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="prix">Prix</label>
                <input type="text" id="prix" name="prix" step="0.01" required
                       value="<?php echo $isEditMode ? htmlspecialchars($service['prix']) : ''; ?>">
            </div>
            
            <button type="submit" class="btn"><?php echo $isEditMode ? 'Mettre à jour' : 'Ajouter'; ?></button>
            <a href="gestion_services.php" class="btn btn-danger">Annuler</a>
        </form>
    </div>
</body>
</html>