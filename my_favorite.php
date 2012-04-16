<?
	require_once('comm/inc.session.php');
	require_once('lib/class.favorites.php');
	$keyword = $_GET['k'];
	$Favorite = new clsFavorites( $DB->getConnection() );
?>

<? require_once 'comm/inc.top.php';?>

<script type="text/javascript">
//<![CDATA[
var DEFAULT_MSG = 'Add tags seperated by commas';

$('#searchResult .tags, a.btn-modify').live('click',function() {
	var tags = $(this).closest('.link-detail').find('.tags');
	if( tags.hasClass('editable') ) {
		return;
	}
	var tagText = tags.text();

	tags.data('orgText',tagText);
	if (tagText == DEFAULT_MSG) tagText = '';
	
	tags.addClass('editable').html( $('<textarea>').val( tagText ) )
		.append( $('<a>').text('cancel').addClass('btn-cancel') )
		.append( $('<a>').text('confirm').addClass('btn-ok') );
	tags.find('textarea').focus();
});

$('#searchResult .btn-cancel').live('click', function() {
	var tags = $(this).closest('.tags');
	tags.removeClass('editable').html(tags.data('orgText'));
	return false;
});

$('#searchResult .btn-ok').live('click', function() {

	var orgText = $(this).closest('.tags').data('orgText');
	var idx = $(this).closest('li').data('idx');
	var $tags = $(this).closest('.tags');
	var tagText = $tags.find('textarea').val();

	if( tagText == orgText ) {
		$tags.find('.btn-cancel').click();
		return false;
	}

	$.ajax({
		url:'favorite/modify_tags.php',
		dataType:'json',
		type:'post',
		data:{
			'idx':idx,
			'tags':tagText
		},
		success: function(data) {
			if( data.r == 'success' ) {
				var tags = $(this).closest('.tags');
				var tagText = tags.find('textarea').val();
				if ($.trim(tagText) == '') tagText = DEFAULT_MSG;
				tags.removeClass('editable').html(tagText);
			}
		},
		context:this
	});

	return false;
});

$('.btn-delete').live('click', function() {
	if (!confirm('Would you like to delete?')) return;
	var idx = $(this).closest('li').data('idx');

	$.ajax({
		url:'favorite/delete_favorite.php',
		dataType:'json',
		type:'post',
		data:{ 'idx':idx },
		success: function(data) {
			if( data.r == 'success' ) {
				var list = $(this).closest('li').parent();
				var curPage = list.data('page');
				list.data('self').showPage(curPage);
			}
		},
		context:this
	});

	return false;
});

var TYPE = 'all';
var CCMyImageSearch, CCMyMusicSearch, CCMyVideoSearch, CCMyDocSearch;
$(function() {
    $('#searchType li').click(function() {
    	$('#contentType option').attr('selected', false);
    	TYPE = this.className;

    	var selectedIndex = $("#contentType option").index($('#contentType option[value="' + TYPE + '"]'));
    	$("#contentType").prop('selectedIndex', selectedIndex);
        $('#body').attr('class','').addClass(this.className);
        $('#searchType li').removeClass('selected');
        $(this).addClass('selected');
        return false;
    });
    CCMyImageSearch = new CCMyFavoriteSearch('image', 15);
	CCMyMusicSearch = new CCMyFavoriteSearch('music', 10);
	CCMyVideoSearch = new CCMyFavoriteSearch('video', 10);
	CCMyDocSearch = new CCMyFavoriteSearch('doc', 10);
	var tag = $('#tag').val();
    CCMyImageSearch.search(tag);
	CCMyMusicSearch.search(tag);
	CCMyVideoSearch.search(tag);
	CCMyDocSearch.search(tag);


	$('#tagCloudArea a').click( function () {
		document.location.href = 'my_favorite.php?k=' + $(this).text();
		return false;
	});
});

