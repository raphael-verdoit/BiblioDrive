<?php
session_start();
require "db.php";

if (isset($_GET['id'])) {
    $id_livre = $_GET['id'];

    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }

    if (!in_array($id_livre, $_SESSION['panier'])) {
        $_SESSION['panier'][] = $id_livre;
    }
}

header("Location: panier.php"); 
exit(); 
?>