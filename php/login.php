<?php

    if (isset($_POST['login_submit'])){
        $email = $_POST['email'];
        $mdp = $_POST['pass'];

        $sql_login = 'SELECT mel, motdepasse FROM utilisateur WHERE mel = ?';
        $stmt = $connexion->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);
    }
?>