CCMyFavoriteSearch = function(type, perPage) {
	this.type = type;
	this.perPage = perPage;
	$('#' + this.type + 'Result').data('self', this)
};
CCMyFavoriteSearch.prototype.search = function(keyword) {
	this.keyword = keyword;
	this.showPage(0);
}
CCMyFavoriteSearch.prototype.showPage = function(p) {
	$('#' + this.type + 'Result').data('page', p).html('');
	$('#' + this.type + 'Template').template('' + this.type + 'Template');
	if (!p) p = 0;

	$.ajax({
		url: 'comm/get_my_favorite.php',
		type: 'post',
		dataType:'json',
		context:this,
		data:{
			keyword:this.keyword,
			type:this.type,
			cur_page:p,
			page_cnt:this.perPage
		},
		success:function(data){
			var list = data.data || [];
			if (list.length == 0) {
				var wrap = $('#' + this.type + 'Result').parent();
				wrap.addClass('none');
				if (this.keyword == '') {
					wrap.find('img.not_added').show();
				} else {
					wrap.find('img.not_searched').show();
				}
				return;
			}			
			for(var i = 0, len = list.length; i < len; i++) {
				var item = list[i];
				if (this.type == 'image') {
					item.tbURL = item.tbURL.replace('_t.','_s.');
				}
				if (item.tags == '') {
					item.tags = DEFAULT_MSG;
				}	
				var tmpl = $.tmpl( this.type + 'Template', item );
				tmpl.appendTo( '#' + this.type + 'Result' );
				tmpl.data('keyword',item.search_word).data('info',item).data('s_name',item.s_name).data('type',this.type).data('idx',item.idx);
			}
			if (p == 0) {
				var total = data.total;
				if (total > 1000) total = 1000;
				var self = this;
				$('#' + this.type + 'Paging').pagination(total,{
					callback:function(p, jq){self.showPage(p); return false;},
					items_per_page:self.perPage,
					num_display_entries:10,
					num_edge_entries:2,
					prev_text:'<',
					next_text:'>'
				});	
			}
		},
	});
}

//]]>
</script>

</head>
<body class="my_favorite">
<?require_once 'comm/inc.go_detail.php';?>
<div id="wrap">
<?require_once 'comm/inc.header.php';?>
	<div id="tagCloudArea">
		<h2>Tag Cloud</h2>
		<ol>
		<?
			$myTagList = $Favorite->getMyTagList($_SESSION['USER_IDX']);
	
			for( $i=0; $i < count($myTagList); $i++ ) {
				$l_tag = $myTagList[$i]['tag'];
				$className = '';
				if ($keyword == $l_tag) $className = 'keyword';
				echo "<li><a href='#' class=".$className.">".$l_tag."</a></li>";
			}
		?>
		</ol>
	</div>
    <div id="body" class="all">
		<h2 class="title"><img src="i/title_my_favorite.gif" alt="내  즐겨찾기" title="내  즐겨찾기" /></h2>
        <div class="desc">
        	<!-- <span>인기있는CC들을 모아 놓았습니다.</span> --><br/>
        	The starred contents can be accessed quickly via ‘My Favorites’.<br/> You can add your own tags on 'My Favorites' and search in favorites. 
        </div>
        <ul id="searchType">
			<!--
            <li class="all selected"><a href="#">전체</a></li>
            <li class="image"><a href="#">이미지</a></li>
            <li class="music"><a href="#">음악</a></li>
            <li class="video"><a href="#">동영상</a></li>
            <li class="doc"><a href="#">문서</a></li>
			//-->
			<li class="all selected"><a href="#">all</a></li>
            <li class="image"><a href="#">images</a></li>
            <li class="music"><a href="#">sounds</a></li>
            <li class="video"><a href="#">videos</a></li>
            <li class="doc"><a href="#">docs</a></li>
        </ul>
        <div id="searchResult">
			<h2>검색결과</h2>
			<div class="image type">
				<h3><a href="">images</a></h3>
				<div class="no-result">
					<img class="not_added" src="i/no_favorite_result.gif" width="228" height="15" alt="아직 즐겨찾기한 내용이 없습니다." title="아직 즐겨찾기한 내용이 없습니다." />
					<img class="not_searched" src="i/no_favorite_search_result.gif" width="148" height="15" alt="검색 결과가 없습니다." title="검색 결과가 없습니다." />
				</div>
				<a href="#" class="more">+ More</a>		
				<ul id="imageResult" class="image list">
					<li class="link-detail">
					</li>
				</ul>
				<div id="imagePaging" class="paging"></div>
			</div>
			<div class="music type">
				<h3><a href="">sounds</a></h3>
				<div class="no-result">
					<img class="not_added" src="i/no_favorite_result.gif" width="228" height="15" alt="아직 즐겨찾기한 내용이 없습니다." title="아직 즐겨찾기한 내용이 없습니다." />
					<img class="not_searched" src="i/no_favorite_search_result.gif" width="148" height="15" alt="검색 결과가 없습니다." title="검색 결과가 없습니다." />
				</div>
				<a href="#" class="more">+ More</a>				
				<ul id="musicResult" class="music list">
					<li>
					</li>
				</ul>
				<div id="musicPaging" class="paging"></div>
			</div>
			<div class="video type">
				<h3>videos</h3>
				<div class="no-result">
					<img class="not_added" src="i/no_favorite_result.gif" width="228" height="15" alt="아직 즐겨찾기한 내용이 없습니다." title="아직 즐겨찾기한 내용이 없습니다." />
					<img class="not_searched" src="i/no_favorite_search_result.gif" width="148" height="15" alt="검색 결과가 없습니다." title="검색 결과가 없습니다." />
				</div>
				<a href="#" class="more">+ More</a>			
				<ul id="videoResult" class="video list">
					<li>
						
					</li>
				</ul>
				<div id="videoPaging" class="paging"></div>
			</div>
			<div class="doc type">
				<h3>docs</h3>
				<div class="no-result">
					<img class="not_added" src="i/no_favorite_result.gif" width="228" height="15" alt="아직 즐겨찾기한 내용이 없습니다." title="아직 즐겨찾기한 내용이 없습니다." />
					<img class="not_searched" src="i/no_favorite_search_result.gif" width="148" height="15" alt="검색 결과가 없습니다." title="검색 결과가 없습니다." />
				</div>
				<a href="#" class="more">+ More</a>			
				<ul id="docResult" class="doc list">
					<li>
						
					</li>
				</ul>
				<div id="docPaging" class="paging"></div>
			</div>
			<div class="end"></div>
			<form id="tagSearchForm" action="my_favorite.php">
				<fieldset>
					<legend>Tag search</legend>
					<div class="search-input">
						<input type="text" name="k" id="tag" value="<?=htmlspecialchars(stripslashes($keyword))?>" />
						<input type="submit" id="tagSubmit" value="검색" />
					</div>
				</fieldset>
			</form>
		</div>
    </div>
