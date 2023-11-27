<?php
session_name("admin_session");
session_start();

$_SESSION = array();

session_destroy();

header("Location: admin_login_signup.php");
exit();
?>
