<?php 
$bdd=new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8','root','');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation</title>
 
</head>
<body>
    <?php
    include("main1.php")
    ?>
     <div class="container">
        <h2 class="section-title">Réserver un Service</h2>
        
        <form method="POST"action="">
            <div class="form-group">
                <label for="nom">Nom complet</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            
            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="telephone">Téléphone</label>
                <input type="tel" id="telephone" name="telephone" required>
            </div>
            
            <div class="form-group">
                <label for="service">Service souhaité</label>
                <select id="service" name="service" required>
                    <option value="">Sélectionnez un service</option>
        
         <?php   
                       
              $Affichagereser=$bdd->query('select * from services' );

              while($affichageData=$Affichagereser->fetch())
                  {
         ?> 
         <option  value="<?php echo $affichageData["id_serv"];?>"><?php echo $affichageData["nom"];?></option>
      <!-- À remplir dynamiquement en filtrant ceux libres -->
         <?php
                  }
         ?>
                </select>
            </div>  
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" id="date" name="date" required>
            </div>
            
            <div class="form-group">
                <label for="heure">Heure</label>
                <input type="time" id="heure" name="heure" min="08:00" max="18:00" required>
            </div>
            
            <div class="form-group">
                <label for="mode">Mode de paiyement</label>
                <input id="message" name="mode"></input>
             
            <button type="submit" name="reserver" class="btn">Confirmer la réservation</button>
        </form>
    
        <?php
        if(isset($_POST["reserver"]))
        {
            $nom = $_POST["nom"];
            $email = $_POST["email"];
            $tel = $_POST["telephone"];
            $service = $_POST["service"];
            $date = $_POST["date"];
            $heure = $_POST["heure"];
            $mode = $_POST["mode"];

            $reserv = "INSERT INTO reservations(nom, email, telephone, service_souhaite, date_reservation, heure_reservation, mode_paiement) 
            VALUES ('$nom','$email','$tel','$service','$date','$heure','$mode')";
            $bdd->exec($reserv);
        }
        ?>
        
        <div class="container" style="margin-top: 4rem;">
            <h3 class="section-title">Nos Forfaits</h3>
            <table>
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Description</th>
                        <th>Prix</th>
                        <th>Durée</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Lavage Express</td>
                        <td>Lavage extérieur rapide</td>
                        <td>45.000fbu</td>
                        <td>10 min</td>
                    </tr>
                    <tr>
                        <td>Intérieur + Extérieur</td>
                        <td>Combo lavage extérieur et nettoyage intérieur basique</td>
                        <td>50.000fbu</td>
                        <td>1h30</td>
                    </tr>
                    <tr>
                        <td>Forfait Premium</td>
                        <td>Tous les services inclus + traitement des plastiques</td>
                        <td>70.000fbu</td>
                        <td>5h</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        <p>AutoLave &copy; 2023 - Tous droits réservés</p>
        <ul class="social-links">
            <li><a href="#"><i class="fab fa-facebook"></i></a></li>
            <li><a href="#"><i class="fab fa-instagram"></i></a></li>
            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
        </ul>
        <p>123 Avenue du Lavage, 75000 Paris | 01 23 45 67 89</p>
    </footer>
</body>
</html>