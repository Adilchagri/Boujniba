<?php
require_once("connexion.php");

// Code PHP pour gérer les actions CRUD

// Ajouter un contenu
if (isset($_POST['add_content'])) {
    $titre = $_POST['add_titre'];
    $description = $_POST['add_description'];

    // Vérifier si un fichier a été téléchargé
    if (isset($_FILES['add_image']) && $_FILES['add_image']['size'] > 0) {
        $file_name = $_FILES['add_image']['name'];
        $file_tmp = $_FILES['add_image']['tmp_name'];
        
        // Chemin complet vers le dossier où vous voulez enregistrer l'image
        $upload_path = "images/news/" . $file_name;
        
        // Déplacer l'image téléchargée vers le dossier spécifié
        if (move_uploaded_file($file_tmp, $upload_path)) {
            // Évitez les injections SQL en utilisant des requêtes préparées avec des paramètres
            $add_query = "INSERT INTO news (titre, description, image) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $add_query);
            mysqli_stmt_bind_param($stmt, "sss", $titre, $description, $file_name);

            if (mysqli_stmt_execute($stmt)) {
                // Redirection après l'ajout réussi
                header("Location: news_admin.php");
                exit();
            } else {
                echo "Erreur lors de l'ajout du contenu.";
            }
        } else {
            echo "Erreur lors de l'enregistrement de l'image.";
        }
    } else {
        // Gérer le cas où aucune image n'est téléchargée
        echo "Veuillez télécharger une image.";
        exit(); // Arrêter l'exécution du script
    }
}



// Modifier un contenu
if (isset($_POST['edit_content'])) {
    $content_id = $_POST['content_id'];
    $new_titre = $_POST['edit_titre'];
    $new_description = $_POST['edit_description'];

    // Récupérer le nom de l'ancienne image
    $get_old_image_query = "SELECT image FROM news WHERE id = ?";
    $stmt_old_image = mysqli_prepare($conn, $get_old_image_query);
    mysqli_stmt_bind_param($stmt_old_image, "i", $content_id);
    mysqli_stmt_execute($stmt_old_image);
    mysqli_stmt_bind_result($stmt_old_image, $old_image);
    mysqli_stmt_fetch($stmt_old_image);
    mysqli_stmt_close($stmt_old_image);

    // Vérifier si une nouvelle image a été téléchargée
    if (isset($_FILES['edit_image']) && $_FILES['edit_image']['size'] > 0) {
        $file_name = $_FILES['edit_image']['name'];
        $file_tmp = $_FILES['edit_image']['tmp_name'];
        
        // Chemin complet vers le dossier où vous voulez enregistrer la nouvelle image
        $upload_path = "images/news/" . $file_name;
        
        // Supprimer l'ancienne image du dossier
        if (!empty($old_image) && file_exists("images/news/" . $old_image)) {
            unlink("images/news/" . $old_image);
        }

        // Déplacer la nouvelle image téléchargée vers le dossier spécifié
        if (move_uploaded_file($file_tmp, $upload_path)) {
            // Mettre à jour l'image dans la base de données avec le nouveau nom de fichier
            $update_image_query = "UPDATE news SET image = ? WHERE id = ?";
            $stmt_image = mysqli_prepare($conn, $update_image_query);
            mysqli_stmt_bind_param($stmt_image, "si", $file_name, $content_id);
            mysqli_stmt_execute($stmt_image);
        } else {
            echo "Erreur lors du déplacement de l'image.";
            exit();
        }
    }

    // Évitez les injections SQL en utilisant des requêtes préparées avec des paramètres
    $edit_query = "UPDATE news SET titre = ?, description = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $edit_query);
    mysqli_stmt_bind_param($stmt, "ssi", $new_titre, $new_description, $content_id);

    if (mysqli_stmt_execute($stmt)) {
        // Redirection après la modification réussie
        header("Location: news_admin.php");
        exit();
    } else {
        echo "Erreur lors de la modification du contenu.";
    }
}




// Supprimer un contenu
if (isset($_POST['delete_content'])) {
    $content_id = $_POST['content_id'];

    // Évitez les injections SQL en utilisant des requêtes préparées avec des paramètres
    $delete_query = "DELETE FROM news WHERE id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $content_id);

    if (mysqli_stmt_execute($stmt)) {
        // Redirection après la suppression réussie
        header("Location: news_admin.php");
        exit();
    } else {
        echo "Erreur lors de la suppression du contenu.";
    }
}

// Récupérer les données existantes pour affichage dans le tableau
$content_query = "SELECT * FROM news";
$content_result = mysqli_query($conn, $content_query);

// Code HTML pour afficher le tableau de gestion des contenus
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Lien vers le fichier CSS de Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
                            <a class="nav-link" href="service.php">الخدمات</a>
                        </li>
                    
                        <li class="nav-item">
                            <a class="nav-link" href="images.php">الصور</a> <!-- Ajout du lien vers la gestion des images -->
                        </li>
                    </ul>
                    <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="sign-out.php">Sign Out</a>
            </li>
        </ul>
                   
                </div>
            </div>
        </nav>
    <div class="container">
        <h1 class="mt-5">Tableau de Gestion des Contenus</h1>
        <table class="table table-striped mt-4">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($content_result)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['titre']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><img src="images/news/<?php echo $row['image']; ?>" style="width: 100px; height: auto;" alt="<?php echo $row['titre']; ?>"></td>
                    <td>
                        <button class="btn btn-primary" onclick="openEditPopup(<?php echo $row['id']; ?>, '<?php echo $row['titre']; ?>', '<?php echo $row['description']; ?>')">Modifier</button>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="content_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="delete_content" class="btn btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
        <h2>Ajouter un Nouveau Contenu</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="addTitre">Titre:</label>
            <input type="text" id="addTitre" name="add_titre" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="addDescription">Description:</label>
            <textarea id="addDescription" name="add_description" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label for="addImage">Image:</label>
            <input type="file" id="addImage" name="add_image" class="form-control" required>
        </div>
        <button type="submit" name="add_content" class="btn btn-primary">Ajouter Contenu</button>
    </form>


    <!-- Popup de Modification -->
         <!-- Popup de Modification -->
    <div id="editPopup" class="modal" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier Contenu</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <input type="hidden" id="editContentId" name="content_id">
                        <div class="form-group">
                            <label for="editTitre">Titre:</label>
                            <input type="text" id="editTitre" name="edit_titre" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="editDescription">Description:</label>
                            <textarea id="editDescription" name="edit_description" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="editImage">Image:</label>
                            <input type="file" id="editImage" name="edit_image" class="form-control">
                        </div>

                        <button type="submit" name="edit_content" class="btn btn-success">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Lien vers les fichiers JS de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function openEditPopup(contentId, titre, description) {
            document.getElementById('editContentId').value = contentId;
            document.getElementById('editTitre').value = titre;
            document.getElementById('editDescription').value = description;
            $('#editPopup').modal('show'); // Utilisation de jQuery pour afficher le popup
        }
    </script>
</body>
</html>

