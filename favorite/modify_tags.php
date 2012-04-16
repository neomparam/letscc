<?php
session_start();

require_once('../lib/class.dbConnect.php');
require_once('../lib/class.contents.php');
require_once('../lib/class.favorites.php');

$DB = new dbConn();
$Favorite = new clsFavorites( $DB->getConnection() );

$idx = $_POST['idx'];
$m_idx = $_SESSION['USER_IDX'];

$arr = array(
	"tags"=>trim($_POST['tags'])
);

$result = $Favorite->modify($idx, $m_idx, $arr );

echo json_encode($result);
?>