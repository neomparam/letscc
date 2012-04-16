<?php
/**
 * @file
 * Clears PHP sessions and redirects to the connect page.
 */
 
/* Load and clear sessions */
session_start();

$return_url = ($_SESSION['return_url'])?$_SESSION['return_url']:"/index.php";

session_destroy();
 
/* Redirect to page with the connect to Twitter option. */
header('Location: '.$return_url);
?>
