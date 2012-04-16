<?php

require_once('../lib/class.dbConnect.php');
require_once('../lib/class.contents.php');
require_once('../lib/class.favorites.php');

$DB = new dbConn();
$conn = $DB->getConnection();

$Content = new clsContents( $conn );
$Favorite = new clsFavorites( $conn );

$curPage = trim($_POST['cur_page']);
$pageLen = trim($_POST['page_cnt'])?trim($_POST['page_cnt']):0;
$c_type = trim($_POST['type']);

if( $c_type == "" ) exit;

$totalcount = $Content->getFavoriteContentsCount($c_type,'y');
$result = array("total"=>$totalcount);

$start = $curPage * $pageLen;
$data = $Content->getFavoriteContents($c_type,$start,$pageLen,'y');

for( $i=0; $i < count($data); $i++ ) {
	$info = json_decode($data[$i]['c_info']);
	$info->idx = $data[$i]['idx'];
	$info->s_name = $data[$i]['s_name'];
	$info->f_count = $data[$i]['f_count'];
	$result['data'][] = $info;
}

echo json_encode($result);
?>