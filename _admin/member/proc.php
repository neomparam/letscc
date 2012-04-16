<?php
session_start();

require_once('../../lib/class.dbConnect.php');
require_once('../../lib/class.members.php');
require_once('../../lib/class.favorites.php');
require_once('../../lib/class.contents.php');

$DB = new dbConn();
$Member = new clsMembers( $DB->getConnection() );
$Favorite = new clsFavorites( $DB->getConnection() );
$Content = new clsContents( $DB->getConnection() );

$type = trim($_POST['type']);
$member_idx = trim($_POST['idx']);
$result = array();

if( $type == "del" ) {
	$f_result = $Favorite->getDatasFromMemberIdx($member_idx);

	//즐겨찾기 횟수 감소
	for( $i=0; $i < count($f_result); $i++ ) {
		$c_idx = $f_result[$i]['c_idx'];
		$Content->decrementFavorite( $c_idx );
	}

	//회원의 즐겨찾기 목록 삭제
	$Favorite->delDatasFromMemberIdx($member_idx);

	//회원 정보
	if( $Member->delMember($member_idx) ) {
		$result['r'] = "success";
		$result['msg'] = "회원정보를 삭제 하였습니다.";
	} else {
		$result['r'] = "error";
		$result['msg'] = "회원정보 삭제에 실패하였습니다.";
	}
}

echo json_encode($result);

//$return_url = ($_SESSION['return_url'])?$_SESSION['return_url']:"/index.php";
//header('Location: '.$return_url);
?>