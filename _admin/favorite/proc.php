<?
	require_once('../../lib/config.php');
	require_once('../../lib/class.dbConnect.php');

	$DB = new dbConn();
?>

<?
	$idx		= trim($_POST["idx"]);
	$enable = trim($_POST["enable"]);

	$result = array();

	if( $idx == "" )
	{
		$result['r'] = "error";
		$result['msg'] = "해당 게시물이 없습니다.";
	} 
	else 
	{
		
		$del_re = $DB->dbUpdate("contents",array("f_enable"=>$enable),"where idx=$idx");

		if( $del_re['cnt'] > 0 )
		{
			$result['r'] = "success";
			$result['msg'] = "상태를 변경하였습니다.";
		}
		else
		{
			$result['r'] = "error";
			$result['msg'] = "게시물 삭제 실패하였습니다.";
		}
	}
	
	echo json_encode($result);
?>