<?
	session_start();
	
	$re_url = ( trim($_POST["re_url"]) ) ? trim($_POST["re_url"]) : trim($_GET["re_url"]);
	if ( $re_url == "" ) $re_url = "/";
	
	if( $_SESSION['USER_AGREE'] != 'n' ) {  
		header('Location: '.$re_url);
	}
?>

<? require_once '../comm/inc.top.php';?>

	<style thype="text/css">
		html { overflow:auto;}		
		body { background-color:#f9fafc; overflow:auto; position:relative; }
		#joinConfirmArea { position:relative; width:417px; padding-top:39px; margin:98px auto 0; }
		#joinConfirmArea h1 { position:absolute; top:0; right:4px; }
			#join_confirm_form { border-top:3px solid #4682a1; }
			#join_confirm_form fieldset { padding-left:16px; }
			#join_confirm_form h2 { display:block; padding-top:24px; }
			#join_confirm_form .desc { padding-top:13px; height:29px; font-size:12px; color:#475f73; }
			#join_confirm_form p { position:relative; }
			#join_confirm_form p.check { height:26px; }			
			#join_confirm_form p span.error { display:none; position:absolute; right:0; bottom:-10px; z-index:9; background-color:#fff; heigh:20px; line-height:20px; padding:0 6px; color:#ff5019; border:1px solid #c9c9cb; font-size:11px; }
			#join_confirm_form label { font-size:13px; color:#8398a3; font-family:dotum; }
			#join_confirm_form .area-confirm {  }
			#join_confirm_form .user-type { height:24px; line-height:24px; padding-left:11px; background-color:#d7dfe3; font-size:13px; color:#494949; }
			#join_confirm_form .user-profile { height:69px; line-height:24px; padding:9px 0 0 11px; background-color:#f0f3f5; overflow:hidden; }
			#join_confirm_form .user-profile img { width:48px; height:48px; border:3px solid #fff; }
			#join_confirm_form .user-name { padding-left:23px; font-size:13px; color:#000; font-weight:bold; }
			
			#join_confirm_form .area-policy { margin-top:10px; }
			#join_confirm_form .area-policy a { font-weight:bold;font-size:13px; color:#8398a3; font-family:dotum; border-bottom:1px solid #8398a3; }
			#join_confirm_form .area-button { margin-top:18px; text-align:right; }
			#join_confirm_form .area-button .btn-cancel { margin-right:8px; }
			#join_confirm_form .area-button .btn-ok { margin-right:11px; }
		#joinConfirmArea .footer { text-align:center; margin:262px 0 25px 0; }
		#joinConfirmArea .footer a { font-size:11px; color:#858585; font-family:dotum; }
		#joinConfirmArea .footer a.link-privacy {  margin-left:7px; padding-left:6px; border-left:1px solid #858585;  }
		#joinConfirmArea .footer img { margin-left:15px; }
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
		
		return false;
	}
		
	$(function () {
		$('#joinConfirmArea a.btn-cancel').click( function() {
			location.href = "logout.php";
			return false;
		});
		
		$('#joinConfirmArea a.btn-ok').click( function() {

			$('#join_confirm_form').submit();
			return false;
		});
		
		$('#join_confirm_form').validate({
			errorElement:'span',
			rules: {
				policyAgreeConfirm: 'required'
			},

			messages: {  
				policyAgreeConfirm: {
					required: 'Check Agree for Terms of Use and Privacy Policies'
				}
			},

			success:function(em) {
				showValidSuccess(em);
			},

			submitHandler: function (frm) {
				frm.submit();
			}
		});
					
	});
	//]]>
	</script>
</head>
<body>
<div id="joinConfirmArea">
	<h1><a href="/"><img src="../i/logo_member.jpg" alt="Let's CC" title="Let's CC"/></a></h1>
	<form name="join_confirm_form" id="join_confirm_form" action="join_confirm_proc.php" method="post">
	<fieldset>
		<input type="hidden" name="user_idx" value="<?=$_SESSION['USER_IDX']?>" />
		<input type="hidden" name="re_url" value="<?=$re_url?>" />
		<h2><img src="../i/title_join.gif" alt="Sign Up" title="Sign Up"/></h2>
		<p class="desc">Registration will be done after adding additional information.</p>
		<p class="area-confirm">
			<div class='user-type'>Your newly registered account is following :<?=$_SESSION['USER_TYPE']?> </div>
			<div class='user-profile'>
				<img src="<?=$_SESSION['USER_IMAGE']?>" alt="user image" /><span class='user-name'><?=$_SESSION['USER_NAME']?></span>
			</div>
		</p>
		<p class="area-policy check">
			<input type="checkbox" id="policyAgreeConfirm" name="policyAgreeConfirm" value='y' /><label for="policyAgreeConfirm">
			<a href="#" class="link-agreement">I agree to the Terms of Service,</a>
			<a href="#" class="link-privacy"> Privacy Policy of CCK.</a></label>
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