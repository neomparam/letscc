<?
	$re_url = ( trim($_POST["re_url"]) ) ? trim($_POST["re_url"]) : trim($_GET["re_url"]);
	if ( $re_url == "" ) $re_url = "/";
?>

<? require_once '../comm/inc.top.php';?>

	<style thype="text/css">
		html { overflow:auto;}
		body { background-color:#f9fafc; overflow:auto; position:relative; }
	</style>

	<script type="text/javascript">
	//<![CDATA[
	function showValidError( obj, msg ) {
		$(obj).siblings('span.mark').removeClass('vaild');
		$(obj).siblings('span.mark').addClass('invaild');

		return msg;
	}
	function showValidSuccess(obj){
		setTimeout(function(){	$(obj).hide();}, 0);
		
		if ($(obj).attr('for') != 'policyAgree') {
			$(obj).siblings('span.mark').removeClass('invaild');
			$(obj).siblings('span.mark').addClass('vaild');
		}
		
		return false;
	}

	$(function () {
		$('#joinArea a.f-join').click( function() {
			location.href = '/member/oauth/facebook/redirect.php?re_url=<?=$re_url?>';
			return false;
		});
		
		$('#joinArea a.t-join').click( function() {
			location.href = '/member/oauth/twitter/redirect.php?re_url=<?=$re_url?>';
			return false;
		});
		
		$('#joinArea a.btn-cancel').click( function() {
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

		$('#joinArea a.btn-ok').click( function() {
			$('#join_form').submit();
			return false;
		});

		$('#join_form').keypress( function(e) {
			if( e.keyCode == 13 ) {
				$('#join_form').submit();
				return false;
			}
		});
		
		$('#join_form').validate({
			errorElement:'span',
			rules: {
				joinPasswd: {
				   required: true,
				   minlength: 6
			   },
				joinPasswdConfirm: {
				   required: true,
				   minlength: 6,
				   equalTo: '#joinPasswd'
			   },
				joinEmail: {
					required: true,
					email: true,
					remote : {
						type : 'POST',
						url  : 'validEmail.php'
					}
				},
				policyAgree: 'required'
			},

			messages: {  
				joinPasswd: {
					required: function(r,el) { return showValidError( el, 'Error : Password must be at least 6 characters.' ); },
					minlength: function(r,el) { return showValidError( el, 'Error : Password must be at least 6 characters.' ); }
				},
				joinPasswdConfirm: {
					required: function(r,el) { return showValidError( el, 'Error : Password must be at least 6 characters.' ); },
					minlength: function(r,el) { return showValidError( el, 'Error : Password must be at least 6 characters.' ); },
					equalTo: function(r,el) { return showValidError( el, 'Error : Passwords do not match. Please retype and try again.' ); }
				},
				joinEmail: {
					required: function(r,el) { return showValidError( el, 'You may use this email account to change your password.<br /> Please enter an active, valid email address.' ); },
					email: function(r,el){ return showValidError( el,'Invalid email address. Please check and try again.' ); },
					remote: function(r,el){ return showValidError( el, 'THe email you entered has already been registered.' ); }
				},
				policyAgree: {
					required: 'Check Agree for Terms of Use and Privacy Policies'
				}
			},

			success:function(em) {
				showValidSuccess(em);
			},

			submitHandler: function (frm) {
                console.log(frm);
				frm.submit();
			}
		});
					
	});
	//]]>
	</script>
</head>
<body>
<div id="joinArea">
	<h1><a href="/"><img src="../i/logo_member.jpg" alt="Let's CC" title="Let's CC"/></a></h1>
	<form name="join_form" id="join_form" action="join_proc.php" method="post">
	<fieldset>
		<h2><img src="../i/title_join.gif" alt="sign up" title="sign up"/></h2>
		<p class="desc">Use your available E-mail address and password more than 6 characters.<br />
Submit to finish or sign in with your facebook or twitter account.</p>

		<p class="area-email input">
			<label for="joinEmail" class="form-title">E-mail</label><input type="text" id="joinEmail" name="joinEmail" class="input-text"/><span class="mark"></span>
		</p>
		<p class="area-passwd input">
			<label for="joinPasswd" class="form-title">Password</label><input type="password" id="joinPasswd" name="joinPasswd" class="input-text"/><span class="mark"></span>
		</p>
		<p class="area-passwd-confirm input">
			<label for="joinPasswdConfirm" class="form-title">Re-type Password</label><input type="password" id="joinPasswdConfirm" name="joinPasswdConfirm" class="input-text"/><span class="mark"></span>
		</p>
		<!--
		<p class="area-policy check">
			<input type="checkbox" id="policyAgree" name="policyAgree" value='y' /><label for="policyAgree">
				<a href="../site_agreement.php" class="link-agreement" target="_blink" >이용약관</a>
				<a href="../site_privacy.php" class="link-privacy" target="_blink" >개인정보취급 방침</a>	에 동의합니다</label>
		</p>
		//-->
		<!--
		<p class="area-policy check">
			<label for="policyAgree">
		I agree to the
				<a href="../site_agreement.php" class="link-agreement" target="_blink" >Terms of Service</a>,
				<a href="../site_privacy.php" class="link-privacy" target="_blink" >Privacy Policy </a>of CCK</label>
		</p>//-->
        <input type="checkbox" id="policyAgree" name="policyAgree" value="y" style="display:none;" checked="checked" />
		<p class="area-button">
			<a href="#" class="btn-cancel"><img src="../i/btn_member_cancel.gif" alt="join cancel" title="join cancel" /></a>
			<a href="#" class="btn-ok"><img src="../i/btn_member_ok.gif" alt="join ok" title="join ok" /></a>
		</p>
	</fieldset>
	</form>
	<div class="area-oauth">
		<a href="#" class="f-join"><img src="../i/btn_oauth_facebook.gif" alt="Sign in with Facebook" title="Sign in with Facebook" /></a>
		<a href="#" class="t-join"><img src="../i/btn_oauth_twitter.gif" alt="Sign in with Twitter" title="Sign in with Twitter" /></a>
	</div>
	<?require_once '../comm/inc.footer.member.php';?>
</div>
</body>
</html>