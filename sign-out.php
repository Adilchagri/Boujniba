<?php
// Déconnexion de l'utilisateur
// Code pour terminer la session, par exemple :
session_start();
session_unset();
session_destroy();

// Redirection vers la page d'accueil (index.php)
header("Location: index.php");
exit();
?>
