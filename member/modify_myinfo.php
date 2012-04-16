<?
	session_start();
	
	$re_url = ( trim($_POST["re_url"]) ) ? trim($_POST["re_url"]) : trim($_GET["re_url"]);
	if ( $re_url == "" ) $re_url = "/";
	
	if( !isset($_SESSION['USER_IDX']) || $_SESSION['USER_IDX'] == '' ) {  
		header('Location: '.$re_url);
	}
?>

<? require_once '../comm/inc.top.php';?>

	<style thype="text/css">
		html { overflow:auto;}
		body { background-color:#f9fafc; overflow:auto; position:relative; }
		#mypageArea { position:relative; width:417px; padding-top:39px; margin:98px auto 0; }
		#mypageArea h1 { position:absolute; top:0; right:4px; }
			#mypage_form { border-top:3px solid #4682a1; }
			#mypage_form fieldset { padding-left:16px; }
			#mypage_form h2 { display:block; padding-top:24px; }
			#mypage_form .desc { padding-top:13px; font-size:12px; color:#475f73; }
			#mypage_form p { position:relative; }
			#mypage_form p.input { height:39px; }
			#mypage_form p.check { height:26px; }
			#mypage_form p span.error { display:none; position:absolute; right:0; bottom:-10px; z-index:9; background-color:#fff; heigh:20px; line-height:20px; padding:0 6px; color:#ff5019; border:1px solid #c9c9cb; font-size:11px; }
			#mypage_form p span.mark { display:block; position:absolute; right:20px; bottom:12px; width:20px; height:17px; }
			#mypage_form p span.vaild { background:url('../i/mark_member_vaild.gif')}
			#mypage_form p span.invaild { background:url('../i/mark_member_invaild.gif')}
			#mypage_form label { font-size:13px; color:#8398a3; font-family:dotum; }
			#mypage_form label.form-title { position:absolute; top:11px; left:13px; }
			#mypage_form .input-text { width:383px; height:35px; border:1px solid #d7dfe3; line-height:35px; padding-left:5px; }
			#mypage_form .area-email {  font-size:12px; color:#475f73; font-family:dotum; font-weight:bold; }
			#mypage_form .area-email .user-id { padding-left:16px; font-weight:normal; }
			#mypage_form .area-email { margin-top:14px; }
			#mypage_form .area-cur-passwd,
			#mypage_form .area-passwd,
			#mypage_form .area-passwd-confirm { margin-top:12px; }
			#mypage_form .area-button { margin-top:18px; text-align:right; }
			#mypage_form .area-button .btn-cancel { margin-right:8px; }
			#mypage_form .area-button .btn-ok { margin-right:11px; }
			#mypage_form .msg { position:absolute; top:17px; left:6px; display:block; font-size:12px; color:#8398a3; font-family:dotum; display:none; }
		#mypageArea .area-oauth { margin-top:16px; padding-top:11px; border-top:1px solid #dbe2e6; }
		#mypageArea .f-join { margin-left:35px; }
		#mypageArea .t-join { margin-left:47px; }
		#mypageArea .footer { text-align:center; margin:262px 0 25px 0; }
		#mypageArea .footer a { font-size:11px; color:#858585; font-family:dotum; }
		#mypageArea .footer a.link-privacy {margin-left:7px; padding-left:6px; border-left:1px solid #858585;}
		#mypageArea .footer img { margin-left:15px; }
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
		$('#mypageArea a.btn-cancel').click( function() {
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

		$('#mypageArea a.btn-ok').click( function() {
			$('#mypage_form').submit();
			return false;
		});

		$('#mypage_form').keypress( function(e) {
			if( e.keyCode == 13 ) {
				$('#mypage_form').submit();
				return false;
			}
		});
		
		$('#mypage_form').validate({
			errorElement:'span',
			rules: {
				currentPasswd: {
					required: true,
					remote: {
						type : 'POST',
						data : { email :$('#mypage_form .user-id').text() },
						url  : 'confirm_passwd.php'
					}
				}, 
				changePasswd: {
					required: true,
					minlength: 6
				}, 
				changePasswdConfirm: {
					required: true,
					minlength: 6,
					equalTo: '#changePasswd'
				}
			},
			messages: {
				currentPasswd: {
					required: function(r,el) { return showValidError( el, 'Insert your current password.' ); },
					remote: function(r,el){ return showValidError( $('#currentPasswd'), 'Error : Password does not match.' ); }
				},
				changePasswd: {
					required: function(r,el) { return showValidError( el, 'Error : Password must be at least 6 characters.' ); },
					minlength: function(r,el) { return showValidError( el, 'Error : Password must be at least 6 characters.' ); }
				},
				changePasswdConfirm: {
					required: function(r,el) { return showValidError( el, 'Error : Password must be at least 6 characters.' ); },
					minlength: function(r,el) { return showValidError( el, 'Error : Password must be at least 6 characters.' ); },
					equalTo: function(r,el) { return showValidError( el, 'Error : Passwords do not match. Please retype and try again.' ); }
				}
			},
			success:function(em) {
				showValidSuccess(em);
			},
			submitHandler: function (frm) {
				var l_email = $('#mypage_form .user-id').text();
				var l_passwd = $('#changePasswd').val();

				$.ajax({
					url:'modify_myinfo_proc.php',
					type:'post',
					dataType:'json',
					data:{ email: l_email, passwd:l_passwd },
					success:function(data) {
						alert(data.msg);
						history.go(-1);
					}
				});
			}
		});
					
	}); //end ready
	//]]>
	</script>
</head>
<body>
<div id="mypageArea">
	<h1><a href="/"><img src="../i/logo_member.jpg" alt="Let's CC" title="Let's CC"/></a></h1>
	<form name="mypage_form" id="mypage_form" action="modify_myinfo.php" method="post">
	<fieldset>
		<h2><img src="../i/title_modify_myinfo.gif" alt="정보수정" title="정보수정"/></h2>
		<p class="desc"></p>
		<p class="area-email" >
			E-mail<span class="user-id"><?=$_SESSION['USER_ID']?></span>
		</p>
		<p class="area-cur-passwd input">
			<label for="currentPasswd" class="form-title">Your current password</label><input type="password" id="currentPasswd" name="currentPasswd" class="input-text"/><span class="mark"></span>
		</p>
		<p class="area-passwd input">
			<label for="changePasswd" class="form-title">New password</label><input type="password" id="changePasswd" name="changePasswd" class="input-text"/><span class="mark"></span>
		</p>
		<p class="area-passwd-confirm input">
			<label for="changePasswdConfirm" class="form-title">Verify your new password</label><input type="password" id="changePasswdConfirm" name="changePasswdConfirm" class="input-text"/><span class="mark"></span>
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