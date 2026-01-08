<?php

session_start();

// Vérifier 'mel' au lieu de 'user_email' pour être cohérent
if (!isset($_SESSION['mel'])) {
    header('Location: login.php');
    exit();
}

// Optional: Implement session timeout (30 minutes of inactivity)
$timeout_duration = 1800; // 30 minutes in seconds

if (isset($_SESSION['last_activity']) && 
    (time() - $_SESSION['last_activity']) > $timeout_duration) {
    // Session expired due to inactivity.
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}

// Update the last activity timestamp.
$_SESSION['last_activity'] = time();

// User is authenticated; retrieve their information from the session.
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <?php require 'header.php'?>
    <h1>Dashboard</h1>

    <p>Welcome, <strong><?php echo htmlspecialchars($user_name); ?></strong>!</p>
    <p>Your email: <?php echo htmlspecialchars($user_email); ?></p>

    <p>This is a protected page. Only logged-in users can see this content.</p>

    <a href="../accueuil.php">Go to the menu</a>

</body>
</html>
