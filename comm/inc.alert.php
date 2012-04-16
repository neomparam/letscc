<div class="fixed">
	<div id="notice">알림 Notice user 님이 로그인 하셨습니다.</div>

</div>
<script type="text/javascript">
window.alert = function(msg) {
	$('#notice').text(msg).show();
	setTimeout(function() {$('#notice').hide();}, 2000);
}
</script>