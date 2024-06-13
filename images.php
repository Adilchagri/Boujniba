<?php
require_once("connexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_image'])) {
        $titre = $_POST['titreImage'];
        $file = $_FILES['fileImage']['name'];
        $target_dir = "images/";
        $target_file = $target_dir . basename($file);
        move_uploaded_file($_FILES['fileImage']['tmp_name'], $target_file);

        $sql = "INSERT INTO image (titre, file) VALUES ('$titre', '$file')";
        $conn->query($sql);
    } elseif (isset($_POST['add_video'])) {
        $titre = $_POST['titreVideo'];
        $file = $_FILES['fileVideo']['name'];
        $target_dir = "videos/";
        $target_file = $target_dir . basename($file);
        move_uploaded_file($_FILES['fileVideo']['tmp_name'], $target_file);

        $sql = "INSERT INTO video (titre, file) VALUES ('$titre', '$file')";
        $conn->query($sql);
    } elseif (isset($_POST['delete_image'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM image WHERE id = $id";
        $conn->query($sql);
    } elseif (isset($_POST['delete_video'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM video WHERE id = $id";
        $conn->query($sql);
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        .image-container {
            display: flex;
            flex-wrap: wrap;
        }
        .image-container div {
            margin-right: 10px; /* Espace entre les images */
        }
        .video-container {
            display: flex;
            flex-wrap: wrap;
        }
        .video-container div {
            margin-right: 10px; /* Espace entre les vidéos */
        }
    </style>
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
                <li class="nav-item"><a class="nav-link active" href="index.php">الرئيسية</a></li>
                <li class="nav-item"><a class="nav-link" href="direction_admin.php">الادارة الجماعية</a></li>
                <li class="nav-item"><a class="nav-link" href="majliss_admin.php">المجلس</a></li>
                <li class="nav-item"><a class="nav-link" href="news_admin.php">المستجدات</a></li>
                <li class="nav-item"><a class="nav-link" href="service_admin.php">الخدمات</a></li>
                <li class="nav-item"><a class="nav-link" href="images.php">الصور</a></li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="sign_out.php">Sign Out</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <h1>Admin Dashboard</h1>

    <h2>Manage Images</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="titreImage" class="form-label">Title</label>
            <input type="text" class="form-control" id="titreImage" name="titreImage" required>
        </div>
        <div class="mb-3">
            <label for="fileImage" class="form-label">Image File</label>
            <input type="file" class="form-control" id="fileImage" name="fileImage" required>
        </div>
        <button type="submit" name="add_image" class="btn btn-primary">Add Image</button>
    </form>

    <h2>Manage Videos</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="titreVideo" class="form-label">Title</label>
            <input type="text" class="form-control" id="titreVideo" name="titreVideo" required>
        </div>
        <div class="mb-3">
            <label for="fileVideo" class="form-label">Video File</label>
            <input type="file" class="form-control" id="fileVideo" name="fileVideo" required>
        </div>
        <button type="submit" name="add_video" class="btn btn-primary">Add Video</button>
    </form>

    <h2>Image Gallery</h2>
    <div class="image-container">
        <?php
        $result = $conn->query("SELECT * FROM image");
        while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<img src='images/" . $row['file'] . "' alt='" . $row['titre'] . "' style='width: 100px; height: auto;'>";
            echo "<form method='post' style='display: inline;'>
                    <input type='hidden' name='id' value='" . $row['id'] . "'>
                    <button type='submit' name='delete_image' class='btn btn-danger'>Delete</button>
                  </form>";
            echo "</div>";
        }
        ?>
    </div>

    <h2>Video Carousel</h2>
    <div class="video-container">
        <?php
        $result = $conn->query("SELECT * FROM video");
        while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo $row['titre'];
            echo "<video src='videos/" . $row['file'] . "' controls style='width: 100px; height: auto;'></video>";
            echo "<form method='post' style='display: inline;'>
                    <input type='hidden' name='id' value='" . $row['id'] . "'>
                    <button type='submit' name='delete_video' class='btn btn-danger'>Delete</button>
                  </form>";
            echo "</div>";
        }
        ?>
    </div>
</div>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
