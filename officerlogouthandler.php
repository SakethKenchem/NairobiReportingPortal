<?php
session_name("officer_session");
session_start();
session_destroy();
header("Location: officerlogin.html");
?>