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
	"c"=>($_POST['c'])?'y':'n',
	"d"=>($_POST['d'])?'y':'n'
);

$result = $Content->save( $arr );

echo json_encode($result);
?>