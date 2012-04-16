<style type="text/css">
#leftMenu ul li a{ color:#fff; font-weight:bold; display:block; width:88px; height:30px; background-color:#000; text-align:center; line-height:30px; margin:5px;}

</style>

<?	if( $TopMenu == "member" ) { ?>
<ul>
	<li><a href="list.php?pgkey=list">List</a></li>
</ul>
<? } else if( $TopMenu == "favorite" ) { ?>
<?
	require_once( '../../lib/class.dbConnect.php' );
	require_once( '../../lib/class.contents.php' );

	$DB = new dbConn();
	$Content = new clsContents( $DB->getConnection() );
	$arrServerList = $Content->getServerList();
?>
<ul>
	<? 
		$type_temp = "";
		for( $i=0; $i < count($arrServerList); $i++ ) { 
			if( $type_temp == $arrServerList[$i]['c_type'] ) continue;
			$type_temp = $arrServerList[$i]['c_type'];

	?>
	<li><a href="list.php?c_type=<?=$arrServerList[$i]['c_type']?>" ><?=$arrServerList[$i]['c_type']?></a></li>
	<? } ?>
<? } ?>
