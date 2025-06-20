<?php
$bdd = new PDO('mysql:host=localhost;dbname=gisabo_db;charset=utf8', 'root', '');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerie</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Styles existants */
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: var(--dark);
            border-top: 1px solid #e0e0e0;
        }
        
        /* Nouveaux styles pour la galerie */
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .gallery-item {
            height: 250px;
            background-size: cover;
            background-position: center;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .gallery-item:hover {
            transform: scale(1.03);
        }
        
        .category-title {
            margin: 2rem 0 1rem;
            color: #2a6496;
            border-bottom: 2px solid #2a6496;
            padding-bottom: 8px;
        }

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
    <?php include("main1.php") ?>
    
    <div class="container">
        <h2 class="section-title">Notre Galerie</h2>
        <p style="text-align: center; margin-bottom: 2rem;">Découvrez le travail de notre équipe à travers ces réalisations</p>
        
        <?php
        // Récupérer les catégories distinctes
        $categories = $bdd->query("SELECT DISTINCT categorie FROM galerie ORDER BY categorie");
        
        while($categorie = $categories->fetch()):
            $current_cat = $categorie['categorie'];
        ?>
            <h3 class="category-title"><?= ucfirst($current_cat) ?></h3>
            <div class="gallery">
                <?php
                // Récupérer les images de cette catégorie
                $images = $bdd->prepare("SELECT * FROM galerie WHERE categorie = ? ORDER BY date_ajout DESC");
                $images->execute([$current_cat]);
                
                while($image = $images->fetch()):
                ?>
                    <div class="gallery-item" 
                         style="background-image: url('<?= htmlspecialchars($image['image_path']) ?>');
                         cursor: pointer;"
                         onclick="openModal('<?= htmlspecialchars($image['image_path']) ?>', '<?= htmlspecialchars($image['titre']) ?>')">
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Modal pour afficher l'image en grand -->
    <div id="imageModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.9); z-index: 1000; justify-content: center; align-items: center;">
        <div style="position: relative; max-width: 90%; max-height: 90%;">
            <span onclick="closeModal()" style="position: absolute; top: -40px; right: 0; color: white; font-size: 35px; cursor: pointer;">&times;</span>
            <img id="modalImage" src="" alt="" style="max-width: 100%; max-height: 80vh;">
            <p id="modalCaption" style="color: white; text-align: center; margin-top: 15px;"></p>
        </div>
    </div>

    <script>
        function openModal(imageSrc, title) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('modalCaption').textContent = title;
            document.getElementById('imageModal').style.display = 'flex';
        }
        
        function closeModal() {
            document.getElementById('imageModal').style.display = 'none';
        }
        
        // Fermer la modal en cliquant en dehors de l'image
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if(e.target === this) {
                closeModal();
            }
        });
    </script>

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