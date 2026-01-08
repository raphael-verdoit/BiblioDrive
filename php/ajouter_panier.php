<?php
session_start();
if (isset($_POST['id_livre'])) {
    $id = $_POST['id_livre'];
    // Initialise le panier s'il n'existe pas
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }
    // Ajoute le livre au panier (l'ID comme clé évite les doublons)
    $_SESSION['panier'][$id] = true;
}
header('Location: panier.php');
exit();