<?
	session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title>Let's CC</title>

<style type="text/css">
body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,form,fieldset,p,button{margin:0;padding:0;}
body,h1,h2,h3,h4,th,td,input{color:#333;font-family:"돋움",dotum,sans-serif;font-size:13px;font-weight:normal;}
hr{display:none;}
img,fieldset{border:0;}
ul,ol,li{list-style:none;}
img,input,select,textarea{vertical-align:middle;}
a{color:#30323f;text-decoration:none;}
a:hover{color:#4559E9;text-decoration:underline;}
legend {display:none;}

body {text-align:center;}

#wrap {width:950px; text-align:left; margin:0 auto; }
#header { height:60px; position:relative; background-color:#ccc; }
#header h1 { position:absolute; font-size:20px; font-weight:bold; }
	#gnb { width:950px; height:20px; padding-top:20px; text-align:right; }
	#gnb ul li { float:right; padding-right:5px; }
	#gnb ul li a{ color:#fff; font-weight:bold;}
#body { width:950px; position:relative; border:1px solid green; overflow:hidden; }
</style>

<script type="text/javascript" src="../j/jquery-1.6.2.min.js"></script>

</head>
<body>
<div id="wrap" >
	<div id="header">
		<h1>Let's CC Administration</h1>
		<div id="gnb">
			<ul>
				<? if( $_SESSION['ADMIN_ID'] != "" ) { ?>
				<li><a href="/_admin/logout.php">Log out</a></li>
				<? } ?>
			</ul>
		</div>
	</div>
	<div id="body">
<?
	if( !isset($_SESSION['ADMIN_ID']) || $_SESSION['ADMIN_ID'] == "" )
		require_once("login.php");
	else 
		echo "<script>location.href='member/list.php'</script>";
?>
	</div>
<? require_once("inc/bottom.php"); ?>
