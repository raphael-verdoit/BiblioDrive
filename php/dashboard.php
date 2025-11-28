<?php
// Start the session to access session variables.
session_start();

// Check if the user is logged in by verifying the session variable exists.
if (!isset($_SESSION['user_email'])) {
    // If not logged in, redirect to the login page.
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
    <h1>Dashboard</h1>

    <p>Welcome, <strong><?php echo htmlspecialchars($user_name); ?></strong>!</p>
    <p>Your email: <?php echo htmlspecialchars($user_email); ?></p>

    <p>This is a protected page. Only logged-in users can see this content.</p>

    <a href="logout.php">Logout</a>

</body>
</html>
