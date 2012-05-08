<?
	require_once('comm/inc.session.php');

	require_once('lib/class.contents.php');
	require_once('lib/class.favorites.php');

	$contentIdx = $_GET['idx'];
	$searchWord = $_GET['k'];

	$Content = new clsContents( $DB->getConnection() );
	$Favorite = new clsFavorites( $DB->getConnection() );

	$contentData = $Content->getData( $contentIdx );

	$contentInfo = json_decode($contentData['c_info']);

	if( $contentData['c_type'] == "image") {
		$dataUrl = $contentInfo->href;
		$imageUrl = $contentInfo->imgURL;
	} else if( $contentData['c_type'] == "music" ) {
		$dataUrl = $contentInfo->url;
	} else if( $contentData['c_type'] == "video" ) {
		$dataUrl = $contentInfo->href;
		//$videoUrl = $contentInfo->url;
		$videoUrl = "http://www.youtube.com/embed/".$contentInfo->id."?wmode=opaque";
	} else if( $contentData['c_type'] == "doc" ) {
		$dataUrl = $contentInfo->href;
	}

	$isFavorite = false;
	if( isset($_SESSION['USER_IDX']) && $_SESSION['USER_IDX'] != "" ) {
		$isFavorite = $Favorite->existFavorite( $_SESSION['USER_IDX'], $contentIdx );
	}
?>

<? require_once 'comm/inc.top.php';?>

<script type="text/javascript">
//<![CDATA[
var DEFAULT_MSG = 'Add tags seperated by commas';
$(function() {
	$('#detailFrm').css('height',$('body').height() - 38 + 'px');
	$(window).bind('resize', function() {
		$('#detailFrm').css('height',$('body').height() - 38 + 'px');
	});
	$('#addFavorite').click(function(e) {
		if (e.target.nodeName != 'A')
        {
                return false;
        }
	});
	$('#favoriteTags').focus(function() {
		if ($('#favoriteTags').val() == DEFAULT_MSG) $('#favoriteTags').val('');
	});
	$('#favoriteTags').blur(function() {
		if ($('#favoriteTags').val() == '') $('#favoriteTags').val(DEFAULT_MSG);
	});
	$('#addFavorite .btn-cancel').click(function() {
		$("#addFavorite").hide();
		$('li.favorite').removeClass('on');
	});
	$('#addFavorite .btn-ok').click( function() {
		if ($('#favoriteTags').val() == DEFAULT_MSG) $('#favoriteTags').val('');
		var l_midx = "<?=$_SESSION['USER_IDX']?>";
		var l_cidx = "<?=$contentIdx?>";
		var l_keyword = "<?=$searchWord?>";
		var l_tags = $('#favoriteTags').val();

		if( l_midx == '' || l_cidx == '' ) {
			alert("Error : Not available to add.");
			return false;
		}
		
		$.ajax({
			url:'favorite/add_favorite.php',
			type:'post',
			dataType:'json',
			data:{ 
				midx: l_midx,
				cidx: l_cidx,
				keyword:l_keyword,
				tags:l_tags
			},
			success:function(data) {
				if( data.r == 'success' || data.r == 'exist' ) {
					alert(data.msg);
				} else {
					alert(data.msg);
				}
				$("#addFavorite").hide();
				$('li.favorite').removeClass('on');
			}
		});
	});	
	$('li.favorite').click(function(e) {
		if ($(this).hasClass('on')) {
			$(this).removeClass('on');
			$('#addFavorite').hide();
		} else {
			$(this).addClass('on');
			<? if( !isset($_SESSION['USER_IDX']) || $_SESSION['USER_IDX'] == "" ) { ?>
			$('#addFavorite').addClass('sign');
			<? } else { ?>
			$('#addFavorite').removeClass('sign');
			$('#favoriteTags').val(DEFAULT_MSG);
			<? } ?>			
			$('#addFavorite').show();
		}
	});
});
//]]>
</script>
</head>
<body class="detail">
<div id="wrap" >
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=<?=FACEBOOK_APPID?>";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
	<div id="header">
		<h1><a href="/">Let's CC</a></h1>
		<ul class="menu">
			<li class="facebook"><div class="fb-like" data-href="<?='http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']?>" data-send="true" data-layout="button_count" data-width="90" data-show-faces="true"></div></li>
			<li class="tweet"><a href="https://twitter.com/share" class="twitter-share-button" data-text="I found something cool to share on LetsCC!" data-count="horizontal" data-lang="eng">Tweet</a><script type="text/javascript" src="//platform.twitter.com/widgets.js"></script></li>
			<li class="favorite">
				<span class="star"><a href="#">add favorite</a></span>
				<div id="addFavorite">
					<h2>Add new favorite</h2>
					<div class="sign">
						In order for you to use LetsCC favorate services, it is required to sign in.
						<ul>
							<li><a href="/member/login.php?re_url=<?=$_SERVER['REQUEST_URI']?>">&gt; Go to the Sign in pagea</a></li>
							<li><a href="/member/join.php?re_url=<?=$_SERVER['REQUEST_URI']?>">&gt; Go to the Sign up pagea</a></li>
						</ul>
						<p class="btns"><input type="button" class="btn-cancel" value="Cancel" /></p>
					</div>
					<dl>
						<dt>Search for :</dt>
						<dd><?=$searchWord?></dd>
					</dl>					
					<form id="favoriteForm" name="favoriteForm" method="post" action="">
					<fieldset>
						<legend>Add To Favorite Form</legend>
						<p class="tags">
							<textarea type="text" id="favoriteTags" name="favoriteTags">divide your tags with comma(i.e. flower, start, )</textarea>
						</p>
						<p class="btns">
							<input type="button" class="btn-cancel" value="Cancel" /><input type="button" class="btn-ok" value="Add to Favorite"  />
						</p>
					</fieldset>
					</form>
				</div>	
			</li>
		</ul>
	</div>
    <div id="body" class="detail">
	<? if( $contentData['c_type'] == "image" && strstr($dataUrl, 'flickr') ) { ?>
		<div id="scroll">
			<a href="<?=$dataUrl?>" class="url" target="_blink"><?=$dataUrl?></a>
			<a href="<?=$dataUrl?>" class="go-src" target="_blink">Go to link directly</a>
			<img src="<?=$imageUrl?>"  alt="detail image" class="preview" />
		</div>
	<? } else if ( $contentData['c_type'] == "video" ) { ?>
		<div id="scroll">
		<a href="<?=$dataUrl?>" class="url" target="_blink"><?=$dataUrl?></a>
			<a href="<?=$dataUrl?>" class="go-src" target="_blink">Go to link directly</a>
			<iframe src="<?=$videoUrl?>"  frameborder="0" width="640" height="480"  class="preview"></iframe>
		</div>
	<? } else { ?>
		<iframe src="<?=$dataUrl?>" id="detailFrm" frameborder="0" ></iframe>
	<? } ?>
    </div>
</div>
<?require_once 'comm/inc.alert.php';?>
</body>
</html>
