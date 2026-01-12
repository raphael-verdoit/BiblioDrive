<?php
session_start();
require("db.php");

if (!isset($_SESSION['mel']) || empty($_SESSION['panier'])) {
    header("Location: accueuil.php");
    exit();
}

$mel = $_SESSION['mel'];
$panier = $_SESSION['panier'];
$date_jour = date('Y-m-d');

try {
    $connexion->beginTransaction();

    $sql = "INSERT INTO emprunter (mel, nolivre, dateemprunt) VALUES (:mel, :nolivre, :dateemprunt)";
    $stmt = $connexion->prepare($sql);

    foreach ($panier as $id_livre) {
        $stmt->execute([
            ':mel' => $mel,
            ':nolivre' => $id_livre,
            ':dateemprunt' => $date_jour
        ]);
    }

    $connexion->commit();
    
    unset($_SESSION['panier']);
    
    echo "<script>alert('Emprunt validé !'); window.location.href='accueuil.php';</script>";

} catch (PDOException $e) {
    $connexion->rollBack();
    die("Erreur lors de l'emprunt : " . $e->getMessage());
}
?>