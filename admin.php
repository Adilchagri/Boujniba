<?php
session_start();
include 'connexion.php'; // Inclure votre fichier de connexion à la base de données

// Vérifier si l'administrateur est connecté
if (isset($_SESSION["email"]) && isset($_SESSION["password"])) {
    $email = $_SESSION["email"];
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">

        <title>بوابة جماعة بوجنيبة - Tableau de Bord</title>

        <!-- CSS FILES -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;700;900&display=swap" rel="stylesheet">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-icons.css" rel="stylesheet">
        <link rel="stylesheet" href="css/slick.css"/>
        <link href="css/tooplate-boujniba-Commune.css" rel="stylesheet">
        <link rel="stylesheet" href="css/admin.css">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <a class="navbar-brand" href="index.php">
                    <strong>بوابة جماعة <span>بوجنيبة</span></strong>
                </a>

                

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php">الرئيسية</a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="direction_admin.php">الادارة الجماعية</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="majliss_admin.php">المجلس</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="news_admin.php">المستجدات</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="service_admin.php">الخدمات</a>
                        </li>
                    
                        <li class="nav-item">
                            <a class="nav-link" href="images.php">الصور</a> <!-- Ajout du lien vers la gestion des images -->
                        </li>
                    </ul>
                    <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="sign_out.php">Sign Out</a>
            </li>
        </ul>
                </div>
            </div>
        </nav>

        <div class="container mt-5">
            <div class="row">
                <div class="col-md-9">
                    <!-- Contenu principal -->
                    <h1>Bienvenue, <?php echo htmlspecialchars($email); ?>!</h1>
                    <p>Votre dashboard administrateur</p>
                </div>
            </div>
        </div>

        <!-- JAVASCRIPT FILES -->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="js/Headroom.js"></script>
        <script src="js/jQuery.headroom.js"></script>
        <script src="js/slick.min.js"></script>
        <script src="js/custom.js"></script>
    </body>
    </html>
    <?php
} else {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: sign-in.php");
    exit;
}
?>
