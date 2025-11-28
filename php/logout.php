<?php
// Start the session so we can destroy it.
session_start();

// Clear all session variables.
$_SESSION = array();

// If a session cookie exists, delete it.
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Destroy the session completely.
session_destroy();

// Redirect to the login page.
header('Location: login.php');
exit();
?>
