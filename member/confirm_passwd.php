<?php
session_start();

require_once('../lib/class.dbConnect.php');
require_once('../lib/class.members.php');


$DB = new dbConn();
$Member = new clsMembers( $DB->getConnection() );

if( $Member->confirmPasswd( trim($_POST['email']), trim($_POST['currentPasswd']) ) === true )
	echo "true";
else
	echo "false";
?>