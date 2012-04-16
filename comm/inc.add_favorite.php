	<script type="text/javascript">
	var DEFAULT_MSG = 'Add tags seperated by commas';
	$('#searchResult a.favorite').live('click',function () {
		var parent_li = $(this).closest(".link-detail");

		var l_info = parent_li.data('info');
		var l_s_name = parent_li.data('s_name');
		var l_type = parent_li.data('type');
		var l_keyword = $('#keyword').val();
		var c = $('#comm').attr('checked') ? 'y':'n';
		var d = $('#deriv').attr('checked') ? 'y':'n';

		var l_c_idx = "";

		$.ajax({
			url:'content/save_content.php',
			type:'post',
			dataType:'json',
			context:this,
			data:{ 
				'id': l_info.id,
				'keyword':l_keyword,
				'info':JSON.stringify(l_info),
				's_name':l_s_name,
				'c_type':l_type,
				'c':c,
				'd':d
			},
			success:function(data) {
				if( data.r == 'success' || data.r == 'exist' ) {
					l_c_idx = data.idx;

					<? if( !isset($_SESSION['USER_IDX']) || $_SESSION['USER_IDX'] == "" ) { ?>
					$('#addFavorite').addClass('sign');
					<? } else { ?>
					$('#addFavorite').removeClass('sign');
					$('#favoriteTags').val(DEFAULT_MSG);
					<? } ?>
					var offset = $(this).offset();
					offset.top += $('#wrap').scrollTop();
					$("#addFavorite").data('c_idx',l_c_idx).data('keyword',l_keyword)
						.hide().css(offset).fadeIn('slow');
				}
			}
		});

		return false;
	});	

	$(function() {
		$('#searchType li').click(function() {
			$("#addFavorite").hide();
			return false;
		});		
		$('#favoriteTags').focus(function() {
			if ($('#favoriteTags').val() == DEFAULT_MSG) $('#favoriteTags').val('');
		});
		$('#favoriteTags').blur(function() {
			if ($('#favoriteTags').val() == '') $('#favoriteTags').val(DEFAULT_MSG);
		});
		$('#addFavorite .btn-ok').click( function() {
			if ($('#favoriteTags').val() == DEFAULT_MSG) $('#favoriteTags').val('');
			var f_data			= $('#addFavorite').data();
			var l_midx			= "<?=$_SESSION['USER_IDX']?>";
			var l_c_idx		= f_data.c_idx;
			var l_keyword	= f_data.keyword;
			var l_tags			= $('#favoriteTags').val();

			$.ajax({
				url:'favorite/add_favorite.php',
				type:'post',
				dataType:'json',
				data:{ 
					'midx': l_midx,
					'cidx': l_c_idx,
					'keyword':l_keyword,
					'tags':l_tags
				},
				success:function(data) {
					if( data.r == 'success' || data.r == 'exist' ) {
						alert(data.msg);
					} else {
						alert(data.msg);
					}
					$("#addFavorite").fadeOut('slow');
				}
			});
		});	

		$('#addFavorite .btn-cancel').click(function() {
			$("#addFavorite").fadeOut('slow');
		});

		$('#wrap').scroll( function(e) {
			$("#addFavorite").fadeOut('slow');
		});
		$('#wrap').click( function(e) {
			if( $(e.target).closest('#addFavorite').length < 1 )
				$("#addFavorite").hide();
		});
	});
	</script>
	<div id="addFavorite" class="sign">
		<h2>Add new favorite</h2>
		<div class="sign">
			In order for you to use LetsCC favorate services, it is required to sign in.
			<ul>
				<li><a href="/member/login.php?re_url=<?=urlencode($_SERVER['REQUEST_URI'])?>">&gt; Go to the Sign in page</a></li>
				<li><a href="/member/join.php?re_url=<?=urlencode($_SERVER['REQUEST_URI'])?>">&gt; Go to the Sign up page</a></li>
			</ul>
			<p class="btns"><input type="button" class="btn-cancel" value="Cancel" /></p>
		</div>
		<form id="favoriteForm" name="favoriteForm" method="post" action="">
		<fieldset>
			<legend>Add new favorite</legend>
			<p class="tags">
				<textarea type="text" id="favoriteTags" name="favoriteTags">Add tags seperated by commas</textarea>
			</p>
			<p class="btns">
				<input type="button" class="btn-cancel" value="Cancel" /><input type="button" class="btn-ok" value="Add favorate"  />
			</p>
		</fieldset>
		</form>
	</div>