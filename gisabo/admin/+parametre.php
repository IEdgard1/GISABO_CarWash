 

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

           .card {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.card h2 {
    margin-bottom: 1rem;
    color: #2c3e50;
    border-bottom: 2px solid #3498db;
    padding-bottom: 0.5rem;
}

.btn {
    display: inline-block;
    background-color: #3498db;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #2980b9;
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

       
        .btn {
            background-color: #3498db;
            color: : #f8f9fa;
        }
    </style>
    
</head>
<body>
 <div class="card">
            <h2>Paramètres généraux</h2>
            
            <form method="POST">
                <div class="form-group">
                    <label for="company-name">Nom de l'entreprise</label>
                    <input type="text" name="company-name" >
                </div>
                
                <div class="form-group">
                    <label for="company-address">Adresse</label>
                    <input type="text" name="company-address" >
                </div>
                
                <div class="form-group">
                    <label for="company-phone">Téléphone</label>
                    <input type="tel" name="company-phone" >
                </div>
                
                <div class="form-group">
                    <label for="company-email">Email</label>
                    <input type="email" name="company-email" >
                </div>
                
                <button type="submit" name="submit" class="btn">Enregistrer les paramètres</button>
            </form>

            <?php
    if(isset($_POST["submit"]))
    {
        $recp_nom = $_POST["company-name"];
        $recp_adress = $_POST["company-address"];
        $recp_phone = $_POST["company-phone"];
        $recp_email = $_POST["company-email"];
        

        $add_param = "INSERT INTO `parametre`( `nom_site`, `adresse`, `telephone`, `email`) 
        VALUES ('$recp_nom','$recp_adress','$recp_phone','$recp_email')";
        $bdd->exec($add_param);
    }
    ?>
        </div>
</div>

    <footer>
        <p>Gisabo CarWash &copy; 2025 - Tous droits réservés</p>
        <ul class="social-links">
            <li><a href="#"><i class="fab fa-facebook"></i></a></li>
            <li><a href="#"><i class="fab fa-instagram"></i></a></li>
            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
        </ul>
        <p>123 Kigobe, | Tel:66371844</p>
    </footer>
</body>
</html>