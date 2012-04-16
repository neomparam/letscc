<?
	require_once('comm/inc.session.php');
	$keyword = $_GET['k']; 
	$type = $_GET['t'];
	$comm = $_GET['c'];
	$deriv = $_GET['d'];
	if ($keyword == '') $body_class = 'init';
	else $body_class = 'search';
	if ($type == '' || $type == 'all') {
		$all_selected = 'selected';
	} else if ($type == 'image') {
		$image_selected = 'selected';
	} else if ($type == 'music') {
		$music_selected = 'selected';
	} else if ($type == 'video') {
		$video_selected = 'selected';
	} else if ($type == 'doc') {
		$doc_selected = 'selected';
	}
	if ($comm == '1') $comm_checked = 'checked';
	if ($deriv == '1') $deriv_checked = 'checked';
?>
<? require_once 'comm/inc.top.php';?>
<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#keyword').focus();

	$('#searchType li, .cc-licenses li, #body .main li').hover(function(e) {
		$(this).addClass('over');
	}, function(e) {
		$(this).removeClass('over');
	});

	$('#searchType li').click(function() {
		$('#contentType option').attr('selected', false);
		var type = this.className.replace(' over','');
		
		if (type == 'video') $('#msgDisableOption').show();
		else $('#msgDisableOption').hide();
		
		var selectedIndex = $("#contentType option").index($('#contentType option[value="' + type + '"]'));
		$("#contentType").prop('selectedIndex', selectedIndex);
		$('#body').attr('class','').addClass(this.className);
		$('#searchType li').removeClass('selected');
		$(this).addClass('selected');

		changeLicenseStatus();			
		return false;
	});
	changeLicenseStatus();
	$('.cc-licenses li').hover(function() {
		$(this).find('dd').show();
	}, function() {
		$(this).find('dd').hide();
	});

	$('.setFavorite').live('click', function() {
		$.ajax({
			url:'proc_favorite.php',
			dataType:'json',
			data:{
				type:'p',
				cid:'',
				url:'',
				midx:1,	//member idx
				kw:''
			},
			success:function(data) {
			},
			context:this
		});
	});
	CCFavoriteImageSearch = new CCFavoriteSearch('image', 7);
	CCFavoriteMusicSearch = new CCFavoriteSearch('music', 2);
	CCFavoriteVideoSearch = new CCFavoriteSearch('video', 1);
	CCFavoriteDocSearch = new CCFavoriteSearch('doc', 1);

	if ($('#keyword').val() != "") {
		$('body').attr('class', 'search');
		var type = $('#contentType').val();
		$('#body').attr('class','').addClass(type);
		$('#searchType li').removeClass('selected');
		$('#searchType li.' + type).addClass('selected');	
		search();
	}
});
function changeLicenseStatus() {
	var comm = $('#comm').attr('checked');
	var deriv = $('#deriv').attr('checked');
	$('.cc-licenses li dt').removeClass('disabled');
	if (comm) {
		$('.cc-licenses li.nc dt').addClass('disabled');
	}
	if (deriv) {
		$('.cc-licenses li.nd dt').addClass('disabled');
	}		
	if ($("#contentType").val() == 'video') {
		$('#msgDisableOption').show();
		$('.cc-licenses li.nc dt').addClass('disabled');
		$('.cc-licenses li.nd dt').addClass('disabled');
		$('.cc-licenses li.sa dt').addClass('disabled');
	}
	else $('#msgDisableOption').hide();
}
var searchAjaxCalls = [];
function getFlickrData(data, callback) {
	data.api_key = 'ec72ae6aba33c60bff198220d36e08b7';
	data.format = 'json';
	searchAjaxCalls.push($.ajax({
		url:'http://api.flickr.com/services/rest/',
		dataType:'jsonp',
		crossDomain:true,
		data: data,
		jsonp:'jsoncallback',
		success:callback
	}));
}
function getJamendoData(data, callback) {
	searchAjaxCalls.push($.ajax({
		url:'http://api.jamendo.com/get2/id+name+url+image+artist_name/track/jsoncallback/track_album+album_artist/',
		dataType:'jsonp',
		crossDomain:true,
		data: data,
		jsonp:'jsoncallbackfunction',
		success:callback
	}));
}
function getYoutubeData(data, callback) {
	searchAjaxCalls.push($.ajax({
		url:'http://gdata.youtube.com/feeds/api/videos',
		dataType:'jsonp',
		crossDomain:true,
		data: data,
		jsonp:'callback',
		success:callback
	}));
}

