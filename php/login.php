<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require "db.php";

if (isset($_POST['login_submit'])){
    $email = $_POST['email'];
    $mdp = $_POST['pass'];

    try {
        $sql = 'SELECT * FROM utilisateur WHERE mel = ?';
        $stmt = $connexion->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if ($user && $mdp === $user->motdepasse) {
            $_SESSION['mel'] = $user->mel; 
            $_SESSION['user_name'] = $user->prenom . ' ' . $user->nom;
            
            header('Location: ' . $_SERVER['PHP_SELF']); 
            exit();
        } else {
            $erreur = "Email ou mot de passe incorrect.";
        }
    } catch (Exception $e) {
        die("Erreur : " . $e->getMessage());
    }
}
?>

<?php if (isset($_SESSION['mel'])): ?>
    
    <p>Bienvenue, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong> (Connecté)</p>
    <a href="logout.php">Se déconnecter</a>

<?php else: ?>
    <h2>Connexion</h2>
    <?php if(isset($erreur)) echo "<p style='color:red'>$erreur</p>"; ?>
    
    <form action="" method="POST">
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="mdp">Mot de passe:</label>
            <input type="password" id="pass" name="pass" required>
        </div>
        <button type="submit" name="login_submit">Log In</button>
    </form>
<?php endif; ?>