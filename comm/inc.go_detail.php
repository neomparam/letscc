<form name="godetailFrm" method="post" action="content/open_content.php" target="_blank" >
	<input type="hidden" name="id" />
	<input type="hidden" name="c_type" />
	<input type="hidden" name="c" />
	<input type="hidden" name="d" />		
	<input type="hidden" name="s_name" />
	<input type="hidden" name="info" />
	<input type="hidden" name="keyword" />
</form>
<script type="text/javascript">
//<![CDATA[
function ajaxGoDetailPage(id, info, name, type, keyword) {
	var f = document.godetailFrm;

	f.id.value = id;
	f.s_name.value = name;
	f.c.value = $('#comm').attr('checked') ? 'y':'n';
	f.d.value = $('#deriv').attr('checked') ? 'y':'n';
	f.c_type.value = type;
	f.info.value = JSON.stringify(info);
	f.keyword.value = keyword ? keyword : '';

	f.submit();
}

$('.go-detail').live('click', function() {
	var li = $(this).closest(".link-detail");

	var info = li.data('info');
	var s_name = li.data('s_name');
	var type = li.data('type');
	var keyword = li.data('keyword');
	ajaxGoDetailPage(info.id, info, s_name, type, keyword);

	return false;
});
//]]>
</script>