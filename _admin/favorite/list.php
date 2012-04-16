<? 
	$TopMenu = "favorite";
	require_once("../inc/header.php"); 

	$s_name = trim($_GET['s_name']);
	$c_type = trim($_GET['c_type']);

	require_once('../../lib/class.dbConnect.php');
	require_once("../../lib/class.paging.php");

	$DB = new dbConn();
	$table = "contents";
	$keyfield = $_POST['keyfield'] ? $_POST['keyfield'] : $_GET['keyfield'];
	$keyword = $_POST['keyword'] ? $_POST['keyword'] : $_GET['keyword'];
	$nowPage = $_POST['nowPage'] ? $_POST['nowPage'] : $_GET['nowPage'];
	$BlockSize = $_POST['BlockSize'] ? $_POST['BlockSize'] : $_GET['BlockSize'];
	$PageSize = $_POST['PageSize'] ? $_POST['PageSize'] : $_GET['PageSize'];

	if($nowPage == "") $nowPage = 1;
	if($BlockSize == "") $BlockSize = 10;
	if($PageSize == "") $PageSize = 10;

	$where_temp = " where f_count > 0";

	if( $s_name )
		$where_temp .= " and s_name = '".$s_name."'";
	if( $c_type )
		$where_temp .= " and c_type = '".$c_type."'";

	if( $keyfield != "" ) {
		$where_temp .= " and $keyfield LIKE '%$keyword%'";
	}

	$re = $DB->dbQuery("select idx from ".$table." $where_temp");
	$totalRecord = $re[cnt];

	$arr = array("keyfield"=>$keyfield,"keyword"=>$keyword); 
	$PAGE = new pageSet($nowPage, $BlockSize,$PageSize, $totalRecord,$arr);
	
	$where_temp .= " order by f_count desc";
	$where_temp .= $PAGE->getLimitQuery();

	$re = $DB->dbSelect($table,$where_temp);
?>

<style type="text/css">
	.info-idx { display:none; }
</style>

<script type="text/javascript">
//<!CDATA[
$(function () {
	$('.btn-enable').click( function() {
		var idx = $(this).siblings('.info-idx').text();
		var status = $(this).text();
		var enable = 'n';

		if( status == "disable" ) { 
			enable = 'y';
		}

		$.ajax({
			url:'proc.php', dataType:'json',type:'post', 
			data:{ 'idx':idx, 'enable':enable },
			success:function(data) {
				if( data.r == "success" ) {
					if( status == "disable" ) {
						$(this).removeClass("btn-red");
						$(this).addClass("btn-black");
						$(this).text('enable');
					} else {
						$(this).removeClass("btn-black");
						$(this).addClass("btn-red");
						$(this).text('disable');
					}
				}
			},
			context:this
		});
	});
});
//]]>
</script>
<div id="contentList">
<table>
<caption>Favorites<?if($c_type) {echo " > ".$c_type;}?><?if($s_name) {echo " > ".$s_name;}?></caption>
	<thead>
	<tr>
		<th>No.</th>
		<th>Service</th>
		<th>Image</th>
		<th>Title</th>
		<th>Registered</th>
		<th>Count</th>
		<th width="70">Action</th>
	</tr>
	</thead>
	<tbody>
	<? 
		for( $i=0; $i <$re['cnt']; $i++ ) {
			$row = mysql_fetch_array($re['result']);
			$number = $totalRecord - $i - ( ($nowPage-1) * $PageSize );
	
			$info = json_decode( stripslashes($row['c_info']) );

			$thumb = "";

			switch( $row['c_type'] ) {
				case "image":
					$title = urldecode($info->title);
					$thumb = $info->tbURL;
					break;
				case "music":
					$title = urldecode($info->name);
					break;
				case "video":
					$title = urldecode($info->title);
					$thumb = $info->thumb;
					break;
				case "doc":
					$title = urldecode($info->title);
					$thumb = $info->thumb;
					break;
			}

			if( $row['f_enable'] == 'n' ) {
				$btn_enable = "<a class='btn-enable btn-red'>disable</a>";
			} else {
				$btn_enable = "<a class='btn-enable btn-black'>enable</a>";
			}
	?>
	<tr>
		<td><?=$number?></td>
		<td><?=$row['s_name']?></td>
		<td><? if( $thumb != "") { ?><img src="<?=$thumb?>" alt="<?=$title?>" /><? } ?></td>
		<td><?=$title?></td>
		<td><?=substr(trim($row["regdate"]), 0, 10)?></td>
		<td><?=$row['f_count']?></td>
		<td><?=$btn_enable?><span class="info-idx"><?=$row['idx']?></span></td>
	</tr>
	<? } ?>
	</tbody>
	<tfoot>
	<tr>
		<td colspan="7" align="center"><?=$PAGE->getPage();?></td>
	</tr>
	</tfoot>
</table>
</div>
<? require_once("../inc/bottom.php"); ?>
