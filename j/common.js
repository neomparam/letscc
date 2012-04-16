$('#searchResult li a.favorite').live('mouseover', function() {
	$(this).find('span').addClass('over');
}).live('mouseout', function() {
	$(this).find('span').removeClass('over');
})
$('#searchResult a.more, #searchResult h3 a').live('click', function() {
	var type = $(this).closest('.type').attr('class').replace(' type', '');
	$('#searchType li.' + type).click();
	return false;
})
$('#searchResult li a.favorite em').live('mouseover mousemove', function() {
	$(this).closest('span').removeClass('over');
	return false;
})