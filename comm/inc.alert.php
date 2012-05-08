<div class="fixed">
	<div id="notice">Notice: User logged in.</div>

</div>
<script type="text/javascript">
window.alert = function(msg) {
	$('#notice').text(msg).show();
	setTimeout(function() {$('#notice').hide();}, 2000);
}
</script>
