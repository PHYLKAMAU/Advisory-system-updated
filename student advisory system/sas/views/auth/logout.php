<?php
include_once __DIR__ . "/../shared/header.php";
include_once __DIR__ . "/../shared/nav.php";

session_destroy();
redirect(base_url() . "/views/auth/login.php");
?>
