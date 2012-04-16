<?
	$re_url = ( trim($_POST["re_url"]) ) ? trim($_POST["re_url"]) : trim($_GET["re_url"]);
	if ( $re_url == "" ) $re_url = "/";
?>

<? require_once '../comm/inc.top.php';?>

	<style thype="text/css">
		html { overflow:auto;}
		body { background-color:#f9fafc; overflow:auto; position:relative;}
		#loginArea { position:relative; width:417px; padding-top:39px; margin:98px auto 0; }
		#loginArea h1 { position:absolute; top:0; right:4px; }
			#login_form { border-top:3px solid #4682a1; }
			#login_form fieldset { padding-left:16px; }
			#login_form h2 { display:block; padding-top:24px; }
			#login_form .desc { padding-top:13px; height:29px; font-size:12px; color:#475f73; }
			#login_form p { position:relative;}
			#login_form p.input { height:39px; }
			#login_form p span.error { display:none; position:absolute; right:0; bottom:-10px; z-index:9; background-color:#fff; heigh:20px; line-height:20px; padding:0 6px; color:#ff5019; border:1px solid #c9c9cb; font-size:11px; }
			#login_form p span.mark { display:block; position:absolute; right:20px; bottom:12px; width:20px; height:17px; }
			#login_form p span.vaild { background:url('../i/mark_member_vaild.gif')}
			#login_form p span.invaild { background:url('../i/mark_member_invaild.gif')}
			#login_form label { font-size:13px; color:#8398a3; font-family:dotum; }
			#login_form label.form-title { position:absolute; top:11px; left:13px; }
			#login_form .input-text { width:383px; height:35px; border:1px solid #d7dfe3; line-height:35px; padding-left:5px; }
			#login_form .area-email { margin-top:21px; }
			#login_form .area-passwd { margin-top:12px; }
			#login_form .area-autologin { margin-top:10px; }
			#login_form .find-passwd { margin-left:10px; padding-left:7px; border-left:1px solid #8398a3; font-size:13px; color:#8398a3; font-family:dotum; font-weight:bold; }
			#login_form .area-button { margin-top:18px; text-align:right; }
			#login_form .area-button .btn-cancel { margin-right:8px; }
			#login_form .area-button .btn-ok { margin-right:11px; }
			#login_form .msg { position:absolute; top:17px; left:6px; display:block; font-size:12px; color:#8398a3; font-family:dotum; display:none; }
		#loginArea .area-oauth { margin-top:16px; padding-top:11px; border-top:1px solid #dbe2e6; }
		#loginArea .f-login { margin-left:35px; }
		#loginArea .t-login { margin-left:47px; }
		#loginArea .footer { text-align:center; margin:262px 0 25px 0; }
		#loginArea .footer a { font-size:11px; color:#858585; font-family:dotum; }
		#loginArea a.link-privacy { margin-left:7px; padding-left:6px; border-left:1px solid #858585; }
		#loginArea .footer img { margin-left:15px; }
	</style>

	<script type="text/javascript">
	//<![CDATA[
	function showValidError( obj, msg ) {
		$(obj).siblings('span.mark').removeClass('vaild');
		$(obj).siblings('span.mark').addClass('invaild');

		return msg;
	}
	function showValidSuccess( obj ) {
		setTimeout(function() {$(obj).hide();},0);
		$(obj).siblings('span.mark').removeClass('invaild');
		$(obj).siblings('span.mark').addClass('vaild');
		
		return false;
	}
		
	$(function () {
		$('#loginArea a.f-login').click( function() {
			location.href = '/member/oauth/facebook/redirect.php?re_url=<?=urlencode($re_url)?>';
			return false;
		});
		
		$('#loginArea a.t-login').click( function() {
			location.href = '/member/oauth/twitter/redirect.php?re_url=<?=urlencode($re_url)?>';
			return false;
		});
		
		$('#loginArea a.btn-cancel').click( function() {
			history.go(-1);
			return false;
		});
		
		$('.input-text').focus( function() {
			$(this).closest('p').find('.form-title').hide();
		});
		$('.input-text').blur( function() {
			if( $(this).val() == '' )
				$(this).closest('p').find('.form-title').show();
		});	
						
		$('#loginArea a.btn-ok').click( function() {

			$('#login_form').submit();
			return false;
		});
		
		$('#login_form').keypress( function(e) {
			if( e.keyCode == 13 ) {
				$('#login_form').submit();
				return false;
			}
		});

		$('#login_form').validate({
			errorElement:'span',
			'rules': {
				'loginPasswd': { required: true, minlength: 6 },
				'loginEmail': {
					'required': true,
					'email': true,
					'remote': {
						'type' : 'POST',
						'url' : 'validEmail.php'
					}
				}
			},
			messages: {
				loginPasswd: {
					required: function(r,el) { return showValidError( el, 'Error : Password must be at least 6 characters.' ); },
					minlength: function(r,el) { return showValidError( el, 'Error : Password must be at least 6 characters.' ); }
				},
				loginEmail: {
					required: function(r,el) { return showValidError( el, 'Please enter the email address you provided when you registered.' ); },
					email: function(r,el){ return showValidError( el, 'Invalid email address. Please check and try again.' ); },
					remote: function(r,el){ return showValidError( $('#loginEmail'), 'Error : Email not registered.' ); }
				}
			},
			success:function(em) {
				showValidSuccess(em);
			},
			submitHandler: function (frm) {
				var l_email = $('#loginEmail').val();
				var l_passwd = $('#loginPasswd').val();
				var l_autoLogin ='n';
				if( $("#autoLogin").is(':checked') )
					l_autoLogin = 'y';

				$.ajax({
					url:'login_proc.php',
					type:'post',
					dataType:'json',
					data:{
						loginEmail: l_email,
						loginPasswd: l_passwd,
						autoLogin: l_autoLogin
					},
					success:function(data) {
						if( data.r == 'success' ) {
							location.href = '<?=$re_url?>';
						} else {
							if( data.type == 'email' ) {
								$('#loginEmail').siblings('.error').show().text(data.msg);
							} else if( data.type == 'passwd' ) {
								$('#loginPasswd').siblings('.error').show().text(data.msg);
							}
						}
					}
				});
			}
		});
					
	});
	//]]>
	</script>
</head>
<body>
<div id="loginArea">
	<h1><a href="/"><img src="../i/logo_member.jpg" alt="Let's CC" title="Let's CC"/></a></h1>
	<form name="login_form" id="login_form" action="login_proc.php" method="post">
	<fieldset>
		<h2><img src="../i/title_login.gif" alt="로그인" title="로그인"/></h2>
		<p class="desc">submit your E-mail address and password or sign in with your facebook or twitter account.</p>
		<p class="area-email input">
			<label for="loginEmail" class="form-title">E-mail</label><input type="text" id="loginEmail" name="loginEmail" class="input-text"/><span class="mark"></span>
		</p>
		<p class="area-passwd input">
			<label for="loginPasswd" class="form-title">Password</label><input type="password" id="loginPasswd" name="loginPasswd" class="input-text"/><span class="mark"></span>
		</p>
		<p class="area-autologin">
			<input type="checkbox" id="autoLogin" name="autoLogin" value='y' /><label for="autoLogin">Keep me signed-in</label><a href="find_passwd.php" class="find-passwd">Find my password</a>
		</p>
		<p class="area-button">
			<a href="#" class="btn-cancel"><img src="../i/btn_member_cancel.gif" alt="login cancel" title="login cancel" /></a>
			<a href="#" class="btn-ok"><img src="../i/btn_member_ok.gif" alt="login ok" title="login ok" /></a>
		</p>
	</fieldset>
	</form>
	<div class="area-oauth">
		<a href="#" class="f-login"><img src="../i/btn_oauth_facebook.gif" alt="Sign in with Facebook" title="Sign in with Facebook" /></a>
		<a href="#" class="t-login"><img src="../i/btn_oauth_twitter.gif" alt="Sign in with Twitter" title="Sign in with Twitter" /></a>
	</div>
	<?require_once '../comm/inc.footer.member.php';?>
</div>
</body>
</html>