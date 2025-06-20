<?php
// Connexion à la base de données
$bdd = new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8', 'root', '');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_hours'])) {
    // Jours de la semaine
    $jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
    
    try {
        $bdd->beginTransaction();
        
        foreach ($jours as $jour) {
            // Validation des heures
            $ouverture = $_POST[$jour.'_open'] ?? '08:00';
            $fermeture = $_POST[$jour.'_close'] ?? '18:00';
            
            // Formatage pour MySQL (ajout des secondes)
            $ouverture .= ':00';
            $fermeture .= ':00';
            
            // Requête UPSERT (insert or update)
            $query = $bdd->prepare("
                INSERT INTO horaires (jour, heure_ouverture, heure_fermeture)
                VALUES (:jour, :ouverture, :fermeture)
                ON DUPLICATE KEY UPDATE
                    heure_ouverture = VALUES(heure_ouverture),
                    heure_fermeture = VALUES(heure_fermeture)
            ");
            
            $query->execute([
                ':jour' => $jour,
                ':ouverture' => $ouverture,
                ':fermeture' => $fermeture
            ]);
        }
        
        $bdd->commit();
        
        // Redirection avec message de succès
        header('Location: parametre.php?success=1');
        exit;
        
    } catch (PDOException $e) {
        $bdd->rollBack();
        die("Erreur lors de la sauvegarde des horaires : " . $e->getMessage());
    }
} else {
    // Redirection si accès direct au script
    header('Location: parametre.php');
    exit;
}