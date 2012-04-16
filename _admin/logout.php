<?
	session_start();

	unset($_SESSION['ADMIN_ID']);

	session_unset();
	session_destroy();

	header('Location: /_admin/index.php');
?>