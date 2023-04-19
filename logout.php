<?php
session_start();

// unset all session variables
$_SESSION = array();

// destroy session
session_destroy();

// redirect to login page
header("Location: home.php");
exit();
?>