<!--[if IE 6]>
	<style thype="text/css">
	.fixed {right:-8px;}
	</style>
<![endif]-->
<!--[if IE 7]>
	<style thype="text/css">
	#searchResult {padding-top:56px;}
	</style>
<![endif]-->

<script type="text/javascript">
//<![CDATA[
$(function(){
	$('#searchForm').submit(function() {
		if ( $('#keyword').val() == '' ) 
		{
			location.href = '/favorite.php';
			return false;
		}
	});	
	$('#member li').hover(function(e) {
		$(this).addClass('over');
	}, function(e) {
		$(this).removeClass('over');
	});
})	
//]]>
</script>
	<div id="header">
		<div class="top">
			<h1 class="logo"><a href="/">Let's CC</a></h1>
			<div id="member">
				<h2>Member</h2>
				<? if( $_SESSION['USER_IDX'] == "" ) { ?>
				<? } else { ?>
					<? if( $_SESSION['USER_TYPE'] == "letscc" ) { ?>
					<div class="eng02"><a href="http://www.letscc.net" onfocus="blur()"><img src="i/bt_kor.gif" border="0"></a></div>
					<? } else { ?>				
					<div class="eng03"><a href="http://www.letscc.net" onfocus="blur()"><img src="i/bt_kor.gif" border="0"></a></div>
					<? } ?>
				<? } ?>
				<ul>
				<? if( $_SESSION['USER_IDX'] == "" ) { ?>
					<li><a href="member/login.php" id="btnMemberLogin">sign in</a></li>
					<li><a href="member/join.php" id="btnMemberJoin">sign up</a></li>
				<? } else { ?>
					<li class="favorite"><a href="my_favorite.php">My favorites</a></li>
					<li>
						<? if( $_SESSION['USER_TYPE'] == "letscc" ) { ?>
							<a href="member/modify_myinfo.php" id="btnMypage">My Info.</a>
						<? } ?>
					</li>
					<li>
						<a href="member/logout.php">
						<? if( $_SESSION['USER_TYPE'] == "twitter" ) { ?>
							<img src="/i/icon_twitter.gif" alt="twitter icon" title="twitter icon" />
						<? } else if( $_SESSION['USER_TYPE'] == "facebook" ) { ?>
							<img src="/i/icon_facebook.gif" alt="facebook icon" title="facebook icon" />
						<? } ?>sign out</a>
					</li>
				<? } ?>
				</ul>
			</div>
		</div>
		<form id="searchForm" action="/">
			<fieldset>
				<legend>CC Search</legend>
				<div class="search-input">
					<select name="t" id="contentType">
						<option value="all" <?=$all_selected?>>Everything</option>
						<option value="image" <?=$image_selected?>>Images</option>
						<option value="music" <?=$music_selected?>>Audio</option>
						<option value="video" <?=$video_selected?>>Videos</option>
						<option value="doc" <?=$doc_selected?>>Docs</option>
					</select>
					<input type="text" name="k" id="keyword" value="<?=htmlspecialchars(stripslashes($keyword))?>" />
					<input type="submit" id="searchSubmit" value="Search" />
				</div>
				<ul class="cc-options">
					<li><input id="comm" name="c" type="checkbox" value="1" <?=$comm_checked?>/> 
					  <label for="comm">Find content to use commercially</label>
					</li>
					<li><input id="deriv" name="d" type="checkbox" value="1" <?=$deriv_checked?>/>
				    <label for="comm">Find content to modify, adapt or build upon</label></li>
				</ul></fieldset>
		</form>
	</div>
