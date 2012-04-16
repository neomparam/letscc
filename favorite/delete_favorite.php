<?php
session_start();

require_once('../lib/class.dbConnect.php');
require_once('../lib/class.contents.php');
require_once('../lib/class.favorites.php');

$DB = new dbConn();
$Content = new clsContents( $DB->getConnection() );
$Favorite = new clsFavorites( $DB->getConnection() );

$idx = trim($_POST['idx']);
$m_idx = $_SESSION['USER_IDX'];

$result = $Favorite->delData( $idx, $m_idx );

if( $result['r'] == "success" )
	$result['f_cnt'] = $Content->decrementFavorite($result['c_idx']);

echo json_encode($result);
?>