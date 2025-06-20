<?php
// Connexion à la base de données
$bdd = new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8','root','');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Récupérer l'ID du client à modifier
if(!isset($_GET['id'])) {
    header("Location: gestion_clients.php");
    exit();
}

$client_id = $_GET['id'];

// Récupérer les données du client
$stmt = $bdd->prepare("SELECT * FROM clients WHERE id_client = ?");
$stmt->execute([$client_id]);
$client = $stmt->fetch();

if(!$client) {
    header("Location: gestion_clients.php");
    exit();
}

// Traitement du formulaire de modification
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nom_complet = $_POST['nom_complet'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];
        
        // Start building the SQL query
        $sql = "UPDATE clients SET 
                Nom_complet = :nom_complet, 
                email = :email, 
                téléphone = :telephone";
        
        // Add password update if provided
        if(!empty($_POST['mot_de_passe'])) {
            $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
            $sql .= ", mot_de_passe = :mot_de_passe";
        }
        
        // Add WHERE clause
        $sql .= " WHERE id_client = :id";
        
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':nom_complet', $nom_complet);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':id', $client_id);
        
        if(!empty($_POST['mot_de_passe'])) {
            $stmt->bindParam(':mot_de_passe', $mot_de_passe);
        }
        
        $stmt->execute();
        
        header("Location: gestion_clients.php?success=1");
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
    <title>Modifier Client</title>
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
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
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
        <h1>Modifier le Client</h1>
        
        <?php if(isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="nom_complet">Nom complet</label>
                <input type="text" id="nom_complet" name="nom_complet" 
                       value="<?php echo htmlspecialchars($client['Nom_complet']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" 
                       value="<?php echo htmlspecialchars($client['email']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="mot_de_passe">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe">
            </div>
            
            <div class="form-group">
                <label for="telephone">Téléphone</label>
                <input type="tel" id="telephone" name="telephone" 
                       value="<?php echo htmlspecialchars($client['téléphone']); ?>" required>
            </div>
            
            
            
            <button type="submit" class="btn">Enregistrer</button>
            <a href="gestion_clients.php" class="btn btn-danger">Annuler</a>
        </form>
    </div>
</body>
</html>