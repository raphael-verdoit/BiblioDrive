<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require "db.php";

if (isset($_POST['login_submit'])){
    $email = $_POST['email'];
    $mdp = $_POST['pass'];

    $sql = 'SELECT * FROM utilisateur WHERE mel = ?';
    
    try {
        $stmt = $connexion->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if ($user && $mdp === $user->motdepasse) {
            // Utiliser 'mel' pour correspondre au test du header.php
            $_SESSION['mel'] = $user->mel; 
            $_SESSION['user_name']  = $user->prenom . ' ' . $user->nom;

            header('Location: dashboard.php');
            exit();
        } else {
            echo "Email ou mot de passe incorrect.";
        }
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<?php if(isset($_SESSION['mel'])): ?>
    <p>Utilisateur connecter</p>
<?php else: ?>
    <form action="./php/login.php" method="POST">
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="mdp">Password:</label>
            <input type="password" id="pass" name="pass" required>
        </div>
        <div>
            <button type="submit" id="login_submit" name="login_submit">Log In</button>
        </div>
    </form>
<?php endif; ?>