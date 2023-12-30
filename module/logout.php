<?php
session_start();

// Biar yakquen kedestroy
$_SESSION = [];
session_unset();

session_destroy();

header("Location: login.php");
exit;
