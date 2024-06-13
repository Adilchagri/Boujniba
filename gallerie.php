<?php

require_once("connexion.php");

?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">

        <title>بوابة جماعة بوجنيبة- gallerie Page</title>

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

            <header class="site-header section-padding-img site-header-image">
                <div class="container">
                    <div class="row">

                        <div class="col-lg-10 col-12 header-info">
                            <h1>
                                <span class="d-block text-primary">اكتشف جمال و طبيعة</span>
                                <span class="d-block text-dark">بوجنيبة</span>
                            </h1>
                        </div>
                    </div>
                </div>

                <img src="images/bj3.jpg" class="header-image img-fluid" alt="">
            </header>
            <?php
                $query = "SELECT * FROM image ";
                $result = mysqli_query($conn, $query);

                // Affichage des images
                if (mysqli_num_rows($result) > 0) {
                    ?>
                    <section class="image-gallery section-padding">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <h2 class="section-title">Galerie d'images</h2>
                                </div>
                            </div>
                            <div class="row">
                            <?php while ($image = mysqli_fetch_assoc($result)) { ?>
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-4"> <!-- Ajout de la classe col-md-4 pour organiser les images -->
                                <a href="#" class="image-link">
                                    <img src="images/<?php echo $image["file"]; ?>" alt="<?php echo $image["titre"]; ?>" class="img-fluid"> <!-- Ajout de la classe img-fluid pour une dimension flexible -->
                                </a>
                            </div>
                            <?php } ?>
                        </div>

                        </div>
                    </section>
                    <?php
                } else {
                    echo "Aucune image trouvée avec le flag 'gallerie'";
                }
             ?>

            <section class="video-carousel section-padding">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="section-title">Carrousel de vidéos</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner text-center">
                                    <div class="carousel-item active">
                                        <video src="videos/video1.mp4" alt="Video 1" controls></video>
                                    </div>
                                    <div class="carousel-item">
                                        <video src="videos/video2.mp4" alt="Video 2" controls></video>
                                    </div>
                                    <div class="carousel-item">
                                        <video src="videos/video3.mp4" alt="Video 3" controls></video>
                                    </div>
                                    <div class="carousel-item">
                                        <video src="videos/video4.mp4" alt="Video 4" controls></video>
                                    </div>
                                    <!-- Ajoutez d'autres vidéos ici -->
                                </div>
                                <a class="carousel-control-prev" href="#carouselExample" role="button" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExample" role="button" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </a>
                            </div>
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