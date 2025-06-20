<?php
$bdd = new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8','root','');

if(isset($_POST['submit'])) {
    $query = $bdd->prepare("UPDATE parametre SET 
                          nom_entreprise = :nom,
                          adresse = :adresse,
                          telephone = :tel,
                          email = :email
                          WHERE id = 1");
    
    $query->execute([
        ':nom' => $_POST['company-name'],
        ':adresse' => $_POST['company-address'],
        ':tel' => $_POST['company-phone'],
        ':email' => $_POST['company-email']
    ]);
    
    header('Location: parametre.php?success=1');
    exit;
}
?>