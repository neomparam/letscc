<?php	
	session_start();

	require_once('lib/config.php');
	require_once('lib/class.dbConnect.php');
	require_once('lib/class.members.php');

	$DB = new dbConn();
	$Member = new clsMembers( $DB->getConnection() );

	if( isset($_COOKIE['userinfo']) || $_COOKIE['userinfo'] != "" ) {
		if( !isset($_SESSION['USER_IDX']) || $_SESSION['USER_IDX'] == "" ) {

			if( $memberData = $Member->getDataFromAutoKey($_COOKIE['userinfo']) ) {
				$_SESSION['USER_IDX'] = $memberData->idx;
				$_SESSION['USER_TYPE'] = $memberData->type;
				$_SESSION['USER_ID'] = $memberData['email'];
				$_SESSION['USER_NAME'] = $memberData['email'];
				$_SESSION['USER_AGREE'] = $memberData['policy_agree'];

				setcookie("userinfo", $key , time()+60*60*24*30, "/",".mistyhand.com");
			}
		}
	}
?>
