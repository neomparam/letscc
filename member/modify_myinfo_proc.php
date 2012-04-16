<?php
session_start();

$re_url = ( trim($_POST["re_url"]) ) ? trim($_POST["re_url"]) : trim($_GET["re_url"]);
if ( $re_url == "" ) $re_url = "/";

$_SESSION['return_url'] = $re_url;

require_once('../lib/class.dbConnect.php');
require_once('../lib/class.members.php');


$DB = new dbConn();
$Member = new clsMembers( $DB->getConnection() );

if( trim($_POST['email']) == "" || trim($_POST['passwd']) == "" ) {
    $DB->historyBackNoMsg();
    return;
}

$arr = array(
	"email"=>trim($_POST['email']),
	"passwd"=>trim($_POST['passwd']),
);

$result = $Member->changePasswd( $arr );

echo json_encode($result);
?>