function getCCMixterData(data, callback ) {
	data.f = "js";

	searchAjaxCalls.push($.ajax({
		url:'query_ccmixter.php',
		dataType:'text',
		data: data,
		success:callback
	}));
}

function getSlideShareData(data, callback) {
	searchAjaxCalls.push($.ajax({
		url:'query_slideshare.php',
		dataType:'json',
		data: data,
		success:callback
	}));
}

CCImageSearch = {};
CCImageSearch.search = function(keyword, deriv, comm) {
	this.keyword = keyword;
	this.deriv = deriv;
	this.comm = comm;
	this.showPage(0);
}
CCImageSearch.showPage = function(p) {
	$("#imageResult").html('');
	$("#imageTemplate").template("imageTemplate");
	if (!p) p = 0;

	var deriv = this.deriv;
	var comm = this.comm;

	var rights = '';
	if (deriv && comm) {
		rights = '4,5';
	} else if (deriv) {
		rights = '1,2,4,5';
	} else if (comm) {
		rights = '4,5,6';
	} else {
		rights = '1,2,3,4,5,6';
	}
	$("#imageResult").html('');
	
	getFlickrData({
		method:'flickr.photos.search',
		text:this.keyword,
		page:p + 1,
		sort:'relevance',
		per_page:70,
		license:rights
	}, function(data) {
		var photos = data.photos.photo;
		if (photos.length == 0) {
			$("#imageResult").parent().addClass('none');
			return;
		}
		for(var i = 0, len = photos.length; i < len; i++) {
			var photo = photos[i];
			photo.tbURL = 'http://farm' + photo.farm + '.static.flickr.com/' + photo.server + '/' + photo.id + '_' + photo.secret + '_t.jpg';
			photo.imgURL = 'http://farm' + photo.farm + '.static.flickr.com/' + photo.server + '/' + photo.id + '_' + photo.secret + '_z.jpg';
			photo.href = 'http://www.flickr.com/photos/' + photo.owner + '/' + photo.id;
			var tmpl = $.tmpl( "imageTemplate" , photo);
			if (i % 7 == 0) tmpl.addClass('nl');
			tmpl.data('keyword',$('#keyword').val()).data('info',photo).data('s_name','flickr').data('type','image').appendTo( "#imageResult" );
		}
		if (p == 0) {
			var total = data.photos.total;
			$('#imagePaging').pagination(total,{
				callback:function(p, jq){CCImageSearch.showPage(p); return false;},
				items_per_page:70,
				num_display_entries:10,
				num_edge_entries:1,
				prev_text:'<',
				next_text:'>'
			});	
		}		
	});
}
var CCMusicSearch = {};
CCMusicSearch.list1 = [];
CCMusicSearch.list2 = [];
CCMusicSearch.list1Recevied = false;
CCMusicSearch.list2Recevied = false;
CCMusicSearch.limit = 15;
CCMusicSearch.search = function(keyword, deriv, comm) {
	this.keyword = keyword.replace(/[,]+/g, ' ');
	
	this.list1 = [];
	this.list1Cursor = 0;
	this.list2 = [];
	this.list2Cursor = 0;
	this.list1Recevied = false;
	this.list2Recevied = false;

	$("#musicResult").html('');
	$('#musicPaging').html('');
	var music_rights = '';
	if (deriv && comm) {
		music_rights = 'by+c+d';
	} else if (deriv) {
		music_rights = 'by+d';
	} else if (comm) {
		music_rights = 'by+c';
	} else {
		music_rights = 'by';
	}


	getJamendoData({
		tag_idstr:this.keyword,
		n:100,
		pn:1,
		license_minrights:music_rights
	}, function(data) {
		var listMusic = data;
		var tempHtml = "";
		if(listMusic) {
			for(var i = 0, len = listMusic.length; i < len; i++) {
				var music = listMusic[i];
				var music_license = "";
				music.download_url = 'http://api.jamendo.com/get2/stream/track/redirect/?id=' + music.id + '&streamencoding=mp31';		  			
				CCMusicSearch.list1.push(music);		
			}
		}
		CCMusicSearch.list1Recevied = true;
		CCMusicSearch.fillList();
	});

	var mixterRights = '';
	if (deriv && comm) {
		mixterRights = 'by,sa';
	} else if (deriv) {
		mixterRights = 'by,sa,nc,ncsa';
	} else if (comm) {
		mixterRights = 'by,sa,nd';
	} else {
		mixterRights = 'by,sa,ncsa,nd,nc,ncnd';
	}


	getCCMixterData( {
		limit:100,
		offset:1,
		search_type:"any",
		search:this.keyword,
		lic:mixterRights
	}, function(data) {
		eval("var listMusic = " + data);
		var tempHtml = "";
		
		if (listMusic) {
			for(var i = 0, len = listMusic.length; i < len; i++) {
				var music = listMusic[i];
				var music_license = "";
				
				var obj = {};
	
				obj.id = music.upload_id;
				obj.name = music.upload_name;
				obj.artist_name = music.user_name;
				obj.url = music.file_page_url;
				obj.download_url = music.files[0].download_url;
	
				CCMusicSearch.list2.push(obj);
			}
		}
		CCMusicSearch.list2Recevied = true;
		CCMusicSearch.fillList();
	});

}

