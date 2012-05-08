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
		$result['msg'] = "The post does not exist.";
	} 
	else 
	{
		
		$del_re = $DB->dbUpdate("contents",array("f_enable"=>$enable),"where idx=$idx");

		if( $del_re['cnt'] > 0 )
		{
			$result['r'] = "success";
			$result['msg'] = "Status is successfully modified.";
		}
		else
		{
			$result['r'] = "error";
			$result['msg'] = "Sorry, we failed to delete your post.";
		}
	}
	
	echo json_encode($result);
?>
