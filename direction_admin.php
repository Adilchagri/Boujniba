<?php
require_once("connexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ajout de nouveau contenu
    if (isset($_POST['add'])) {
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $file_type = $_POST['file_type'];
        $fichier = $_FILES['file']['name'];
        $target_dir = ($file_type == 'pdf') ? "pdfs/" : "images/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);

        if (file_exists($target_file)) {
            echo "Le fichier existe déjà.";
        } else {
            move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
            $query = "INSERT INTO direction (titre, description, fichier, file_type) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssss", $titre, $description, $fichier, $file_type);
            $stmt->execute();
            $stmt->close();
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        }
    }

    // Modification du contenu existant
    if (isset($_POST['edit'])) {
        $id = $_POST['edit_id'];
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $file_type = $_POST['file_type'];

        if ($_FILES['file']['name'] != '') {
            $fichier = $_FILES['file']['name'];
            $target_dir = ($file_type == 'pdf') ? "pdfs/" : "images/";
            $target_file = $target_dir . basename($_FILES["file"]["name"]);

            move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

            $query = "UPDATE direction SET titre=?, description=?, fichier=?, file_type=? WHERE id=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssi", $titre, $description, $fichier, $file_type, $id);
        } else {
            $query = "UPDATE direction SET titre=?, description=?, file_type=? WHERE id=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssi", $titre, $description, $file_type, $id);
        }
        $stmt->execute();
        $stmt->close();
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }

    // Suppression du contenu existant
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $query = "DELETE FROM direction WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

$query = "SELECT * FROM direction";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des direction</title>
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
    <h2>Gestion de direction</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Description</th>
                <th>Type de Fichier</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['titre']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['file_type']; ?></td>
                    <td>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger">Supprimer</a>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>">Modifier</button>
                    </td>
                </tr>

                <!-- Modal for editing content -->
                <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel<?php echo $row['id']; ?>">Modifier <?php echo $row['titre']; ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
                                    <div class="form-group">
                                        <label for="titre">Titre :</label>
                                        <input type="text" class="form-control" id="titre" name="titre" value="<?php echo $row['titre']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description :</label>
                                        <textarea class="form-control" id="description" name="description" required><?php echo $row['description']; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="file_type">Type de Fichier :</label>
                                        <select class="form-control" id="file_type" name="file_type" required>
                                            <option value="image" <?php echo ($row['file_type'] == 'image') ? 'selected' : ''; ?>>Image</option>
                                            <option value="pdf" <?php echo ($row['file_type'] == 'pdf') ? 'selected' : ''; ?>>PDF</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="file">Fichier :</label>
                                        <input type="file" class="form-control-file" id="file" name="file">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                    <button type="submit" name="edit" class="btn btn-primary">Enregistrer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Form for adding new content -->
    <div class="row">
        <div class="col-lg-12">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="titre">Titre :</label>
                    <input type="text" class="form-control" id="titre" name="titre" required>
                </div>
                <div class="form-group">
                    <label for="description">Description :</label>
                    <textarea class="form-control" id="description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="file_type">Type de Fichier :</label>
                    <select class="form-control" id="file_type" name="file_type" required>
                        <option value="image">Image</option>
                        <option value="pdf">PDF</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="file">Fichier :</label>
                    <input type="file" class="form-control-file" id="file" name="file" required>
                </div>
                <button type="submit" name="add" class="btn btn-primary">Ajouter</button>
            </form>
        </div>
    </div>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
