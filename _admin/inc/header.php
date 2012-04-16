<?
	session_start();

	$_SESSION['useCKUpload'] = false;

	if( !isset($_SESSION['ADMIN_ID']) || $_SESSION['ADMIN_ID'] == "" )
		header("Location:/_admin/index.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
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
a.btn-red, a.btn-black { float:left; margin-left:5px; display:block; width:50px; height:25px; line-height:25px; text-align:center;}
a.btn-red { border:3px solid red; }
a.btn-black { border:3px solid black; }

legend {display:none;}
body {text-align:center;}

#wrap { text-align:left; margin:0 auto; }
#header { height:80px; position:relative; background-color:#ccc; padding-left:5px;  }
#header h1 { position:absolute; font-size:20px; font-weight:bold; }
	#gnb { width:950px; height:20px; padding-top:20px; text-align:right; }
	#gnb ul li { float:right; padding-right:5px; }
	#gnb ul li a{ color:#fff; font-weight:bold;}
	#topMenu ul { padding-left:150px; }
	#topMenu ul li { float:left; padding-right:5px; }
	#topMenu ul li a{ color:#fff; font-weight:bold; display:block; width:100px; height:30px; background-color:#000; text-align:center; line-height:30px; }
#body { position:relative; overflow:hidden; }
#leftMenu { float:left; width:100px; text-align:center; }
#mainContent { float:left; padding-left:10px; margin-top:5px;  border-left:2px solid #ccc;}
#contentList caption { font-size:16px; font-weight:bold; text-align:left; padding:5px; color:blue; border-bottom:2px solid blue; margin-bottom:10px; }
#contentList table { width:800px; border-left:1px solid #000; border-top:1px solid #000;  border-collapse:collapse; }
#contentList th { font-size:12px; font-weight:bold; background-color:#dfdfdf; height:20px; }
#contentList th, #contentList td { border-right:1px solid #000; border-bottom:1px solid #000; text-align:center; }
#contentList table tfoot { height:50px; }
</style>

<script type="text/javascript" src="/j/jquery-1.6.2.min.js"></script>

</head>
<body>
<div id="wrap" >
	<div id="header">
		<h1>Let'sCC Administration</h1>
		<div id="gnb">
			<ul>
				<? if( $_SESSION['ADMIN_ID'] != "" ) { ?>
				<li><a href="/_admin/logout.php">Log out</a></li>
				<? } ?>
			</ul>
		</div>
		<div id="topMenu">
			<ul>
				<li><a href="/_admin/member/list.php">Members</a></li>
				<li><a href="/_admin/favorite/list.php">Favorites</a></li>
			</ul>
		</div>
	</div>
	<div id="body">
		<div id="leftMenu">
		<? require_once("../inc/left.php"); ?>
		</div>
		<div id="mainContent" >