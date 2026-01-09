<?php


if (!isset($_SESSION['mel'])) {
    header('Location: login.php');
    exit();
}

$timeout_duration = 1800; 
if (isset($_SESSION['last_activity']) && 
    (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}

$_SESSION['last_activity'] = time();

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
