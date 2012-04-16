<?php

session_start();

require_once('../lib/class.dbConnect.php');
require_once('../lib/class.members.php');


$DB = new dbConn();
$Member = new clsMembers( $DB->getConnection() );

$user_idx = trim($_POST['user_idx']);
$policy_agree = ( trim($_POST['policyAgreeConfirm']) == 'y' )?trim($_POST['policyAgreeConfirm']):'n';

if( trim($user_idx == "" ) {
    $DB->historyBackNoMsg();
    return;
}

$result = $Member->updatePolicyAgree( $user_idx, $policy_agree );

if( $result )
{
	$_SESSION['USER_AGREE'] = $result;
}

$re_url = ( trim($_POST["re_url"]) ) ? trim($_POST["re_url"]) : trim($_GET["re_url"]);
if ( $re_url == "" ) $re_url = "/";

header('Location: '.$re_url);
?>