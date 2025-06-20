<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Carwash - Accueil</title>
    <style>
        :root {
            --primary: #0066cc;
            --secondary: #00cc99;
            --accent: #ff6600;
            --light: #f8f9fa;
            --dark: #343a40;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            text-align: center;
            max-width: 800px;
            width: 100%;
        }
        
        .logo {
            width: 150px;
            height: 150px;
            margin: 0 auto 30px;
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 2px dashed rgba(0, 0, 0, 0.1);
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.05));
        }
        
        .logo::after {
            content: "Logo";
            color: rgba(0, 0, 0, 0.3);
            font-size: 18px;
            font-weight: 500;
        }
        
        h1 {
            color: var(--dark);
            margin-bottom: 40px;
            font-weight: 600;
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.8);
        }
        
        .buttons-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-width: 400px;
            margin: 0 auto;
        }
        
        .btn {
            padding: 16px 24px;
            border: none;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                        transparent, 
                        rgba(255, 255, 255, 0.3), 
                        transparent);
            transition: 0.5s;
            z-index: -1;
        }
        
        .btn:hover::before {
            left: 100%;
        }
        
        .btn-client {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }
        
        .btn-client:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        
        .btn-admin {
            background: linear-gradient(135deg, var(--dark), #495057);
            color: white;
        }
        
        .btn-admin:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        
        footer {
            margin-top: 50px;
            color: #6c757d;
            font-size: 14px;
        }
        
        @media (min-width: 768px) {
            .buttons-container {
                flex-direction: row;
                justify-content: center;
            }
            
            .btn {
                min-width: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
             <img src="admin/image/back.jpg" alt="Photo exemple" width="400" height="300" style="border: 2px solid #333;"> 
        </div>
        
        <h1>Bienvenue à Mon Carwash</h1>
        
        <div class="buttons-container">
            <a href="index.php" class="btn btn-client">Espace Client</a>
            <a href="Login.php" class="btn btn-admin">Espace Admin</a>
        </div>
        
        <footer>
            &copy; 2023 Mon Carwash - Tous droits réservés
        </footer>
    </div>
</body>
</html>