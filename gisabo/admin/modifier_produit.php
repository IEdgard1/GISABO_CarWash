<?php
$bdd = new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8','root','');

// Récupération du produit à modifier
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = $bdd->prepare("SELECT * FROM produits WHERE id_prod = ?");
    $query->execute([$id]);
    $produit = $query->fetch();
    
    if(!$produit) {
        die("Produit non trouvé");
    }
} else {
    die("ID de produit non spécifié");
}

// Traitement du formulaire de modification
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $categorie = $_POST['categorie'];
    $quantite = $_POST['quantite'];
    $fournisseur = $_POST['fournisseur'];
    
    $update = $bdd->prepare("UPDATE produits SET nom = ?, categorie = ?, quantité = ?, fournisseur = ? WHERE id_prod = ?");
    $update->execute([$nom, $categorie, $quantite, $fournisseur, $id]);
    
    header("Location: stock.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier produit</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .form-actions {
            margin-top: 20px;
            text-align: right;
        }
        
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-save {
            background: #28a745;
            color: white;
        }
        
        .btn-cancel {
            background: #6c757d;
            color: white;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <?php include("main.php"); ?>
    
    <div class="container">
        <h1>Modifier le produit</h1>
        
        <div class="form-container">
            <form method="POST">
                <div class="form-group">
                    <label for="nom">Nom du produit</label>
                    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($produit['nom']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="categorie">Catégorie</label>
                    <input type="text" id="categorie" name="categorie" value="<?= htmlspecialchars($produit['categorie']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="quantite">Quantité</label>
                    <input type="text" id="quantite" name="quantite" value="<?= htmlspecialchars($produit['quantité']) ?>" min="0" required>
                </div>
                
                <div class="form-group">
                    <label for="fournisseur">Fournisseur</label>
                    <input type="text" id="fournisseur" name="fournisseur" value="<?= htmlspecialchars($produit['fournisseur']) ?>" required>
                </div>
                
                <div class="form-actions">
                    <a href="stock.php" class="btn btn-cancel">Annuler</a>
                    <button type="submit" class="btn btn-save">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>