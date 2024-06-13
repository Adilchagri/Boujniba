<?php
require_once("connexion.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['name'];
    $email = $_POST['email'];
    $tel = $_POST['phone'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name']; // Nom de l'image téléchargée

    // Emplacement de stockage des images téléchargées
    $targetDir = "images/complaints/";
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);

    // Validation et téléchargement de l'image
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Vérification du type de fichier
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        echo "Désolé, seuls les fichiers JPG, JPEG, PNG & GIF sont autorisés.";
        $uploadOk = 0;
    }

    // Vérifier si le fichier existe déjà
    if (file_exists($targetFile)) {
        echo "Désolé, le fichier existe déjà.";
        $uploadOk = 0;
    }

    // Vérifier la taille de l'image
    if ($_FILES["image"]["size"] > 500000) {
        echo "Désolé, votre fichier est trop volumineux.";
        $uploadOk = 0;
    }

    // Vérifier si $uploadOk est défini à 0 par une erreur
    if ($uploadOk == 0) {
        echo "Désolé, votre fichier n'a pas été téléchargé.";
    // Si tout est bon, télécharger le fichier
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            // Insertion des données dans la table 'complain' avec le chemin de l'image
            $sql = "INSERT INTO complain (nom, email, tel, description, image) VALUES ('$nom', '$email', '$tel', '$description', '$image')";

            if ($conn->query($sql) === TRUE) {
                echo "Les données ont été insérées avec succès.";
            } else {
                echo "Erreur lors de l'insertion des données: " . $conn->error;
            }
        } else {
            echo "Une erreur s'est produite lors du téléchargement de votre fichier.";
        }
    }
}
?>


<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">

        <title>بوابة جماعة بوجنيبة</title>

        <!-- CSS FILES -->
        <link rel="preconnect" href="https://fonts.googleapis.com">

        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;700;900&display=swap" rel="stylesheet">

        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-icons.css" rel="stylesheet">

        <link rel="stylesheet" href="css/slick.css"/>

        <link href="css/tooplate-boujniba-Commune.css" rel="stylesheet">
<!--

Tooplate 2127 Little Fashion

https://www.tooplate.com/view/2127-boujniba-Commune

