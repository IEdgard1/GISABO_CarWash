<?php
// Connexion à la base de données
$bdd = new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8','root','');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Récupérer l'ID de l'employé à modifier
if(!isset($_GET['id'])) {
    header("Location: gestion_personnel.php");
    exit();
}

$employe_id = $_GET['id'];

// Récupérer les données de l'employé
$stmt = $bdd->prepare("SELECT * FROM employés WHERE id_empl = ?");
$stmt->execute([$employe_id]);
$employe = $stmt->fetch();

if(!$employe) {
    header("Location: gestion_personnel.php");
    exit();
}

// Traitement du formulaire de modification
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nom_complet = $_POST['nom_complet'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];
        $role = $_POST['role'];
        $adresse = $_POST['adresse'];
        
        $update_stmt = $bdd->prepare("UPDATE employés SET 
                                    nom_complet = ?, 
                                    email = ?, 
                                    telephone = ?, 
                                    rôle = ?, 
                                    adresse = ? 
                                    WHERE id_empl = ?");
        
        $update_stmt->execute([$nom_complet, $email, $telephone, $role, $adresse, $employe_id]);
        
        header("Location: gestion_personnel.php?success=1");
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
    <title>Modifier Employé</title>
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
        <h1>Modifier l'Employé</h1>
        
        <?php if(isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="nom_complet">Nom complet</label>
                <input type="text" id="nom_complet" name="nom_complet" 
                       value="<?php echo htmlspecialchars($employe['nom_complet']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" 
                       value="<?php echo htmlspecialchars($employe['email']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="telephone">Téléphone</label>
                <input type="tel" id="telephone" name="telephone" 
                       value="<?php echo htmlspecialchars($employe['telephone']); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" 
                       value="<?php echo htmlspecialchars($employe['mot_de_passe']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="role">Rôle</label>
                <select id="role" name="role" required>
                    <option value="netoyeur" <?= ($employe['rôle'] == 'netoyeur') ? 'selected' : '' ?>>Netoyeur</option>
                    <option value="Réceptionniste" <?= ($employe['rôle'] == 'Réceptionniste') ? 'selected' : '' ?>>Réceptionniste</option>
                    <option value="Administrateur" <?= ($employe['rôle'] == 'Administrateur') ? 'selected' : '' ?>>Administrateur</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="adresse">Adresse</label>
                <textarea id="adresse" name="adresse" rows="3" required><?php echo htmlspecialchars($employe['adresse']); ?></textarea>
            </div>
            
            <button type="submit" class="btn">Enregistrer</button>
            <a href="gestion_personnel.php" class="btn btn-danger">Annuler</a>
        </form>
    </div>
</body>
</html>