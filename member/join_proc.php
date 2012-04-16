<?php

session_start();

$re_url = ( trim($_POST["re_url"]) ) ? trim($_POST["re_url"]) : trim($_GET["re_url"]);
if ( $re_url == "" ) $re_url = "/";

require_once('../lib/class.dbConnect.php');
require_once('../lib/class.members.php');


$DB = new dbConn();
$Member = new clsMembers( $DB->getConnection() );

$policy_agree = ( $_POST['policyAgree'] == 'y' )?$_POST['policyAgree']:'n';

if( trim($_POST['joinEmail']) == "" || trim($_POST['joinPasswd']) == "" ) {
    $DB->historyBackNoMsg();
    return;
}

$arr = array(
	"email"=>trim($_POST['joinEmail']), 
	"passwd"=>trim($_POST['joinPasswd']), 
	"policy_agree"=>trim($policy_agree)
);
$result = $Member->joinMember( $arr );

if( $result['r'] == 'success' )
{
	$_SESSION['USER_IDX'] = $result['idx'];
	$_SESSION['USER_TYPE'] = "letscc";
	$_SESSION['USER_ID'] = $_POST['joinEmail'];
	$_SESSION['USER_AGREE'] = $result['policy_agree'];
}

header('Location: '.$re_url);
