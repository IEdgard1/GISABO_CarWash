<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A_propos</title>
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
        <h2 class="section-title">Notre Histoire</h2>
        
        <div style="display: flex; flex-wrap: wrap; gap: 2rem; margin-bottom: 3rem; align-items: center;">
            <div style="flex: 1; min-width: 300px;">
                <h3 style="margin-bottom: 1rem;">Qui sommes-nous ?</h3>
                <p>Fondé en 2025, Gisabo CarWash est né de la passion de son fondateur, Ishimwe Edgard, pour les voitures et le souci du détail. Ce qui a commencé comme un petit centre de lavage manuel est rapidement devenu une référence dans le domaine du nettoyage automobile haut de gamme.</p>
                <p>Aujourd'hui, notre équipe professionnels dévoués s'engage à fournir un service exceptionnel à chaque client, en utilisant des produits écologiques et des techniques innovantes.</p>
            </div>
            
            <div style="flex: 1; min-width: 300px;">
                <div style="height: 300px; background-image:url('../admin/image/ceo.jpg') ; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                   
                </div>
            </div>
        </div>
        
        <h3 style="text-align: center; margin: 3rem 0 1.5rem;">Nos Valeurs</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
            <div style="background-color: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center;">
                <h4 style="color: var(--primary); margin-bottom: 1rem;">Qualité</h4>
                <p>Nous n'utilisons que les meilleurs produits et techniques pour des résultats impeccables et durables.</p>
            </div>
            
            <div style="background-color: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center;">
                <h4 style="color: var(--primary); margin-bottom: 1rem;">Écologie</h4>
                <p>Nos produits sont biodégradables et nous recyclons 90% de l'eau utilisée dans nos processus.</p>
            </div>
            
            <div style="background-color: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-align: center;">
                <h4 style="color: var(--primary); margin-bottom: 1rem;">Service Client</h4>
                <p>Votre satisfaction est notre priorité. Nous sommes à votre écoute pour répondre à tous vos besoins.</p>
            </div>
        </div>
        
        <h3 style="text-align: center; margin-bottom: 1.5rem;">Notre Équipe</h3>
        <div class="team">
            <div class="team-member">
                <div style="width: 150px; height: 150px; background-image:  url('../admin/image/img4.jpg'); border-radius: 40%; margin: 0 auto 1rem;"></div>
                <h4>Ishimwe Edgard</h4>
                <p>Fondateur & CEO</p>
            </div>
            
            <div class="team-member">
                <div style="width: 150px; height: 150px; background-image: url('../admin/image/irag.png'); background-repeat:none; border-radius: 40%; "></div>
                <h4>Elie</h4>
                <p>Responsable Administrateur</p>
            </div>
            
            <div class="team-member">
                <div style="width: 150px; height: 150px; background-image:url('../admin/image/avatar-2.png') ; border-radius: 50%; margin: 0 auto 1rem;"></div>
                <h4>Iraganje</h4>
                <p>Responsable Technique</p>
            </div>
            
            <div class="team-member">
                <div style="width: 150px; height: 150px; background-image: url('../client/image/horug.png'); border-radius: 50%; margin: 0 auto 1rem;"></div>
                <h4>Horugavye</h4>
                <p>Responsable Clientèle</p>
            </div>
        </div>
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