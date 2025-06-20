<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parametre</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
    include("main.php");
    ?>
     <div class="container">
        <h1>Paramètres du Site</h1>
        
        <div class="card">
            <h2>Configuration des horaires d'ouverture</h2>
            
            <form>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="monday-open">Lundi - Ouverture</label>
                        <input type="time" id="monday-open" value="08:00">
                    </div>
                    <div class="form-group">
                        <label for="monday-close">Lundi - Fermeture</label>
                        <input type="time" id="monday-close" value="18:00">
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="tuesday-open">Mardi - Ouverture</label>
                        <input type="time" id="tuesday-open" value="08:00">
                    </div>
                    <div class="form-group">
                        <label for="tuesday-close">Mardi - Fermeture</label>
                        <input type="time" id="tuesday-close" value="18:00">
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="wednesday-open">Mercredi - Ouverture</label>
                        <input type="time" id="wednesday-open" value="08:00">
                    </div>
                    <div class="form-group">
                        <label for="wednesday-close">Mercredi - Fermeture</label>
                        <input type="time" id="wednesday-close" value="18:00">
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="thursday-open">Jeudi - Ouverture</label>
                        <input type="time" id="thursday-open" value="08:00">
                    </div>
                    <div class="form-group">
                        <label for="thursday-close">Jeudi - Fermeture</label>
                        <input type="time" id="thursday-close" value="18:00">
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="friday-open">Vendredi - Ouverture</label>
                        <input type="time" id="friday-open" value="08:00">
                    </div>
                    <div class="form-group">
                        <label for="friday-close">Vendredi - Fermeture</label>
                        <input type="time" id="friday-close" value="18:00">
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="saturday-open">Samedi - Ouverture</label>
                        <input type="time" id="saturday-open" value="09:00">
                    </div>
                    <div class="form-group">
                        <label for="saturday-close">Samedi - Fermeture</label>
                        <input type="time" id="saturday-close" value="17:00">
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="sunday-open">Dimanche - Ouverture</label>
                        <input type="time" id="sunday-open" value="10:00">
                    </div>
                    <div class="form-group">
                        <label for="sunday-close">Dimanche - Fermeture</label>
                        <input type="time" id="sunday-close" value="15:00">
                    </div>
                </div>
                
                <button type="submit" class="btn">Enregistrer les horaires</button>
            </form>
        </div>
        
      <!--  <div class="card">
            <h2>Gestion des tarifs</h2>
            
            <form>
                <div class="form-group">
                    <label>
                        <input type="checkbox" checked>
                        Appliquer des tarifs week-end (+15%)
                    </label>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" checked>
                        Appliquer des tarifs spéciaux pour les clients fidèles
                    </label>
                </div>
                
                <div class="form-group">
                    <label for="discount-rate">Taux de réduction fidélité (%)</label>
                    <input type="number" id="discount-rate" value="10" min="0" max="50">
                </div>
                
                <div class="form-group">
                    <label for="min-visits">Nombre minimum de visites pour fidélité</label>
                    <input type="number" id="min-visits" value="5" min="1">
                </div>
                
                <button type="submit" class="btn">Enregistrer les tarifs</button>
            </form>
        </div>   -->
        
        <div class="card">
            <h2>Paramètres généraux</h2>
            
            <form>
                <div class="form-group">
                    <label for="company-name">Nom de l'entreprise</label>
                    <input type="text" id="company-name" value="Gisabo CarWash">
                </div>
                
                <div class="form-group">
                    <label for="company-address">Adresse</label>
                    <input type="text" id="company-address" value="123 Kigobe, | Tel:66371844">
                </div>
                
                <div class="form-group">
                    <label for="company-phone">Téléphone</label>
                    <input type="tel" id="company-phone" value="Tel:+25766371844">
                </div>
                
                <div class="form-group">
                    <label for="company-email">Email</label>
                    <input type="email" id="company-email" value="gisaboCW@gmail.com">
                </div>
                
                <button type="submit" class="btn">Enregistrer les paramètres</button>
            </form>
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