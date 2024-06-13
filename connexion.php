<?php
// Informations de connexion à la base de données
$host = 'localhost';
$username = 'root'; 
$password = ''; 
$dbname = 'boujniba'; 

// Connexion à la base de données
$conn = new mysqli($host, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}




?>