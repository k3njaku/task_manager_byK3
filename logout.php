<?php
session_start(); // Start the session to access session data

// Clear all session variables and destroy the session
session_unset();
session_destroy();

// Redirect the user to the login page
header('Location: login.php'); 
exit();
?>
