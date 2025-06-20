<?php 
$bdd=new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8','root','');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
     background-color: var(--dark);
    border-top: 1px solid #e0e0e0;
}

.footer-logo {
    display: flex;
    align-items: center;
    border-radius:10px;
}

.footer-logo img {
    height: 60px;
    margin-right: 100px;
    border-radius:20px;
}

.footer-info p {
    margin: 0;
    font-size: 14px;
    text-align:center;
    padding-left: 270px;
     color:white;
}

.footer-info p:last-child {
    margin-top: 5px;
    
}

.social-icons {
    display: flex;
    gap: 15px;
}

.social-icons a {
    color: inherit;
    font-size: 34px;
    padding-right:20px;
}

.social-icons .fa-facebook { color:rgb(255, 255, 255); }
.social-icons .fa-instagram { color:rgb(252, 249, 250); }
.social-icons .fa-youtube { color:rgb(252, 249, 249); }
    </style>
    
</head>
<body>
    <?php
    include("main1.php")
    ?>
   <div class="container">
        <h2 class="section-title">Contactez-nous</h2>
        
        <div style="display: flex; flex-wrap: wrap; gap: 2rem; margin-bottom: 3rem;">
            <div style="flex: 1; min-width: 300px;">
                <h3 style="margin-bottom: 1rem;">Coordonnées</h3>
                <p><strong>Adresse :</strong>Kigobe, Blv du 28 novembre | Tel:66371844</p>
                <p><strong>Téléphone :</strong> +257 66371844</p>
                <p><strong>Email :</strong> gisabo@gmail.com</p>
                <p><strong>Horaires :</strong></p>
                <ul style="margin-left: 1.5rem;">
                    <li>Lundi-Vendredi : 8h-19h</li>
                    <li>Samedi : 9h-18h</li>
                    <li>Dimanche : 10h-15h</li>
                </ul>
            </div>
            
            <div style="flex: 1; min-width: 300px;">
                <h3 style="margin-bottom: 1rem;">Localisation</h3>
                <div style="height: 300px; background-color: #eee; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    
                </div>
            </div>
        </div>
        
        <h3 style="text-align: center; margin-bottom: 1.5rem;">Formulaire de Contact</h3>
        <form method="POST" action="">
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="sujet">Sujet</label>
                <select id="sujet" name="sujet" required>
                    <option value="">Sélectionnez un sujet</option>
                    <option value="question">Question</option>
                    <option value="reservation">Réservation</option>
                    <option value="reclamation">Réclamation</option>
                    <option value="autre">Autre</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" required></textarea>
            </div>
           <button type="submit" class="btn" name="envoyer">Envoyer le message</button> 
        </form>

        
    <?php
    if(isset($_POST["envoyer"]))
    {
        $recp_nom = $_POST["nom"];
        $recp_email = $_POST["email"];
        $sujet = $_POST["sujet"];
        $message = $_POST["message"];

        $add_cont = "INSERT INTO  contact_form ( nom, email, sujet, message) 
        VALUES ('$recp_nom','$recp_email',' $sujet ',' $message')";
        $bdd->exec($add_cont);
    }
    ?>


    </div>

  <footer class="footer">
    <div class="footer-logo">
        <img src="../admin/image1/gisabo.jpeg" alt="Gisabo CarWash Logo">
        <div class="footer-info">
            <p>Gisabo CarWash &copy; 2025 - Tous droits réservés</p>
            <p>123 Kigobe, Bujumbura | Tel: 66371844</p>
        </div>
    </div>
    
    <div class="social-icons">
        <a href="https://www.facebook.com/profile.php?id=61577200785540" target="_blank">
            <i class="fab fa-facebook"></i>
        </a>
        <a href="https://www.instagram.com/gisabocw/" target="_blank">
            <i class="fab fa-instagram"></i>
        </a>
        <a href="https://www.youtube.com/channel/UCfLu1uub_YnL9hb01JMnM8Q" target="_blank">
            <i class="fab fa-youtube"></i>
        </a>
    </div>
</footer>
</body>
</html>