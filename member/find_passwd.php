<?
	$re_url = ( trim($_POST["re_url"]) ) ? trim($_POST["re_url"]) : trim($_GET["re_url"]);
	if ( $re_url == "" ) $re_url = "/";
?>

<? require_once '../comm/inc.top.php';?>

	<style thype="text/css">
		html { overflow:auto;}
		body { background-color:#f9fafc; overflow:auto; position:relative; }
		#findPasswdArea { position:relative; width:417px; padding-top:39px; margin:98px auto 0; }
		#findPasswdArea h1 { position:absolute; top:0; right:4px; }
			#find_passwd_form { border-top:3px solid #4682a1; }
			#find_passwd_form fieldset { padding-left:16px; }
			#find_passwd_form h2 { display:block; padding-top:24px; }
			#find_passwd_form .desc { padding-top:13px; height:29px; font-size:12px; color:#475f73; }
			#find_passwd_form p { position:relative; }
			#find_passwd_form p.input { height:39px; }
			#find_passwd_form p span.error { display:none; position:absolute; right:0; bottom:-10px; z-index:9; background-color:#fff; heigh:20px; line-height:20px; padding:0 6px; color:#ff5019; border:1px solid #c9c9cb; font-size:11px; }
			#find_passwd_form p span.mark { display:block; position:absolute; right:20px; bottom:12px; width:20px; height:17px; }
			#find_passwd_form p span.vaild { background:url('../i/mark_member_vaild.gif')}
			#find_passwd_form p span.invaild { background:url('../i/mark_member_invaild.gif')}
			#find_passwd_form label { font-size:13px; color:#8398a3; font-family:dotum; }
			#find_passwd_form label.form-title { position:absolute; top:12px; left:13px; }
			#find_passwd_form .input-text { width:383px; height:35px; border:1px solid #d7dfe3; line-height:35px; padding-left:5px; }
			#find_passwd_form .area-email { margin-top:21px; }
			#find_passwd_form .area-button { margin-top:18px; text-align:right; }
			#find_passwd_form .area-button .btn-cancel { margin-right:8px; }
			#find_passwd_form .area-button .btn-ok { margin-right:11px; }
			#find_passwd_form .msg { position:absolute; top:17px; left:6px; display:block; font-size:12px; color:#8398a3; font-family:dotum; display:none; }
		#findPasswdArea .footer { text-align:center; margin:262px 0 25px 0; }
		#findPasswdArea .footer a { font-size:11px; color:#858585; font-family:dotum; }
		#findPasswdArea .footer a.link-privacy { margin-left:7px; padding-left:6px; border-left:1px solid #858585; }
		#findPasswdArea .footer img { margin-left:15px; }
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
		$('#findPasswdArea a.btn-cancel').click( function() {
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

		$('#findPasswdArea a.btn-ok').click( function() {
			$('#find_passwd_form').submit();
			return false;
		});
		
		$('#find_passwd_form').validate({
			errorElement:'span',
			rules: {
				passwdEmail: {
					required: true,
					email: true,
					remote: {
						type : 'POST',
						url  : 'validEmail.php'
					}
				}
			},
			messages: {
				passwdEmail: {
					required: function(r,el) { $('#findPasswdArea .btn-ok').attr('disabled',true); return showValidError( el, 'Sign in with your mail account.' ); },
					email: function(r,el){ $('#findPasswdArea .btn-ok').attr('disabled',true); return showValidError( el, 'Invalid email address. Please checke and try again.' ); },
					remote: function(r,el){ $('#findPasswdArea .btn-ok').attr('disabled',true); return showValidError( $('#passwdEmail'), 'Error : This mail is not registered.' ); }
				}
			},
			success:function(em) {
				showValidSuccess(em);
				$('#findPasswdArea .btn-ok').attr('disabled',false);
			},
			submitHandler: function (frm) {
				var l_email = $('#passwdEmail').val();

				$.ajax({
					url:'find_passwd_proc.php',
					type:'post',
					dataType:'json',
					data:{ email: l_email },
					success:function(data) {
						if( data.r == "success" ) {
							alert('We sent your temporary password to your mail account.');
							location.href = 'login.php';
						//$('#findPasswdArea .message').text(data.msg);
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
<div id="findPasswdArea">
	<h1><a href="/"><img src="../i/logo_member.jpg" alt="Let's CC" title="Let's CC"/></a></h1>
	<form name="find_passwd_form" id="find_passwd_form" action="find_passwd_proc.php" method="post">
	<fieldset>
		<h2><img src="../i/title_find_passwd.gif" alt="Find Password" title="Find Password"/></h2>
		<p class="desc">Insert your mail address registered.</br> We will send you temporary password for sign in via registered mail address.</p>
		<p class="area-email input">
			<label for="passwdEmail" class="form-title">E-mail</label><input type="text" id="passwdEmail" name="passwdEmail" class="input-text"/><span class="mark"></span>
		</p>
		<p class="area-button">
			<a href="#" class="btn-cancel"><img src="../i/btn_member_cancel.gif" alt="join cancel" title="join cancel" /></a>
			<a href="#" class="btn-ok"><img src="../i/btn_member_ok.gif" alt="join ok" title="join ok" /></a>
		</p>
	</fieldset>
	</form>
	<?require_once '../comm/inc.footer.member.php';?>
</div>
</body>
</html>