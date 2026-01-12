<?php
session_start();
if (isset($_POST['id_livre'])) {
    $id = $_POST['id_livre'];
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }
    $_SESSION['panier'][$id] = true;
}
header('Location: panier.php');
exit();