CCMusicSearch.fillList = function() {
	if (this.list1Recevied && this.list2Recevied) {
		CCMusicSearch.showPage(0);
		
		$('#musicPaging').pagination(this.list1.length + this.list2.length,{
			callback:function(p, jq){CCMusicSearch.showPage(p); return false;},
			items_per_page:15,
			num_display_entries:10,
			num_edge_entries:1,
			prev_text:'<',
			next_text:'>'
		});
	}
}
CCMusicSearch.showPage = function(p) {
	if (this.list1.length == 0 && this.list2.length == 0) {
		$("#musicResult").parent().addClass('none');
		return;
	}
	$("#musicResult").html('');
	$("#musicTemplate").template("musicTemplate");
	if (!p) p = 0;
	this.list1Cursor = this.list2Cursor = 0;
	var shownItemCount = this.limit * p;
	if (shownItemCount > 0) {
		for (var i = 0; i < shownItemCount; i++) {
			if (this.list1Cursor < this.list2Cursor || this.list2.length == this.list2Cursor) {
				if (this.list1.length > this.list1Cursor) {			
					this.list1Cursor++;
					continue;
				}
			}
			if (this.list2.length > this.list2Cursor) {
				this.list2Cursor++;
			}
		}
	}
	
	for (var i = shownItemCount, len = shownItemCount + this.limit; i < len; i++) {
		if (this.list1Cursor < this.list2Cursor || this.list2.length == this.list2Cursor) {
			if (this.list1.length > this.list1Cursor) {
				var info = this.list1[this.list1Cursor];
				var musicTmpl = $.tmpl( "musicTemplate" , info);
				musicTmpl.appendTo( "#musicResult" );
				musicTmpl.data('keyword',$('#keyword').val()).data('info',info).data('s_name','jamendo').data('type','music');
				this.list1Cursor++;
				continue;
			}
		} 

		if (this.list2.length > this.list2Cursor) {
			var info = this.list2[this.list2Cursor];
			var musicTmpl = $.tmpl( "musicTemplate" , info );
			musicTmpl.appendTo( "#musicResult" );
			musicTmpl.data('keyword',$('#keyword').val()).data('info',info).data('s_name','ccmixter').data('type','music');
			this.list2Cursor++;
		}
	}
}

