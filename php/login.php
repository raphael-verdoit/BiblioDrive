<?php
// Always start the session at the very beginning of the script.
session_start();

// Include your database connection file.
require_once 'db.php'; // Assumes db.php creates a PDO object named $connexion

// Check if the form was submitted using the POST method.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Get the submitted data (and trim whitespace)
    $email = trim($_POST['email']);
    $password = $_POST['mdp']; // Don't hash the password on login!

    // 2. Find the user in the database by their email.
    // Use the correct column name 'mel'.
    $stmt = $connexion->prepare('SELECT * FROM utilisateur WHERE mel = :email');
    $stmt->execute([':email' => $email]);

    // 3. Fetch the user record.
    $user_record = $stmt->fetch(PDO::FETCH_ASSOC);

    // 4. Verify the user exists AND the password is correct.
    // password_verify() compares the plain-text password from the form
    // with the hashed password from the database ('motdepasse').
    if ($user_record && password_verify($password, $user_record['motdepasse'])) {

        // --- SUCCESSFUL LOGIN ---

        // 5. Regenerate the session ID to prevent session fixation attacks.
        session_regenerate_id(true);

        // 6. Store essential, non-sensitive user data in the session.
        // Storing the user's unique identifier is best practice.
        $_SESSION['user_email'] = $user_record['mel'];
        $_SESSION['user_name'] = $user_record['prenom']; // Or 'nom', as you prefer.

        // 7. Redirect to the protected dashboard.
        header('Location: dashboard.php');
        exit(); // Always call exit() after a header redirect.

    } else {
        // --- FAILED LOGIN ---
        $error_message = 'Invalid email or password.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>

    <?php
    // Display an error message if login failed.
    if (isset($error_message)) {
        echo '<p style="color: red;">' . htmlspecialchars($error_message) . '</p>';
    }
    ?>

    <form action="" method="POST">
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="mdp">Password:</label>
            <input type="password" id="mdp" name="mdp" required>
        </div>
        <div>
            <button type="submit">Log In</button>
        </div>
    </form>

</body>
</html>
