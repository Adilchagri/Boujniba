<?php
require_once("connexion.php");

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Vérifier si les données sont valides
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Requête pour vérifier les informations de connexion
        $sql = "SELECT * FROM admin WHERE login = '$email' AND mp = '$password'";
        $result = mysqli_query($conn, $sql);

        // Vérifier si le résultat est valide
        if (mysqli_num_rows($result) > 0) {
            // Démarer la session
            session_start();

            // Stocker les informations de connexion dans la session
            $_SESSION["email"] = $email;
            $_SESSION["password"] = $password;

            // Rediriger vers la page admin.php
            header("Location: admin.php");
            exit;
        } else {
            // Les données de connexion sont fausses, ne pas stocker les informations dans la session
            $error = "Erreur de connexion : email ou mot de passe incorrect";
        }
    } else {
        $error = "Erreur : adresse email invalide";
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

    <title>بوابة جماعة بوجنيبة- Sign In Page</title>

    <!-- CSS FILES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/slick.css"/>
    <link href="css/tooplate-boujniba-Commune.css" rel="stylesheet">
</head>
<body>

<section class="preloader">
    <div class="spinner">
        <span class="sk-inner-circle"></span>
    </div>
</section>

<main>
    <section class="sign-in-form section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto col-12">
                    <h1 class="hero-title text-center mb-5">Sign In</h1>
                    <div class="row">
                        <div class="col-lg-8 col-11 mx-auto">
                            <?php
                            if (!empty($error)) {
                                echo "<div class='alert alert-danger'>$error</div>";
                            }
                            ?>
                            <form role="form" method="post" action="sign-in.php">
                                <div class="form-floating mb-4 p-0">
                                    <input type="email" name="email" id="email" pattern="[^ @]*@[^ @]*" class="form-control" placeholder="Email address" required>
                                    <label for="email">Email address</label>
                                </div>

                                <div class="form-floating p-0">
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                                    <label for="password">Password</label>
                                </div>

                                <button type="submit" class="btn custom-btn form-control mt-4 mb-3">
                                    Sign in
                                </button>
                            </form>
                        </div>
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
                <h4 class="text-white mb-3"><a href="index.php">Little</a> Fashion</h4>
                <p class="copyright-text text-muted mt-lg-5 mb-4 mb-lg-0">Copyright © 2022 <strong>Little Fashion</strong></p>
                <br>
                <p class="copyright-text">Designed by <a href="https://www.tooplate.com/" target="_blank">Tooplate</a></p>
            </div>

            <div class="col-lg-5 col-8">
                <h5 class="text-white mb-3">Sitemap</h5>
                <ul class="footer-menu d-flex flex-wrap">
                    <li class="footer-menu-item"><a href="about.php" class="footer-menu-link">Story</a></li>
                    <li class="footer-menu-item"><a href="#" class="footer-menu-link">Maps</a></li>
                    <li class="footer-menu-item"><a href="#" class="footer-menu-link">Privacy policy</a></li>
                    <li class="footer-menu-item"><a href="#" class="footer-menu-link">FAQs</a></li>
                    <li class="footer-menu-item"><a href="#" class="footer-menu-link">gallerie</a></li>
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
