<?php
require_once('../lib/class.dbConnect.php');
require_once('../lib/class.contents.php');
require_once('../lib/class.favorites.php');

$DB = new dbConn();
$conn = $DB->getConnection();

$Content = new clsContents( $conn );
$Favorite = new clsFavorites( $conn );

$keyword = trim($_POST['keyword'])?trim($_POST['keyword']):'';
$curPage = trim($_POST['cur_page'])?trim($_POST['page_cnt']):0;
$pageLen = trim($_POST['page_cnt'])?trim($_POST['page_cnt']):0;
$c_type = trim($_POST['type']);
$c = trim($_POST['c']);
$d = trim($_POST['d']);

if( $c_type == "" ) exit;

$totalcount = $Content->getSearchContentsCount($c_type,$keyword,$c,$d,'y');
$result = array("total"=>$totalcount);

$start = $curPage * $pageLen;
$data = $Content->getSearchContents($c_type,$start,$pageLen,$keyword,$c,$d,'y');

for( $i=0; $i < count($data); $i++ ) {
	$info = json_decode($data[$i]['c_info']);
	$info->idx = $data[$i]['idx'];
	$info->s_name = $data[$i]['s_name'];
	$info->f_count = $data[$i]['f_count'];
	$result['data'][] = $info;
}

echo json_encode($result);
?>