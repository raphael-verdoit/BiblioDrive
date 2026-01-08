<?php
session_start();
if (isset($_GET['id_livre'])) {
    $id = $_GET['id_livre'];
    if (isset($_SESSION['panier'][$id])) {
        unset($_SESSION['panier'][$id]);
    }
}
header('Location: panier.php');
exit();