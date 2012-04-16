<?php

require_once('../lib/class.dbConnect.php');
require_once('../lib/class.contents.php');

$DB = new dbConn();
$Content = new clsContents( $DB->getConnection() );

$arr = array(
	"c_id"=>trim($_POST['id']),
	"c_type"=>trim($_POST['c_type']),
	"s_name"=>trim($_POST['s_name']),
	"c_info"=>trim($_POST['info']),
	"k"=>trim($_POST['keyword']),
	"c"=>trim($_POST['c']),
	"d"=>trim($_POST['d'])
);

$result = $Content->save( $arr );

header('Location: /detail.php?idx='.$result['idx']."&k=".$_POST['keyword']);
?>