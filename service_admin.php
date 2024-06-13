<?php
require_once("connexion.php");

// Fonction pour récupérer les services en fonction du type (électronique ou public)
function getServicesByType($conn, $type) {
    $sql = "SELECT * FROM service WHERE flag = '$type'";
    $result = $conn->query($sql);
    return $result;
}

// Fonction pour ajouter un nouveau service
function addService($conn, $titre, $type, $image, $url) {
    $targetDir = "images/services/";
    $targetFile = $targetDir . basename($image["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Validation du type de fichier
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        return "Désolé, seuls les fichiers JPG, JPEG, PNG & GIF sont autorisés.";
    }

    // Vérifier la taille de l'image
    if ($image["size"] > 500000) {
        return "Désolé, votre fichier est trop volumineux.";
    }

    // Vérifier si le fichier existe déjà
    if (file_exists($targetFile)) {
        // Supprimer l'ancienne image
        unlink($targetFile);
    }

    // Télécharger la nouvelle image
    if (move_uploaded_file($image["tmp_name"], $targetFile)) {
        // Enregistrer le nom de l'image dans la base de données sans le chemin complet
        $imageName = basename($image["name"]);
        $sql = "INSERT INTO service (titre, flag, image, url) VALUES ('$titre', '$type', '$imageName', '$url')";

        if ($conn->query($sql) === TRUE) {
            return "Le service a été ajouté avec succès.";
        } else {
            return "Erreur lors de l'ajout du service: " . $conn->error;
        }
    } else {
        return "Une erreur s'est produite lors du téléchargement de votre fichier.";
    }
}


// Fonction pour supprimer un service
function deleteService($conn, $id) {
    $sql = "DELETE FROM service WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        return "Le service a été supprimé avec succès.";
    } else {
        return "Erreur lors de la suppression du service: " . $conn->error;
    }
}

// Gestion de l'ajout de service
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addService'])) {
    $titre = $_POST['titre'];
    $type = $_POST['type'];
    $url = $_POST['url'];
    $image = $_FILES["image"];

    echo addService($conn, $titre, $type, $image, $url);
}

// Gestion de la suppression de service
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteService'])) {
    $id = $_POST['id'];

    echo deleteService($conn, $id);
}

// Fonction pour récupérer toutes les plaintes
function getAllComplaints($conn) {
    $sql = "SELECT * FROM `complain`";
    $result = $conn->query($sql);
    return $result;
}

// Fonction pour supprimer une plainte
function deleteComplaint($conn, $id) {
    $sql = "DELETE FROM `complain` WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        return "La plainte a été supprimée avec succès.";
    } else {
        return "Erreur lors de la suppression de la plainte: " . $conn->error;
    }
}

// Gestion de la suppression de plainte
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteComplaint'])) {
    $id = $_POST['id'];

    echo deleteComplaint($conn, $id);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Administratif</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
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
                    <a class="nav-link" href="images.php">الصور</a>
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
    <h2>Gestion des Services</h2>
    <form method="post" enctype="multipart/form-data" class="mb-3">
        <input type="text" name="titre" placeholder="Titre du service" required>
        <input type="url" name="url" placeholder="URL du service" required>
        <select name="type" required>
            <option value="">Sélectionnez le type de service</option>
            <option value="electronique">Service Électronique</option>
            <option value="public">Service Public</option>
        </select>
        <input type="file" name="image" accept="image/*" required>
        <button type="submit" name="addService" class="btn btn-primary">Ajouter Service</button>
    </form>
    <hr>
    <!-- Affichage des services publics -->
    <h3>Services Publics</h3>
    <table class='table table-striped'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>URL</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $publicServices = getServicesByType($conn, 'public');
            if ($publicServices->num_rows > 0) {
                while ($row = $publicServices->fetch_assoc()) {
                    echo "<tr><td>".$row["id"]."</td><td>".$row["titre"]."</td><td><a href='".$row["url"]."'>".$row["url"]."</a></td><td>";
                    echo "<form method='post'>";
                    echo "<input type='hidden' name='id' value='".$row["id"]."'>";
                    echo "<button type='submit' name='deleteService' class='btn btn-danger'>Supprimer</button>";
                    echo "</form>";
                    echo "</td></tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Aucun service public trouvé.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <!-- Affichage des services électroniques -->
    <h3>Services Électroniques</h3>
    <table class='table table-striped'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>URL</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $electronicServices = getServicesByType($conn, 'electronique');
            if ($electronicServices->num_rows > 0) {
                while ($row = $electronicServices->fetch_assoc()) {
                    echo "<tr><td>".$row["id"]."</td><td>".$row["titre"]."</td><td><a href='".$row["url"]."'>".$row["url"]."</a></td><td>";
                    echo "<form method='post'>";
                    echo "<input type='hidden' name='id' value='".$row["id"]."'>";
                    echo "<button type='submit' name='deleteService' class='btn btn-danger'>Supprimer</button>";
                    echo "</form>";
                    echo "</td></tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Aucun service électronique trouvé.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <hr>
    <h1>Liste des Plaintes</h1>
    <table class='table table-striped'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom Complet</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Description</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $complaints = getAllComplaints($conn);
            if ($complaints->num_rows > 0) {
                while ($row = $complaints->fetch_assoc()) {
                    echo "<tr><td>".$row["id"]."</td><td>".$row["nom"]."</td><td>".$row["email"]."</td><td>".$row["tel"]."</td><td>".$row["description"]."</td><td>";
                    if (!empty($row["image"])) {
                        echo "<a href='#' data-toggle='modal' data-target='#imageModal' data-image='images/complaints/".$row["image"]."'>";
                        echo "<img src='images/complaints/".$row["image"]."' alt='Image de la plainte' style='width:100px;'></a>";
                    } else {
                        echo "Pas d'image";
                    }
                    echo "</td><td>";
                    echo "<form method='post'>";
                    echo "<input type='hidden' name='id' value='".$row["id"]."'>";
                    echo "<button type='submit' name='deleteComplaint' class='btn btn-danger'>Supprimer</button>";
                    echo "</form>";
                    echo "</td></tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Aucune plainte trouvée.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Image de la Plainte</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img id="modalImage" src="" alt="Image de la plainte" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script>
    $('#imageModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var imageUrl = button.data('image');
        var modal = $(this);
        modal.find('#modalImage').attr('src', imageUrl);
    });
</script>
</body>
</html>