<?php
require("header.php");
require_once("db.php");
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mel = $_POST['mel'];
    $mdp_hashe = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
    
    try {
        $sql = "INSERT INTO utilisateur (mel, motdepasse, nom, prenom, adresse, ville, codepostal, profil) 
                VALUES (:mel, :mdp, :nom, :prenom, :adresse, :ville, :cp, 'client')";
        $stmt = $connexion->prepare($sql);
        $stmt->execute([
            ':mel' => $mel,
            ':mdp' => $mdp_hashe,
            ':nom' => $_POST['nom'],
            ':prenom' => $_POST['prenom'],
            ':adresse' => $_POST['adresse'],
            ':ville' => $_POST['ville'],
            ':cp' => $_POST['cp']
        ]);
        $message = "<div class='alert alert-success'>Compte créé avec succès !</div>";
    } catch (PDOException $e) {
        $message = "<div class='alert alert-danger'>Erreur : " . $e->getMessage() . "</div>";
    }
}
?>
<div class="container mt-5">
    <h2>Créer un compte</h2>
    <?= $message ?>
    <form method="post" class="row g-3">
        <div class="col-md-6"><label>Email</label><input type="email" name="mel" class="form-control" required></div>
        <div class="col-md-6"><label>Mot de passe</label><input type="password" name="mdp" class="form-control" required></div>
        <div class="col-md-6"><label>Nom</label><input type="text" name="nom" class="form-control" required></div>
        <div class="col-md-6"><label>Prénom</label><input type="text" name="prenom" class="form-control" required></div>
        <div class="col-12"><label>Adresse</label><input type="text" name="adresse" class="form-control" required></div>
        <div class="col-md-8"><label>Ville</label><input type="text" name="ville" class="form-control" required></div>
        <div class="col-md-4"><label>Code Postal</label><input type="number" name="cp" class="form-control" required></div>
        <button type="submit" class="btn btn-primary">S'inscrire</button>
    </form>
</div>