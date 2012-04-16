<?php
session_start();

require_once('../lib/class.dbConnect.php');
require_once('../lib/class.contents.php');
require_once('../lib/class.favorites.php');

$DB = new dbConn();
$conn = $DB->getConnection();

$Content = new clsContents( $conn );
$Favorite = new clsFavorites( $conn );

$memberIdx = $_SESSION['USER_IDX'];
$keyword = trim($_POST['keyword'])?trim($_POST['keyword']):'';
$curPage = trim($_POST['cur_page']);
$pageLen = trim($_POST['page_cnt'])?trim($_POST['page_cnt']):0;
$c_type = trim($_POST['type']);

if ($c_type == "" ) exit;

$totalcount = $Content->getMyFavoriteContentsCount( $c_type, $memberIdx, $keyword);
$result = array("total"=>$totalcount);

$start = ($curPage) * $pageLen;
$data = $Content->getMyFavoriteContents( $c_type, $memberIdx, $keyword, $start, $pageLen);

for( $i=0; $i < count($data); $i++ ) {
	$info = json_decode($data[$i]['c_info']);
	$info->idx = $data[$i]['f_idx'];
	$info->tags = $data[$i]['tags'];
	$info->s_name = $data[$i]['s_name'];
	$info->search_word = $data[$i]['search_word'];
	$result['data'][] = $info;
}

echo json_encode($result);
?>