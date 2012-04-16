<?php

session_start();
$_SESSION['return_url'] = $_SERVER['HTTP_REFERER'];

require_once('../lib/class.dbConnect.php');
require_once('../lib/class.members.php');

$DB = new dbConn();
$Member = new clsMembers( $DB->getConnection() );

if( trim($_POST['joinEmail']) )
{
	if( $Member->existEmail( trim($_POST['joinEmail']) ) === false )
		echo "true";
	else
		echo "false";
} else if( $_POST['loginEmail'] ) {
	if( $Member->existEmail( trim($_POST['loginEmail']) ) === false )
		echo "false";
	else
		echo "true";
} else if( $_POST['passwdEmail'] ) {
	if( $Member->existEmail( trim($_POST['passwdEmail']) ) === false )
		echo "false";
	else
		echo "true";
}
