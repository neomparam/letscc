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

	for( $i=0; $i < count($f_result); $i++ ) {
		$c_idx = $f_result[$i]['c_idx'];
		$Content->decrementFavorite( $c_idx );
	}

	$Favorite->delDatasFromMemberIdx($member_idx);

	if( $Member->delMember($member_idx) ) {
		$result['r'] = "success";
		$result['msg'] = "Your member information no longer exists.";
	} else {
		$result['r'] = "error";
		$result['msg'] = "Sorry, we failed to delete your member information.";
	}
}

echo json_encode($result);
?>
