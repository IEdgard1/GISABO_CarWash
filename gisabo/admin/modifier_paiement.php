<?php
$bdd = new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8','root','');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Récupérer l'ID du paiement
if(!isset($_GET['id'])) {
    header("Location: gestion_reservation.php");
    exit();
}

$paiement_id = $_GET['id'];

// Récupérer les données du paiement
$stmt = $bdd->prepare("SELECT * FROM paiements WHERE id_pai = ?");
$stmt->execute([$paiement_id]);
$paiement = $stmt->fetch();

if(!$paiement) {
    header("Location: gestion_reservation.php");
    exit();
}

// Traitement du formulaire
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nom_cl = $_POST['nom_cl'];
        $montant = $_POST['montant'];
        $date_paiement = $_POST['date_paiement'];
        $mode = $_POST['mode'];
        $adresse = $_POST['adresse'];
        
        $update_stmt = $bdd->prepare("UPDATE paiements SET 
                                    nom_cl = ?, 
                                    montant = ?, 
                                    date_paiement = ?, 
                                    mode = ?, 
                                    adresse = ? 
                                    WHERE id_pai = ?");
        
        $update_stmt->execute([$nom_cl, $montant, $date_paiement, $mode, $adresse, $paiement_id]);
        
        header("Location: gestion_reservation.php?success=1");
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
    <title>Modifier Paiement</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Styles identiques à votre fichier gestion_reservation.php */
    </style>
</head>
<body>
    <?php include("main.php"); ?>
    
    <div class="container">
        <h1>Modifier le Paiement</h1>
        
        <?php if(isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="nom_cl">Nom du client</label>
                <input type="text" id="nom_cl" name="nom_cl" 
                       value="<?php echo htmlspecialchars($paiement['nom_cl']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="montant">Montant</label>
                <input type="number" id="montant" name="montant" 
                       value="<?php echo htmlspecialchars($paiement['montant']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="date_paiement">Date de paiement</label>
                <input type="date" id="date_paiement" name="date_paiement" 
                       value="<?php echo htmlspecialchars($paiement['date_paiement']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="mode">Mode de paiement</label>
                <select id="mode" name="mode" required>
                    <option value="Espèces" <?= ($paiement['mode'] == 'Espèces') ? 'selected' : '' ?>>Espèces</option>
                    <option value="Carte" <?= ($paiement['mode'] == 'Carte') ? 'selected' : '' ?>>Carte</option>
                    <option value="Mobile Money" <?= ($paiement['mode'] == 'Mobile Money') ? 'selected' : '' ?>>Mobile Money</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="adresse">Adresse</label>
                <textarea id="adresse" name="adresse" rows="3" required><?php echo htmlspecialchars($paiement['adresse']); ?></textarea>
            </div>
            
            <button type="submit" class="btn">Enregistrer</button>
            <a href="gestion_reservation.php" class="btn btn-danger">Annuler</a>
        </form>
    </div>
</body>
</html>