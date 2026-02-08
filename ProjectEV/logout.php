<?php
session_start(); // Start the session

// Destroy all session variables
$_SESSION = array();

// If you want to delete the session cookie as well, do this:
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}

// Finally, destroy the session itself
session_destroy();

// Redirect to the homepage
header("Location: index.html"); // Change '/' if the homepage URL is different
exit;
?>
