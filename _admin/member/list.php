<? 
	$TopMenu = "member";

	require_once("../inc/header.php"); 
	require_once('../../lib/class.dbConnect.php');
	require_once("../../lib/class.paging.php");

	$DB = new dbConn();
	
	$table = "members";
	$keyfield = $_POST['keyfield'] ? $_POST['keyfield'] : $_GET['keyfield'];
	$keyword = $_POST['keyword'] ? $_POST['keyword'] : $_GET['keyword'];
	$nowPage = $_POST['nowPage'] ? $_POST['nowPage'] : $_GET['nowPage'];
	$BlockSize = $_POST['BlockSize'] ? $_POST['BlockSize'] : $_GET['BlockSize'];
	$PageSize = $_POST['PageSize'] ? $_POST['PageSize'] : $_GET['PageSize'];

	if($nowPage == "") $nowPage = 1;
	if($BlockSize == "") $BlockSize = 10;
	if($PageSize == "") $PageSize = 30;

	$where_temp = " where 1 ";

	if( $keyfield != "" ) {
		if( $keyfield == "email" )
			$where_temp .= " and email LIKE '%$keyword%'";
	}

	$re = $DB->dbQuery("select idx from ".$table." $where_temp");
	$totalRecord = $re[cnt];

	$arr = array("keyfield"=>$keyfield,"keyword"=>$keyword); 
	$PAGE = new pageSet($nowPage, $BlockSize,$PageSize, $totalRecord,$arr);

	$where_temp .= " order by idx desc";
	$where_temp .= $PAGE->getLimitQuery();

	$re = $DB->dbSelect($table,$where_temp);
?>

<style type="text/css">
	.info-idx { display:none; }
</style>

<script type="text/javascript">
//<!CDATA[
$(function () {
	$('.btn-del').click( function() {
		var idx = $(this).siblings('.info-idx').text();

		if( confirm("All your member information is about to be deleted and impossible to restore.\n\nAre you sure to quit?") ) {
			$.ajax({
				url:'proc.php', dataType:'json',type:'post', 
				data:{
					type:'del',
					idx:idx
				},
				success:function(data) {
					if( data.r == "success" )
						location.reload();
				},
				context:this
			});
		}
	});
});
//]]>
</script>
<div id="contentList">
<table>
<caption>Member List</caption>
	<thead>
	<tr>
		<th>No.</th>
		<th>Email</th>
		<th>twitter</th>
		<th>facebook</th>
		<th>Sign Up</th>
		<th>Last Login</th>
		<th>Action</th>
	</tr>
	</thead>
	<tbody>
	<? 
		for( $i=0; $i <$re['cnt']; $i++ ) {
			$row = mysql_fetch_array($re['result']);
			$number = $totalRecord - $i - ( ($nowPage-1) * $PageSize );
	?>
	<tr>
		<td><?=$number?></td>
		<td><?=$row['email']?></td>
		<td><?=$row['twitter_id']?></td>
		<td><?=$row['facebook_id']?></td>
		<td><?=substr(trim($row["joindate"]), 0, 10)?></td>
		<td><?=substr(trim($row["logindate"]), 0, 10)?></td>
		<td><button class="btn-del">Delete</button><span class="info-idx"><?=$row['idx']?></span></td>
	</tr>
	<? } ?>
	</tbody>
	<tfoot>
	<tr>
		<td colspan="8" align="center"><?=$PAGE->getPage();?>	</td>
	</tr>
	<tr>
		<td colspan="8" >
			<form method="post" action="<?=$_SERVER['REQUEST_URI']?>" name="search_form" class="BoardSearch">
			<fieldset>
				<select id="searchSelect" name="keyfield">
					<option value="" <? if($keyfield=="") { echo "selected"; } ?>>Select</option>
					<option value="email" <? if($keyfield=="email") { echo "selected"; } ?>>Email</option>
				</select>
				<input type="text" name="keyword" value="<?=$keyword?>"/>
				<input type="submit" value="Search"/>
			</fieldset>
			</form>
		</td>
	</tr>
	</tfoot>
</table>
</div>
<? require_once("../inc/bottom.php"); ?>
