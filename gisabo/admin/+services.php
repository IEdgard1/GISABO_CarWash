<?php 
$bdd=new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8','root','');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ajouter un service</title>
    <style>

          * {
            box-sizing: border-box;
            margin: 0;
            padding: 3px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        /* Formulaires */
.form-container {
    background-color: white;
    border-radius: 8px;
    border-color: rgba(18, 4, 150, 0.05);
    padding: 45px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    margin-bottom: 20px;
    max-width: 500px;
    margin: 0 auto;
     border-collapse: collapse;
    
    
    
}

.form-container h2 {
    margin-bottom: 30px;
    margin-left: 90px;
    color:rgb(7, 12, 80);
    font-size: 1.5 rem;
   
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #555;
}

.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="password"],
.form-group input[type="tel"],
.form-group input[type="number"],
.form-group input[type="date"],
.form-group input[type="time"],
.form-group input[type="datetime-local"],
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.9rem;
}

.form-group textarea {
    min-height: 100px;
    resize: vertical;
}

.form-row {
    display: flex;
    gap: 15px;
}

.form-row .form-group {
    flex: 1;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
}

        .btn {
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: background-color 0.3s;
}
.btn-back{
    padding: 10px 15px;
    border: none;
    margin-left: 350px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: background-color 0.3s;
    background-color: #3498db;
    color: white;
}

.btn-primary {
    background-color: #3498db;
    color: white;
}

.btn-primary:hover {
    background-color: #2980b9;
}

.btn-secondary {
    background-color: #95a5a6;
    color: white;
}

.btn-secondary:hover {
    background-color: #7f8c8d;
}

.btn-icon {
    background: none;
    border: none;
    color: #3498db;
    cursor: pointer;
    font-size: 1rem;
    margin: 0 5px;
}

.btn-icon:hover {
    color: #2980b9;
}

small {
    font-size: 0.8rem;
    color: #7f8c8d;
    display: block;
    margin-top: 5px;
}

/* Style pour les cases à cocher */
input[type="checkbox"] {
    margin-right: 10px;
}

/* Style pour les champs de fichier */
input[type="file"] {
    padding: 5px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: white;
}
    </style>
</head>
<body>

<?php
include("main.php")
?>

    <div class="form-container">
    <h2>Ajouter/Modifier un un service</h2>
    <a href="gestion_services.php" class="btn-back">back</a>
    <form id="user-form"  method="POST">
        <input type="hidden" id="user_id" name="id">
        
        <div class="form-group">
            <label for="user_nom">Nom_service</label>
            <input type="text" id="user_nom" name="nom" required>
        </div>
        
        <div class="form-group">
            <label for="description">Description</label>
            <input type="text" id="description" name="description" required>
        </div>
        
        <div class="form-group">
            <label for="time">Duree</label>
            <input type="text" id="duree" name="duree">
            
        </div>
        
        <div class="form-group">
            <label for="user_telephone">Prix</label>
            <input type="tel" id="prix" name="prix">
        </div>

       <div class="form-actions">
            <button type="submit" name="submit" class="btn btn-primary">Enregistrer</button>
            <button type="button" class="btn btn-secondary" onclick="resetForm('user-form')">Annuler</button>
        </div> 
    </form>

    <?php
    if(isset($_POST["submit"]))
    {
        $recp_nom = $_POST["nom"];
        $recp_descr = $_POST["description"];
        $recp_duree = $_POST["duree"];
        $recp_prix = $_POST["prix"];
        

        $add_serv = "INSERT INTO `services`( `nom`, `description`, `durée`, `prix`) 
        VALUES ('$recp_nom','$recp_descr','$recp_duree','$recp_prix')";
        $bdd->exec($add_serv);
    }
    ?>

    
</div>
</body>
</html>