CCVideoSearch = {};
CCVideoSearch.search = function(keyword, deriv, comm) {
	this.keyword = keyword;
	this.showPage(0);
}
CCVideoSearch.showPage = function(p) {
	$("#videoResult").html('');
	$("#videoTemplate").template("videoTemplate");
	if (!p) p = 0;
	getYoutubeData( {
			q:this.keyword+" creativecommons", 
			v:2, 
			alt:"jsonc",
			'start-index':1 + (10 * p),
			"max-results":10
		}, function(data) {
			var objData = data.data;
			var listVideo = objData.items || [];
			if (objData.totalItems == 0) {
				$("#videoResult").parent().addClass('none');
				return;
			}
			for(var i = 0, len = listVideo.length; i < len; i++) {
				var video = listVideo[i];

				var obj = {};
				obj.id = video.id;
				obj.title = video.title;
				obj.thumb = video.thumbnail.sqDefault;
				obj.href = video.player['default'];
				obj.url = video.content[5];
				obj.hits = video.viewCount;
				obj.desc = video.description;
			
				var videoTmpl = $.tmpl( "videoTemplate", obj );
				videoTmpl.appendTo( "#videoResult" );
				videoTmpl.data('keyword',$('#keyword').val()).data('info',obj).data('s_name','youtube').data('type','video');
			}
			if (p == 0) {
				var total = objData ? objData.totalItems : 0;
				if (total > 1000) total = 1000;
				$('#videoPaging').pagination(total,{
					callback:function(p, jq){CCVideoSearch.showPage(p); return false;},
					items_per_page:10,
					num_display_entries:10,
					num_edge_entries:1,
					prev_text:'<',
					next_text:'>'
				});	
			}
	});
}
CCDocSearch = {}
CCDocSearch.search = function(keyword, deriv, comm) {
	this.keyword = keyword.replace(/\s+/g, ',');
	this.deriv = deriv;
	this.comm = comm;	
	this.showPage(0);
}
CCDocSearch.showPage = function(p) {
	var deriv = this.deriv;
	var comm = this.comm;	

	var docCC_cc = 1;
	var docCC_adapt = 1, docCC_commercial = 1;

	if (deriv && comm) {
		docCC_adapt = 0;
		docCC_commercial = 0;
	} else if (deriv) {
		docCC_commercial = 0;
	} else if (comm) {
		docCC_adapt = 0;
	} 

	$("#docTemplate").template("docTemplate");
	$("#docResult").html('');
	
	getSlideShareData( {
		limit:10,
		offset:p,
		search:this.keyword,
		cc:docCC_cc,
		cc_adapt:docCC_adapt,
		cc_commercial:docCC_commercial
	}, function(data) {
		if (data.Meta.TotalResults == 0) {
			$("#docResult").parent().addClass('none');
			return;
		}
		var listDoc = data.Slideshow;
		if (listDoc) {
			for(var i = 0, len = listDoc.length; i < len; i++) {
				var doc = listDoc[i];

				var obj = {};

				obj.id = doc.ID;
				obj.title = doc.Title;
				obj.href = doc.URL;
				obj.desc = typeof doc.Description == 'object' ? '' : doc.Description;
				obj.thumb = doc.ThumbnailSmallURL;
				var docTmpl = $.tmpl("docTemplate", obj);
				docTmpl.appendTo("#docResult");
				docTmpl.data('keyword',$('#keyword').val()).data('info',obj).data('s_name','slideshare').data('type','doc');
			}
		}
		if (p == 0) {
			var total = data.Meta.TotalResults;
			//if (total > 1000) total = 1000;
			$('#docPaging').pagination(total,{
				callback:function(p, jq){CCDocSearch.showPage(p); return false;},
				items_per_page:10,
				num_display_entries:10,
				num_edge_entries:1,
				prev_text:'<',
				next_text:'>'
			});	
		}		
	});
}
function search() {
	for (var i = 0; i < searchAjaxCalls.length; i++) {
		var call = searchAjaxCalls[i];
		call.abort();
	}
	searchAjaxCalls = [];
	var keyword = $('#keyword').val();

	if ( keyword == '' ) 
	{
		location.href = '/favorite.php';
		return false;
	}

	var comm = $('#comm').attr('checked');
	var deriv = $('#deriv').attr('checked');

	CCFavoriteImageSearch.search(keyword, deriv, comm);
	CCFavoriteMusicSearch.search(keyword, deriv, comm);
	CCFavoriteVideoSearch.search(keyword, deriv, comm);
	CCFavoriteDocSearch.search(keyword, deriv, comm);
	
	CCImageSearch.search(keyword, deriv, comm);
	CCMusicSearch.search(keyword, deriv, comm);
	CCVideoSearch.search(keyword, deriv, comm);
	CCDocSearch.search(keyword, deriv, comm);
}