-->
    </head>
    
    <body>

        <section class="preloader">
            <div class="spinner">
                <span class="sk-inner-circle"></span>
            </div>
        </section>
    
        <main>

            <nav class="navbar navbar-expand-lg">
                <div class="container">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <a class="navbar-brand" href="index.php">
                        <strong>بوابة جماعة  <span>بوجنيبة</span></strong>
                    </a>

                    <div class="d-lg-none">
                        <a href="sign-in.php" class="bi-person custom-icon me-3"></a>

                        <a href="map-detail.php" class="bi-bag custom-icon"></a>
                    </div>

                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav mx-auto">
                            <li class="nav-item">
                                <a class="nav-link active" href="index.php">الرئيسية</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="about.php">المدينة</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="direction.php">الادارة الجماعية</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="majliss.php">المجلس</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="news.php">المستجدات</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="service.php">الخدمات</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="gallerie.php">صوت و صورة</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <header class="site-header section-padding d-flex justify-content-center align-items-center">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-10 col-12">
                            <h1>
                                <span class="d-block text-primary">الخدمات</span>
                            </h1>
                        </div>
                    </div>
                </div>
            </header>
            
            <section class="services-section section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="section-title">الخدمات الالكترونية</h2>
                </div>
            </div>
            <div class="row flex-row">
                <?php
                // Récupérer les services électroniques
                $sql = "SELECT * FROM service WHERE flag = 'electronique'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Afficher les services électroniques
                    while($row = $result->fetch_assoc()) {
                        echo '<div class="col-lg-3 col-md-6 col-sm-12">
                                <a href="' . $row["url"] . '" class="service-link">
                                    <img src="images/services/'. $row["image"].'" alt="'. $row["titre"]. '" style="width:180px;height:80px;">
                                    <h3>'. $row["titre"]. '</h3>
                                </a>
                              </div>';
                    }
                } else {
                    echo "Aucun service électronique trouvé";
                }
                ?>
            </div>
        </div>
    </section>

    <section class="public-services-section section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="section-title">خدمات عامة</h2>
                </div>
            </div>
            <div class="row flex-row">
                <?php
                // Récupérer les services publics
                $sql = "SELECT * FROM service WHERE flag = 'public'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Afficher les services publics
                    while($row = $result->fetch_assoc()) {
                        echo '<div class="col-lg-3 col-md-6 col-sm-12">
                                <a href="' . $row["url"] . '" class="service-link">
                                    <img src="images/services/'. $row["image"].'" alt="'. $row["titre"]. '" style="width:180px;height:80px;">
                                    <h3>'. $row["titre"]. '</h3>
                                </a>
                              </div>';
                    }
                } else {
                    echo "Aucun service public trouvé";
                }
                ?>
            </div>
        </div>
    </section>




            <section class="complaint-section section-padding">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <h2 class="mb-4">تقديم شكاية أو اقتراح </h2>
                            <form class="gallerie-form me-lg-5 pe-lg-3" role="form" method="POST" enctype="multipart/form-data">
                                <div class="form-floating">
                                    <input type="text" name="name" id="name" class="form-control" placeholder="الاسم الكامل" required>
                                    <label for="name">الاسم الكامل</label>
                                </div>
                                <div class="form-floating my-4">
                                    <input type="email" name="email" id="email" pattern="[^ @]*@[^ @]*" class="form-control" placeholder="البريد الالكتروني" required>
                                    <label for="email">البريد الالكتروني</label>
                                </div>
                                <div class="form-floating my-4">
                                    <input type="tel" name="phone" id="phone"class="form-control" placeholder="رقم الهاتف" required>
                                    <label for="phone">رقم الهاتف</label>
                                </div>
                                <div class="form-floating mb-4">
                                    <textarea id="description" name="description" class="form-control" placeholder="الوصف" required style="height: 160px"></textarea>
                                    <label for="description">الوصف</label>
                                </div>
                                <div class="form-floating mb-4">
                                    <input type="file" name="image" accept="image/*" required>
                                    <label for="image">الصورة</label>
                                </div>
                                <div class="col-lg-5 col-6">
                                    <button type="submit" class="form-control">ارسل</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

        </main>

        <footer class="site-footer">
            <div class="container">
                <div class="row">

                    <div class="col-lg-3 col-10 me-auto mb-4">
                        <h4 class="text-white mb-3"> بوابة جماعة <a href="index.php">بوجنيبة</a></h4>
                        <p class="copyright-text text-muted mt-lg-5 mb-4 mb-lg-0">Copyright © 2022 <strong>Adil Chagri</strong></p>
                        <br>
                        <p class="copyright-text">Designed by <a href="" target="_blank">Adil Chagri</a></p>
                    </div>

                    <div class="col-lg-5 col-8">
                        <h5 class="text-white mb-3">Sitemap</h5>

                        <ul class="footer-menu d-flex flex-wrap">
                            <li class="footer-menu-item"><a href="about.php" class="footer-menu-link">المدينة</a></li>

                            <li class="footer-menu-item"><a href="direction.php" class="footer-menu-link">الادارة الجماعية</a></li>

                            <li class="footer-menu-item"><a href="news.php" class="footer-menu-link">المستجدات</a></li>

                            <li class="footer-menu-item"><a href="majliss.php" class="footer-menu-link">المجلس</a></li>

                            <li class="footer-menu-item"><a href="service.php" class="footer-menu-link">الخدمات</a></li>

                            <li class="footer-menu-item"><a href="gallerie.php" class="footer-menu-link">صوت و صورة</a></li>

                        </ul>
                    </div>

                    <div class="col-lg-3 col-4">
                        <h5 class="text-white mb-3">Social</h5>

                        <ul class="social-icon">

                            <li><a href="#" class="social-icon-link bi-youtube"></a></li>

                            <li><a href="#" class="social-icon-link bi-whatsapp"></a></li>

                            <li><a href="#" class="social-icon-link bi-instagram"></a></li>

                            <li><a href="#" class="social-icon-link bi-skype"></a></li>
                        </ul>
                    </div>

                </div>
            </div>
        </footer>

        <!-- JAVASCRIPT FILES -->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="js/Headroom.js"></script>
        <script src="js/jQuery.headroom.js"></script>
        <script src="js/slick.min.js"></script>
        <script src="js/custom.js"></script>

    </body>
</html>
