<? require_once('comm/inc.session.php'); ?>

<? require_once 'comm/inc.top.php';?>

<style type="text/css">
.favorite #searchResult div.none .no-result {background:none;}
.favorite #body.all #searchResult div.image {height:171px;}
.favorite #body.all #searchResult div.music {height:144px; overflow:hidden;}
.favorite #body.all #searchResult div.video {height:136px; overflow:hidden;}
.favorite #body.all #searchResult div.doc {height:136px; overflow:hidden;}

h2.title {display:block; margin:44px 0 0 40px;}
div.desc {text-align:right; width:910px;height:55px;font-size:12px;font-family:Dotum;color:#a2a2a2;margin:46px 0 0 18px;border-bottom:1px dotted #ccc;}
div.desc span {color:#ff010d;display:block;font-size:16px;padding-bottom:2px;font-family:NanumGothic, "Malgun Gothic", Dotum, Helvetica, Arial, sans-serif;}
.favorite #searchType {position:relative; left:0; top:0; margin:10px 0 0 25px; border-bottom:none; overflow:hidden;}
.favorite #searchType li {width:auto;height:20px; float:left;margin-left:-2px;background:url("i/favorite_navi_bar.gif") left 6px no-repeat;}
.favorite #searchType li a {background-color:transparent; float:left; display:block; width:auto; padding:0 12px; height:20px; color:#212121; font-size:15px; font-weight:bold; line-height:20px; cursor:pointer;}
.favorite #searchType li.selected a,
.favorite #searchType li.over a {color:#428cab;}

.favorite #searchResult {left:0; border:none; margin-top:38px; padding:0 50px 5px 42px; min-height:400px; overflow:hidden; }
.favorite #searchResult div.type {margin-top:-1px !important; width:910px;}

ul.image li.link-detail {padding:0 4px 10px 0;}

</style>

<script type="text/javascript">
//<![CDATA[
var CCFavoriteImageSearch, CCFavoriteMusicSearch, CCFavoriteVideoSearch, CCFavoriteDocSearch;
$(function() {
	$('#searchType li').hover(function(e) {
		$(this).addClass('over');
	}, function(e) {
		$(this).removeClass('over');
	});	
    $('#searchType li').click(function() {
        $('#body').attr('class','').addClass(this.className);
        $('#searchType li').removeClass('selected');
        $(this).addClass('selected');
        return false;
    });
    CCFavoriteImageSearch = new CCFavoriteSearch('image', 80);
	CCFavoriteMusicSearch = new CCFavoriteSearch('music', 15);
	CCFavoriteVideoSearch = new CCFavoriteSearch('video', 10);
	CCFavoriteDocSearch = new CCFavoriteSearch('doc', 10);

    CCFavoriteImageSearch.search('');
	CCFavoriteMusicSearch.search('');
	CCFavoriteVideoSearch.search('');
	CCFavoriteDocSearch.search('');
});


CCFavoriteSearch = function(type, limit) {
	this.type = type;
	this.limit = limit;
};
CCFavoriteSearch.prototype.search = function() {
	this.showPage(0);
}
CCFavoriteSearch.prototype.showPage = function(p) {
	$('#' + this.type + 'FavoriteResult').show().html('');
	$('#' + this.type + 'FavoriteTemplate').template('' + this.type + 'FavoriteTemplate');
	if (!p) p = 0;

	$.ajax({
		url: 'comm/get_favorite.php',
		type: 'post',
		dataType:'json',
		context:this,
		data:{
			type:this.type,
			cur_page:p,
			page_cnt:this.limit
		},
		success:function(data){
			var list = data.data || [];
			if (list.length == 0) {
				$('#' + this.type + 'FavoriteResult').parent().addClass('none');
				return;
			}
			for(var i = 0, len = list.length; i < len; i++) {
				var item = list[i];
				var tmpl = $.tmpl( this.type + 'FavoriteTemplate', item );
				tmpl.appendTo( '#' + this.type + 'FavoriteResult' );
				tmpl.data('info',item).data('s_name',item.s_name).data('type',this.type);
				tmpl.data('idx',item.idx);
			}
			if (p == 0) {
				var total = data.total;
				if (total > 1000) total = 1000;
				var self = this;
				$('#' + this.type + 'Paging').pagination(total,{
					callback:function(p, jq){self.showPage(p); return false;},
					items_per_page:self.limit,
					num_display_entries:10,
					num_edge_entries:1,
					prev_text:'<',
					next_text:'>'
				});	
			}
		}
	});
}

//]]>
</script>

</head>
<body class="favorite">
<?require_once 'comm/inc.go_detail.php';?>
<div id="wrap">
<?require_once 'comm/inc.header.php';?>
    <div id="body" class="all">
		<h2 class="title"><img src="i/title_favorite_cc.gif" alt="Favorites" title="Favorites" /></h2>
        <div class="desc">
			<span>Here are most featured contents</span>
        	You can look and check the most 'favorite' marked contents
        </div>
        <ul id="searchType">
            <li class="all selected"><a href="#">all</a></li>
            <li class="image"><a href="#">images</a></li>
            <li class="music"><a href="#">sounds</a></li>
            <li class="video"><a href="#">videos</a></li>
            <li class="doc"><a href="#">docs</a></li>
        </ul>
        <div id="searchResult">
			<h2>Search Result</h2>
			<div class="image type">
				<h3><a href="">images</a></h3>
				<div class="no-result">

				</div>
				<a href="#" class="more">+ More</a>
				<ul id="imageFavoriteResult" class="favorite image list">
					<li class="link-detail">
						
					</li>
				</ul>				
				<div id="imagePaging" class="paging"></div>
			</div>
			<div class="music type">
				<h3><a href="">sounds</a></h3>
				<div class="no-result">

				</div>
				<a href="#" class="more">+ More</a>
				<ul id="musicFavoriteResult" class="favorite music list">
					<li>
						
					</li>
				</ul>				
				<div id="musicPaging" class="paging"></div>
			</div>
			<div class="video type">
				<h3>videos</h3>
				<div class="no-result">

				</div>
				<a href="#" class="more">+ More</a>
				<ul id="videoFavoriteResult" class="favorite video list">
					<li>
						
					</li>
				</ul>				
				<div id="videoPaging" class="paging"></div>
			</div>
			<div class="doc type">
				<h3>docs</h3>
				<div class="no-result">

				</div>
				<a href="#" class="more">+ More</a>
				<ul id="docFavoriteResult" class="favorite doc list">
					<li>
						
					</li>
				</ul>				
				<div id="docPaging" class="paging"></div>
			</div>
			<div class="end"></div>
		</div>
    </div>
<?require_once 'comm/inc.add_favorite.php';?>
<?require_once 'comm/inc.footer.php';?>
</div>
<?require_once 'comm/inc.fixed.php';?>
<?require_once 'comm/inc.alert.php';?>

<script id="imageFavoriteTemplate" type="text/x-jquery-tmpl">
					<li class="link-detail">
						<a href="${href}" class="img go-detail"><img src="${tbURL}" alt="${title}" title="${title}" /></a>
						<a href="#" class="favorite"><img src="i/btn_favorite.gif" width="13" height="13" alt="add favorite"><span>${f_count}<em>${f_count}users marked it as favorite.</em></span></a>
					</li>
</script>
<script id="musicFavoriteTemplate" type="text/x-jquery-tmpl">
					<li class="link-detail">
						<dl>
							<dt><span class="desc go-detail"><a href="${url}">${name} <span class="by">by</span> ${artist_name}</a></span></dt>
							<dd class="download">
								<object type="application/x-shockwave-flash" data="i/player_mp3_maxi.swf" width="25" height="20">
									<param name="movie" value="i/player_mp3_maxi.swf" />
									<param name="FlashVars" value="mp3=${download_url}&amp;showslider=0&amp;width=25" />
								</object>
							</dd>
							<dd class="favorite"><a href="#" class="favorite"><img src="i/btn_favorite.gif" width="13" height="13" alt="add favorite"><span>${f_count}<em>${f_count}users marked it as favorite.</em></span></a></dd>
						 </dl>
					</li>
</script>


<script id="videoFavoriteTemplate" type="text/x-jquery-tmpl">
					<li class="link-detail">
						<dl>
							<dt><a href="${href}" class="title go-detail">${title}</a><a href="#" class="favorite"><img src="i/btn_favorite.gif" width="13" height="13" alt="add favorite"><span>${f_count}<em>${f_count}users marked it as favorite.</em></span></a></dt>
							<dd class="desc"><a href="${href}" class="go-detail">${desc}</a></dd>
							<dd class="hits">Read : ${hits}</dd>
							<dd class="image"><a href="${href}" class="go-detail"><img src="${thumb}" width="125" height="80" alt="${title}"	title="${title}" /></a></dd>
						</dl>
					</li>
</script>
<script id="docFavoriteTemplate" type="text/x-jquery-tmpl">
					<li class="link-detail">
						<dl>
							<dt><a href="${href}" class="title go-detail">${title}</a><a href="#" class="favorite"><img src="i/btn_favorite.gif" width="13" height="13" alt="add favorite"><span>${f_count}<em>${f_count}users marked it as favorite.</em></span></a></dt>
							<dd class="desc"><a href="${href}" class="go-detail">${desc}</a></dd>
							<dd class="image"><a href="${href}" class="go-detail"><img src="${thumb}" width="125" height="80" alt="${title}"	title="${title}" /></a></dd>
						</dl>
					</li>
</script>
</body>
</html>