$('.cc-options input').live('click', function() {
	var comm = $('#comm').attr('checked');
	var deriv = $('#deriv').attr('checked');
	$('.cc-licenses li dt').removeClass('disabled');
	if (comm) {
		$('.cc-licenses li.nc dt').addClass('disabled');
	}
	if (deriv) {
		$('.cc-licenses li.nd dt').addClass('disabled');
	}
	if($('body').hasClass('search')) {
	  $('#searchForm').submit();
	}
});

var CCFavoriteImageSearch, CCFavoriteMusicSearch, CCFavoriteVideoSearch, CCFavoriteDocSearch;
CCFavoriteSearch = function(type, limit) {
	this.type = type;
	this.limit = limit;
};
CCFavoriteSearch.prototype.search = function(keyword, deriv, comm) {
	this.keyword = keyword;
	this.deriv = deriv;
	this.comm = comm;
	
	this.showPage(0);
}
CCFavoriteSearch.prototype.showPage = function(p) {
	$('#' + this.type + 'FavoriteResult').html('').hide();
	$('#' + this.type + 'FavoriteTemplate').template(this.type + 'FavoriteTemplate');
	if (!p) p = 0;

	searchAjaxCalls.push($.ajax({
		url: 'comm/search_favorite.php',
		type: 'post',
		dataType:'json',
		context:this,
		data:{
			keyword:this.keyword,
			type:this.type,
			c:this.comm ? 'y':'n',
			d:this.deriv ? 'y':'n',
			cur_page:p,
			page_cnt:this.limit
		},
		success:function(data){
			var list = data.data || [];
			if (list.length > 0) {
				$( '#' + this.type + 'FavoriteResult' ).show();
			}
			for(var i = 0, len = list.length; i < len; i++) {
				var item = list[i];
				var tmpl = $.tmpl( this.type + 'FavoriteTemplate', item );
				tmpl.appendTo( '#' + this.type + 'FavoriteResult' );
				tmpl.data('keyword',$('#keyword').val()).data('info',item).data('s_name',item.s_name).data('type',this.type).data('idx',item.idx);
			}
		}
	}));
}
//]]>
</script>
</head>
<body class="<?=$body_class?>">
<?require_once 'comm/inc.go_detail.php';?>
<div id="wrap">
<?require_once 'comm/inc.header.php';?>
	<div id="msgDisableOption">
	Videos are only for (<strong>BY</strong>) license applied contents.
	</div>
	<div id="body" class="all">
	  <div class="main">
			<ul class="desc">
				<li class="desc1"><img src="i/main_desc1.gif" width="195" height="44" alt="about lets'cc" title="about lets'cc" /><span><a href="/about.php" onFocus="blur()"><img src="i/main_desc1_detail.gif" width="288" height="90" alt="" title="" /></a></span></li>
				<li class="desc2"><img src="i/main_desc2.gif" width="195" height="44" alt="about CC" title="about CC" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span><a href="http://creativecommons.org/" onFocus="blur()"><img src="i/main_desc2_detail.gif" width="288" height="90" alt="" title="" /></a></span></li>
				<li class="desc3"><img src="i/main_desc3.gif" width="243" height="44" alt="about CCL" title="about CCL" /><span><a href="http://creativecommons.org/licenses/" onFocus="blur()"><img src="i/main_desc3_detail.gif" width="288" height="90" alt="" title="" /></a></span></li>
			</ul>
			<div class="footer">
				<ul class="links">
					<li>© 2011 CC Korea Except where otherwise noted, content on this site is licensed 
					<a href="http://creativecommons.org/licenses/by/2.0/kr/deed.en">under a Creative Commons Attribution 2.0 Korea License.</a> <br>
					<a href="http://creativecommons.org/licenses/by/2.0/" target="_blank" class="logocc"><img src="http://i.creativecommons.org/l/by/2.0/kr/80x15.png" alt="creative commons korea" /></a>
				    <a href="http://www.cckorea.org/xe/?mid=english" target="_blank" class="logo"><img src="i/logo_init_bottom.gif" width="94" height="17" alt="creative commons korea" /></a>			        </li>
				</ul>
		  </div>
		</div>
		<ul id="searchType">
			<li class="all"><a href="#">All</a></li>
			<li class="image"><a href="#">images</a></li>
			<li class="music"><a href="#">sounds</a></li>
			<li class="video"><a href="#">videos</a></li>
			<li class="doc"><a href="#">docs</a></li>
		</ul>
		<div class="license-area">
			<ul class="cc-licenses">
				<li class="by lic1"><dl>
					<dt class="lic-by">by</dt>
					<dd><strong>(BY)</strong><span>You're free to use-modify, commercialize-it only with attribution.<br/></span></dd>
				</dl></li>
				<li class="by sa lic2"><dl>
					<dt class="lic-by-sa">by sa</dt>
					<dd><strong>(BY-SA)</strong><span>With Attribution, free to modify but carry the same license.</span></dd>
				</dl></li>
				<li class="by nc lic3"><dl>
					<dt class="lic-by-nc">by nc</dt>
					<dd><strong>(BY-NC)</strong><span>You're free to use but for non-commercial.</span></dd>
				</dl></li>
				<li class="by nc sa lic4"><dl>
					<dt class="lic-by-nc-sa">by nc sa</dt>
					<dd><strong>(BY-NC-SA)</strong><span>No commercial use allowed, free to modify carrying same license.</span></dd>
				</dl></li>
				<li class="by nd lic5"><dl>
					<dt class="lic-by-nd">by nd</dt>
					<dd><strong>(BY-ND)</strong><span>With attribution, without any modification.</span></dd>
				</dl></li>
				<li class="by nc nd lic6"><dl>
					<dt class="lic-by-nc-nd">by nc nd</dt>
					<dd><strong>(BY-NC-ND)</strong><span>Use it with attribution, but no change and commercial use allowed.</span></dd>
				</dl></li>
			</ul>
			<div class="desc">
				
				<p>
					<img src="i/cc_search_notice.gif" width="322" alt="CC Korea makes no warranty whatsoever in connection with the result of Search. You need to verify that the work is actually underr a CCL by following the link or clear the licensor." />
			  </p>
		  </div>
	  </div>
		<div id="searchResult">
			<h2>Search Result</h2>
			<div class="image type">
				<h3><a href="">images</a></h3>
				<div class="no-result">
					<img src="i/no_result.gif" width="353" height="15" alt="No results for your search terms." title="No results for your search terms." />
					<p>
						<span><strong>Tip)</strong> Let'sCC draws search result using API from various global service. </span>
						<span style="position:relative;left:6px;">You can get more contents via English keywords.</span>
					</p>
				</div>
				<a href="#" class="more">+ More</a>
				<ul id="imageFavoriteResult" class="favorite image list">
					<li class="link-detail">
						
					</li>
				</ul>				
				<ul id="imageResult" class="image list">
					<li class="link-detail">
						
					</li>
				</ul>
				<div id="imagePaging" class="paging"></div>
			</div>
			<div class="music type">
				<h3><a href="">sounds</a></h3>
				<div class="no-result">
					<img src="i/no_result.gif" width="353" height="15" alt="No results for your search terms." title="No results for your search terms." />
					<p>
						<span><strong>Tip)</strong> Let'sCC draws search result using API from various global service. </span>
						<span style="position:relative;left:6px;">You can get more contents via English keywords.</span>
					</p>
				</div>
				<a href="#" class="more">+ More</a>
				<ul id="musicFavoriteResult" class="favorite music list">
					<li>
						
					</li>
				</ul>				
				<ul id="musicResult" class="music list">
					<li>
						
					</li>
				</ul>
				<div id="musicPaging" class="paging"></div>
			</div>
			<div class="video type">
				<h3>videos</h3>
				<div class="no-result">
					<img src="i/no_result.gif" width="353" height="15" alt="No results for your search terms." title="No results for your search terms." />
					<p>
						<span><strong>Tip)</strong> Let'sCC draws search result using API from various global service. </span>
						<span style="position:relative;left:6px;">You can get more contents via English keywords.</span>
					</p>
				</div>
				<a href="#" class="more">+ More</a>
				<ul id="videoFavoriteResult" class="favorite video list">
					<li>
						
					</li>
				</ul>				
				<ul id="videoResult" class="video list">
					<li>
						
					</li>
				</ul>
				<div id="videoPaging" class="paging"></div>
			</div>
			<div class="doc type">
				<h3>docs</h3>
				<div class="no-result">
					<img src="i/no_result.gif" width="353" height="15" alt="No results for your search terms." title="No results for your search terms." />
					<p>
						<span><strong>Tip)</strong> Let'sCC draws search result using API from various global service. </span>
						<span style="position:relative;left:6px;">You can get more contents via English keywords.</span>
					</p>
				</div>
				<a href="#" class="more">+ More</a>
				<ul id="docFavoriteResult" class="favorite doc list">
					<li>
						
					</li>
				</ul>				
				<ul id="docResult" class="doc list">
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
<script id="imageTemplate" type="text/x-jquery-tmpl">
					<li class="link-detail">
						<a href="${href}" class="img go-detail"><img src="${tbURL}" alt="${title}" title="${title}" /></a>
						<a href="#" class="favorite"><img src="i/btn_favorite.gif" width="13" height="13" alt="add favorite"></a>
					</li>