<?require_once 'comm/inc.footer.php';?>
</div>
<?require_once 'comm/inc.fixed.php';?>
<script id="imageTemplate" type="text/x-jquery-tmpl">
<li class="link-detail" >
	<p class="title">${search_word}</p>
    <a href="${href}" class="go-detail"><img src="${tbURL}" alt="${title}" width="75" height="75" title="${title}" /></a>
	<p class="tags">${tags}</p>
	<p class="controls"><a class="btn-modify">modify</a> <a class="btn-delete">delete</a></p>
</li>
</script>

<script id="musicTemplate" type="text/x-jquery-tmpl">
					<li class="link-detail">
						<p class="title">${search_word}</p>
						<dl>
							<dt><span class="desc"><a href="${url}" class="go-detail">${name} <span class="by">by</span> ${artist_name}</a></span></dt>
							<dd class="download">
								<object type="application/x-shockwave-flash" data="i/player_mp3_maxi.swf" width="25" height="20">
									<param name="movie" value="i/player_mp3_maxi.swf" />
									<param name="FlashVars" value="mp3=${download_url}&amp;showslider=0&amp;width=25" />
								</object>
							</dd>
						</dl>
						<p class="tags">${tags}</p>
						<p class="controls"><a class="btn-modify">modify</a> <a class="btn-delete">delete</a></p>
					</li>
</script>
<script id="videoTemplate" type="text/x-jquery-tmpl">
					<li class="link-detail">
						<p class="title">${search_word}</p>
						<dl>
							<dt><a href="${href}" class="title go-detail">${title}</a></dt>
							<dd class="desc"><a href="${href}" class="go-detail">${desc}</a></dd>
							<dd class="hits">read : ${hits}회</dd>
							<dd class="image"><a href="${href}" class="go-detail"><img src="${thumb}" width="120" height="85" alt="${title}"	title="${title}" /></a></dd>
						</dl>
						<p class="tags">${tags}</p>
						<p class="controls"><a class="btn-modify">modify</a> <a class="btn-delete">delete</a></p>
					</li>
</script>
<script id="docTemplate" type="text/x-jquery-tmpl">
					<li class="link-detail">
						<p class="title">${search_word}</p>
						<dl>
							<dt><a href="${href}" class="title go-detail">${title}</a></dt>
							<dd class="desc"><a href="${href}" class="go-detail">${desc}</a></dd>
							<dd class="image"><a href="${href}" class="go-detail"><img src="${thumb}" width="120" height="85" alt="${title}"	title="${title}" /></a></dd>
						</dl>
						<p class="tags">${tags}</p>
						<p class="controls"><a class="btn-modify">modify</a> <a class="btn-delete">delete</a></p>
					</li>
</script>
</body>
</html>
