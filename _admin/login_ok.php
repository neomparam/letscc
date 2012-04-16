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
				alert('관리자 정보가 일치하지 않습니다.');
				history.go(-1);
			</script>
		");
	}

	
?>
