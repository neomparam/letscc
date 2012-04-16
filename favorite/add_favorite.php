<?php
session_start();

require_once('../lib/class.dbConnect.php');
require_once('../lib/class.contents.php');
require_once('../lib/class.favorites.php');

$DB = new dbConn();
$Content = new clsContents( $DB->getConnection() );
$Favorite = new clsFavorites( $DB->getConnection() );

$result = array();

if( $_SESSION['USER_IDX'] == "" ) {
	$result = array("r"=>"error","msg"=>"Available after sign in.");
	echo json_encode($result);
	return;
}

if( trim($_POST['cidx']) == "" ) {

	$arr = array(
		"c_id"=>trim($_POST['id']),
		"c_type"=>trim($_POST['c_type']),
		"s_name"=>trim($_POST['s_name']),
		"c_info"=>trim($_POST['info'])
	);

	$result = $Content->save( $arr );

	if( $result['r'] == "success" || $result['r'] == "exist") {
		$c_idx = $result['idx'];
	}

} else {
	$c_idx = trim($_POST['cidx']);
}

$arr = array(
	"m_idx"=>$_SESSION['USER_IDX'],
	"c_idx"=>$c_idx,
	"search_word"=>trim($_POST['keyword']),
	"tags"=>trim($_POST['tags'])
);

$result = $Favorite->save( $arr );

if( $result['r'] == "success" )
	$result['f_cnt'] = $Content->incrementFavorite($result['c_idx']);

echo json_encode($result);
?>