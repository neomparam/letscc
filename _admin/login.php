<style type="text/css">
input.input_login {background-color:#FFFFFF; border:1px solid #e5e5e5; width:170px; height:18px; font-family:Dotum; font-size:9pt; color: #638BA1; line-height:16px; LETTER-SPACING: -0.04em;}
#loginArea { width:100%; text-align:center; }
#loginArea fieldset { position:relative;  }
#loginArea h2 { font-weight:bold; font-size:18px; padding:20px 0;}
#loginArea label{ font-weight:bold; padding-right:10px; }
#loginArea p {padding-bottom:10px;}
#loginArea button.btn-login { width:100px; height:50px; position:absolute; right:240px; top:0; }
</style>
<script type="text/javascript">
//<![CDATA[
	$(function () {
		$('#loginArea .btn-login').click( function() {
			if( $('#loginId').val() == "" ) {
				alert('Input login id');
				$('#loginId').focus();
			} else if( $('#loginPasswd').val() == "" ) {
				alert('Input password');
				$('#loginPasswd').focus();
			} else {
				document.loginForm.submit();
			}
			return false;
		});
	});
//]]>
</script>
	<div id="loginArea">
		<h2>Login</h2>
		<form name="loginForm" method="post" action="login_ok.php">
		<fieldset>
			<legend>Log in</legend>
			<p>
				<label for="loginId">Admin ID : </label><input type="text" id="loginId" name="loginId" class="input_login" />
			</p>
			<p>
				<label for="loginPasswd">Passwd : </label><input type="password" id="loginPasswd" name="loginPasswd" class="input_login" />
			</p>
			<button class="btn-login">Login</button>
		</fieldset>
		</form>
	</div>
