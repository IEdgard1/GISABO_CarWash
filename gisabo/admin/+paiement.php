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
            background-color: #2980b9;
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
                <h2 id="modalTitle">Ajouter/Modifier un paiement</h2>
                <a href="gestion_reservation.php" class="btn">back</a>
            </div>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="employeeName">Nom client</label>
                    <input type="text" id="employeename" name="clientName" required>
                </div>
                <div class="form-group">
                    <label for="montant">Montant</label>
                    <input type="text" id="montant" name="montant"required>
                </div>
                <div class="form-group">
                    <label for="date">Date_paiement</label>
                    <input type="date" id="datepaiement" name="date" required>
                </div>
                <div class="form-group">
                    <label for="mode_paiement">Mode_paiement</label>
                    <input type="text" name="mode">
                </div>
                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <input type="adresse" id="clientAdresse" name="clientAdresse">
                </div>
               <div class="form-actions">
                    <button type="button" class="btn-cancel">Annuler</button>
                    <button type="submit" name="submit" class="btn-submit">Enregistrer</button>
                </div>
            </form>
         <?php
    if(isset($_POST["submit"]))
    {
        $name = $_POST ["clientName"];
        $montant = $_POST["montant"];
        $date = $_POST["date"];
        $mode = $_POST["mode"];
        $adresse = $_POST["clientAdresse"];
        

        $add_empl = "INSERT INTO paiements (nom_cl,montant,date_paiement,mode,adresse) 
        VALUES ('$name','$montant','$date','$mode','$adresse')";
         $bdd->exec($add_empl);
    
    }
    ?>
</body>
</html>