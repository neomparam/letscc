<?php

session_start();

require_once('../../../../lib/oauth/facebook/facebook.php');
require_once('../../../../lib/config.php');

require_once('../../../../lib/class.dbConnect.php');
require_once('../../../../lib/class.members.php');
require_once('../../../../lib/class.favorites.php');

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
	'appId'  => FACEBOOK_APPID,
	'secret' => FACEBOOK_SECRET,
));

$return_url = ($_SESSION['return_url']!="")?$_SESSION['return_url']:"/index.php";

$user = $facebook->getUser();

if ( $user ) {
	try {
		// Proceed knowing you have a logged in user who's authenticated.
		$user_profile = $facebook->api('/me');
	} catch (FacebookApiException $e) {
		error_log($e);
		$user = null;
	}

	$DB = new dbConn();
	$Member = new clsMembers( $DB->getConnection() );

	$oauth_type = "facebook";

	//세션 저장
	$result = $Member->getOauthMemberIdx( $oauth_type, $user );

	if( $result['r'] == 'success' )
	{
		$_SESSION['USER_IDX'] = $result['idx'];
		$_SESSION['USER_TYPE'] = $oauth_type;
		$_SESSION['USER_ID'] = $user;
		$_SESSION['USER_NAME'] = $user_profile['name'];
		$_SESSION['USER_IMAGE'] = "https://graph.facebook.com/".$user."/picture";
		$_SESSION['USER_AGREE'] = $result['policy_agree'];

		//my favorite 정보가 있다면 저장 후 my favorite 페이지로 이동
		$c_idx ="";
		$keyword ="";

		if( !isset($_SESSION['favorite_cidx']) || $_SESSION['favorite_cidx'] != "" ) {
			$c_idx = $_SESSION['favorite_cidx'];
			$keyword = $_SESSION['favorite_keyword'];
			$_SESSION['favorite_cidx'] = ""; unset($_SESSION['favorite_cidx']);
			$_SESSION['favorite_keyword'] = ""; unset($_SESSION['favorite_keyword']);
		}
	} else {
		header('Location: ./clearsessions.php');
	}

	//콘텐츠 IDX 가 넘어오면 즐겨찾기에 추가 후 My즐겨찾기 페이지로 이동
	if( $c_idx != "" ) {
		$Favorite = new clsFavorites( $DB->getConnection() );

		$arr = array(
			"m_idx"=>$_SESSION['USER_IDX'],
			"c_idx"=>$c_idx,
			"search_word"=>$keyword,
			"tags"=>""
		);

		$f_result = $Favorite->save( $arr );
		$f_idx = $f_result['idx']; //데이터가 있으면 callback 페이지에서 my즐겨찾기 페이지로 이동.

		//회원가입에 동의 하지 않았다면 동의 페이지로 이동한다.
		if( $_SESSION['USER_AGREE'] == "n" ) {
			header('Location: /member/join_confirm.php?re_url=/my_favorite.php?f_idx='.$f_idx );
		} else {
			header('Location: /my_favorite.php?f_idx='.$f_idx);
		}
	} else {
		//회원가입에 동의 하지 않았다면 동의 페이지로 이동한다.
		if( $_SESSION['USER_AGREE'] == "n" ) {
			header('Location: /member/join_confirm.php?re_url='.$return_url);
		} else {
			header('Location: '.$return_url);
		}
	}

} else {
	/* Save HTTP status for error dialog on connnect page.*/
	header('Location: ./clearsessions.php');
}
?>