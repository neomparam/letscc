<?php
session_start();

$_SESSION['return_url'] = $_SERVER['HTTP_REFERER'];

require_once('../lib/class.dbConnect.php');
require_once('../lib/class.members.php');


$DB = new dbConn();
$Member = new clsMembers( $DB->getConnection() );

if( trim($_POST['email']) == "" ) {
    $DB->historyBackNoMsg();
    return;
}

$arr = array(
	"email"=>trim($_POST['email'])
);

$result = $Member->changePasswd( $arr );

if( $result['r'] == 'success' )
{
	$subject = "LetsCC password guide.";
	$content = "<div style='width:80%; height:300px; border:1px solid black;text-align:center; margin:0 auto; overflow:hidden; border:1px solid red;>";
	$content = "<div style='width:80%; margin-top:150px; background-color:#eee;' >Your temporary password is : [".$result['passwd']."] </div>";
	$content .= "</div>";
	if( $Member->sendMail( $arr['email'], $subject, $content ) ) {
		$result['msg'] = "We sent your temporary password to your mail account.";
		$result['passwd'] = "";
	} else {
		$result['r'] = "error";
		$result['msg'] = "Error : Sorry, mail sending is failed.";
		$result['passwd'] = "";
	}
	
}

echo json_encode($result);
?>