</script>
<script id="musicTemplate" type="text/x-jquery-tmpl">
					<li class="link-detail">
						<dl>
							<dt><span class="desc"><a href="${url}" class="go-detail">${name} <span class="by">by</span> ${artist_name}</a></span></dt>
							<dd class="download">
								<object type="application/x-shockwave-flash" data="i/player_mp3_maxi.swf" width="25" height="20">
									<param name="movie" value="i/player_mp3_maxi.swf" />
									<param name="wmode" value="transparent" />
									<param name="FlashVars" value="mp3=${download_url}&amp;showslider=0&amp;width=25" />
								</object>
							</dd>
							<dd class="favorite"><a href="#" class="favorite"><img src="i/btn_favorite.gif" width="13" height="13" alt="add favorite"></a></dd>
						</dl>
					</li>
</script>


<script id="videoTemplate" type="text/x-jquery-tmpl">
					<li class="link-detail">
						<dl>
							<dt><a href="${href}" class="title go-detail">${title}</a><a href="#" class="favorite"><img src="i/btn_favorite.gif" width="13" height="13" alt="add favorite"></a></dt>
							<dd class="desc"><a href="${href}" class="go-detail">${desc}</a></dd>
							<dd class="hits">read : ${hits}</dd>
							<dd class="image"><a href="${href}" class="go-detail"><img src="${thumb}" width="125" height="80" alt="${title}"	title="${title}" /></a></dd>
						</dl>
					</li>
</script>
<script id="docTemplate" type="text/x-jquery-tmpl">
					<li class="link-detail">
						<dl>
							<dt><a href="${href}" class="title go-detail">${title}</a><a href="#" class="favorite"><img src="i/btn_favorite.gif" width="13" height="13" alt="add favorite"></a></dt>
							<dd class="desc"><a href="${href}" class="go-detail">${desc}</a></dd>
							<dd class="image"><a href="${href}" class="go-detail"><img src="${thumb}" width="125" height="80" alt="${title}"	title="${title}" /></a></dd>
						</dl>
					</li>
</script>
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
									<param name="wmode" value="transparent" />
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
							<dt><a href="${href}" class="title go-detail">${title}</a><a href="#" class="favorite"><img src="i/btn_favorite.gif" width="13" height="13" alt="add favorite"><span>${f_count}<em>${f_count}users marked it as favorite다.</em></span></a></dt>
							<dd class="desc"><a href="${href}" class="go-detail">${desc}</a></dd>
							<dd class="hits">read : ${hits}회</dd>
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
