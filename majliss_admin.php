<?php
require_once("connexion.php");

$query = "SELECT * FROM majliss";
$result = mysqli_query($conn, $query);

// Traitement des actions de l'administrateur
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['edit'])) {
        // Modification des données d'un membre du conseil communal
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modifier'])) {
            $id = $_POST['id'];
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $description = $_POST['description'];

            // Mettre à jour les données dans la base de données
            $query = "UPDATE majliss SET nom='$nom', prenom='$prenom', description='$description' WHERE id=$id";
            mysqli_query($conn, $query);
            header("Location: ".$_SERVER['PHP_SELF']); // Rediriger pour éviter la soumission multiple du formulaire
            exit();
        }
    }
    
    // Suppression d'un membre du conseil communal
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        
        // Supprimer le membre de la base de données et du répertoire des images
        $query = "SELECT image FROM majliss WHERE id = $id";
        $result = mysqli_query($conn, $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $imageToDelete = "images/". $row['image'];
            unlink($imageToDelete); // Supprimer l'image du répertoire
        }
        
        $deleteQuery = "DELETE FROM majliss WHERE id = $id";
        mysqli_query($conn, $deleteQuery);
        header("Location: ".$_SERVER['PHP_SELF']); // Rediriger pour éviter la soumission multiple du formulaire
        exit();
    }
    
    // Ajout d'un nouveau membre dans le conseil communal
    if (isset($_POST['add'])) {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
            // Récupérer les autres données du formulaire
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $description = $_POST['description'];
            
            // Vérifier si un fichier a été téléchargé
            if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $_FILES['image']['name'];
                $target_dir = "images/";
                $target_file = $target_dir . basename($_FILES["image"]["name"]);
        
                // Déplacer le fichier téléchargé vers le répertoire images
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
            } else {
                // Gérer le cas où aucun fichier n'a été téléchargé
                $image = ""; // Ou une valeur par défaut
            }
        
            // Insérer les données dans la base de données
            $query = "INSERT INTO majliss (nom, prenom, description, image) VALUES ('$nom', '$prenom', '$description', '$image')";
            mysqli_query($conn, $query);
            header("Location: ".$_SERVER['PHP_SELF']); // Rediriger pour éviter la soumission multiple du formulaire
            exit();
        }
    }
}



?>

<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

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
    <link rel="stylesheet" href="css/slick.css" />
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
        <h1 class="mb-4">Gestion du contenu de la page Majliss</h1>

        <!-- Affichage des données existantes -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?= $row['nom'] ?></td>
                        <td><?= $row['prenom'] ?></td>
                        <td><?= $row['description'] ?></td>
                        <td>
                            <!-- Boutons pour modifier et supprimer le contenu -->
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit" name="edit" class="btn btn-primary btn-sm">Modifier</button>
                                <button type="submit" name="delete" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Formulaire d'ajout de nouveau contenu -->
        <h2 class="mt-5 mb-3">Ajouter un nouveau membre Majliss</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
                <label for="nom" class="form-label">Nom :</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom :</label>
                <input type="text" class="form-control" id="prenom" name="prenom" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description :</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="mb-3">
    <label for="image" class="form-label">Image :</label>
    <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
</div>

            <button type="submit" name="add" class="btn btn-primary">Ajouter</button>
        </form>
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

