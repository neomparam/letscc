<?php
session_start();
session_destroy();

$_COOKIE['userinfo'] = "";
unset($_COOKIE['userinfo']);
setcookie("userinfo","", time()+60*60*24*30, "/",".mistyhand.com");

header('Location: /index.php');
?>