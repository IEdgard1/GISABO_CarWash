<?php 
$bdd=new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8','root','');
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>+employe</title>
    <style>
         * {
            box-sizing: border-box;
            margin: 0;
            padding: 3px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .modal-content {

            background-color: white;
            padding: 55px;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
             margin-left: 330px;
           
        }
         .modal-header h2 {
            color:rgb(7, 12, 80);

        }

        .form-actions {
             display: flex;
             justify-content: flex-end;
             gap: 10px;
             margin-top: 20px;
           }

         .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .form-actions button {
            padding: 10px 20px;
            margin-left: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
        }
        .btn {
    padding: 10px 15px;
    margin-left: 430px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: background-color 0.3s;
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

        .btn-cancel {
            background-color:rgb(194, 8, 8);
            color: #f8f9fa;
        }

        .btn-submit {
            background-color:  #2980b9;;
            color:  #f8f9fa;
        }
    </style>
    
</head>
<body>

<?php
include("main.php")
?>
      <!-- Modal pour ajouter/modifier un employé -->
    <div class="modal" id="employeeModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Ajouter un employé</h2>
                <a href="gestion_personnel.php" class="btn">back</a>
               
            </div>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="employeeName">Nom complet</label>
                    <input type="text" id="employeename" name="employeeName" required>
                </div>
                <div class="form-group">
                    <label for="employeeEmail">Email</label>
                    <input type="email" id="employeeemail" name="employeeEmail"required>
                </div>
                <div class="form-group">
                    <label for="employeePhone">Téléphone</label>
                    <input type="tel" id="employeephone" name="employeePhone">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password">
                </div>
                <div class="form-group">
                    <label for="employeeRole">Rôle</label>
                    <select id="employeerole" name="employeeRole"required>
                        <option value="">Sélectionner un rôle</option>
                        <option value="admin">Admin</option>
                        <option value="netoyeur">netoyeur</option>
                        
                    </select>
                </div>
                <div class="form-group">
                    <label for="employeeStatus">Adresse</label>
                    <input type="adresse" id="employeeAdresse" name="employeeAdresse">
                </div>
             
               <div class="form-actions">
                    <button type="button" class="btn-cancel">Annuler</button>
                    <button type="submit" name="submit" class="btn-submit">Enregistrer</button>
                </div>
            </form>

    <?php
    if(isset($_POST["submit"]))
    {
        $name = $_POST ["employeeName"];
        $email = $_POST["employeeEmail"];
        $tel = $_POST["employeePhone"];
        $passw = $_POST["password"];
        $role = $_POST["employeeRole"];
        $adresse = $_POST["employeeAdresse"];

        $add_empl = "INSERT INTO employés (nom_complet,  email, telephone,mot_de_passe, rôle, adresse) 
        VALUES ('$name','$email','$tel','$passw','$role ','$adresse')";
         $bdd->exec($add_empl);
    }
    ?>

 
</body>
</html>