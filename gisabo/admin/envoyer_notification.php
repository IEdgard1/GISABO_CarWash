<?php
// Connexion à la base de données
$bdd = new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8', 'root', '');

// Vérification des données du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = $_POST['client_id'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // Validation des données
    if (empty($subject) || empty($message)) {
        header('Location: gestion_clients.php?error=missing_fields');
        exit;
    }
    
    // Récupération des emails des clients
    if ($client_id === 'all') {
        // Envoi à tous les clients
        $query = $bdd->query("SELECT email FROM clients WHERE email IS NOT NULL");
        $emails = $query->fetchAll(PDO::FETCH_COLUMN);
    } else {
        // Envoi à un client spécifique
        $query = $bdd->prepare("SELECT email FROM clients WHERE id_client = ?");
        $query->execute([$client_id]);
        $email = $query->fetchColumn();
        $emails = $email ? [$email] : [];
    }
    
    // Configuration de l'envoi d'email (à adapter selon votre configuration)
    $headers = "From: gisaboCW@gmail.com\r\n";
    $headers .= "Reply-To: ishedgard@gmail.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    $successCount = 0;
    foreach ($emails as $to) {
        // Envoi de l'email
        $mailSent = mail($to, $subject, $message, $headers);
        if ($mailSent) {
            $successCount++;
            
            // Enregistrement dans la base de données
            $insert = $bdd->prepare("INSERT INTO notifications (client_id, subject, message, sent_at) 
                                    VALUES (?, ?, ?, NOW())");
            $insert->execute([$client_id === 'all' ? NULL : $client_id, $subject, $message]);
        }
    }
    
    // Redirection avec message de succès
    $redirectMsg = $successCount > 0 ? 'success' : 'error';
    header("Location: gestion_clients.php?notification=$redirectMsg&count=$successCount");
    exit;
} else {
    header('Location: gestion_clients.php');
    exit;
}