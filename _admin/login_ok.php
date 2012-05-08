<?
	session_start();
	include "../lib/config.php";

	$id = trim($_POST['loginId']);
	$passwd = trim($_POST['loginPasswd']);

	if( $id == ADMIN_ID && $passwd == ADMIN_PASSWD ) {
		$_SESSION['ADMIN_ID'] = ADMIN_ID;
		header('Location: /_admin/index.php');
	} else {
		echo ("
			<script>
				alert('The admin information is incorrect.');
				history.go(-1);
			</script>
		");
	}

	
?>
