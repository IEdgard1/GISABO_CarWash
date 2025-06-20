<?php
// Connexion à la base de données
$bdd = new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8', 'root', '');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        // Récupérer le chemin de l'image avant suppression
        $query = $bdd->prepare("SELECT image_path FROM galerie WHERE id = ?");
        $query->execute([$id]);
        $image = $query->fetch(PDO::FETCH_ASSOC);
        
        if ($image) {
            // Supprimer le fichier image
            if (file_exists($image['image_path'])) {
                unlink($image['image_path']);
            }
            
            // Supprimer l'entrée de la base de données
            $stmt = $bdd->prepare("DELETE FROM galerie WHERE id = ?");
            $stmt->execute([$id]);
        }
        
        header("Location: parametres.php?delete_success=1");
        exit();
    } catch (PDOException $e) {
        header("Location: parametres.php?delete_error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: parametres.php");
    exit();
}
?>