<style type="text/css">
	#joinArea { width:400px; position:absolute; right:0; top:42px; z-index:9; display:none; background-color:#fff; border:1px solid black; }
	#joinArea h2 { font-size:15px; font-weight:bold; }
	#joinArea .input-button { padding:3px 5px; }
	#joinArea fieldset p { margin:5px 50px 5px 0; position:relative; }
	#joinArea fieldset p label { margin-right:5px; }
	#joinArea fieldset p span.check { position:absolute; top:0; right:0; }
	#joinArea fieldset p span.valid { display:block; background-color:#eee; }
	#joinArea fieldset p span.error { display:block; background-color:#eee; color:red; font-weight:bold; }
	#joinArea fieldset p .red { color:red; font-weight:bold; }
	#joinArea fieldset p .blue { color:blue; font-weight:bold; }
	#joinArea .checkboxText { position:absolute; top:0; left:20px; }
		#joinForm { margin:5px 0 0 20px; }

	#joinConfirmArea { width:400px; position:absolute; right:0; top:42px; z-index:9; display:none; background-color:#fff; border:1px solid black; }
	#joinConfirmArea h2 { font-size:15px; font-weight:bold; }
	#joinConfirmArea .input-button { padding:3px 5px; }
	#joinConfirmArea fieldset p { margin:5px 50px 5px 0; position:relative; }
	#joinConfirmArea fieldset p span.error { display:block; background-color:#eee; color:red; font-weight:bold; }
	#joinConfirmArea fieldset .user-type { color:blue; }
	#joinConfirmArea fieldset .user-name { font-weight:bold; }
	#joinConfirmArea fieldset p .user-profile { clear:both; }
	#joinConfirmArea fieldset p label { margin-right:5px; }
	#joinConfirmArea .checkboxText { position:absolute; top:0; left:20px; }
		#joinConfirmForm { margin:5px 0 0 20px; }

	#loginArea { width:400px; position:absolute; right:0; top:42px; z-index:9; display:none; background-color:#fff; border:1px solid black; }
	#loginArea h2 { font-size:15px; font-weight:bold; }
	#loginArea .input-button { padding:3px 5px; }
	#loginArea fieldset p { margin:5px 50px 5px 0; position:relative; }
	#loginArea fieldset p label { margin:0 5px; }
	#loginArea fieldset p span.check { position:absolute; top:0; right:0; }
	#loginArea fieldset p span.valid { display:block; background-color:#eee; }
	#loginArea fieldset p span.error { display:block; background-color:#eee; color:red; font-weight:bold; }
	#loginArea fieldset p .red { color:red; font-weight:bold; }
	#loginArea fieldset p .blue { color:blue; font-weight:bold; }
		#loginForm { margin:5px 0 0 20px; }

	#findPasswdArea { width:400px; position:absolute; right:0; top:42px; z-index:9; display:none; background-color:#fff; border:1px solid black; }
	#findPasswdArea h2 { font-size:15px; font-weight:bold; }
	#findPasswdArea .input-button { padding:3px 5px; }
	#findPasswdArea fieldset p { margin:5px 50px 5px 0; position:relative; }
	#findPasswdArea fieldset p label { margin:0 5px; }
	#findPasswdArea fieldset p span.check { position:absolute; top:0; right:0; }
	#findPasswdArea fieldset p span.error { display:block; background-color:#eee; color:red; font-weight:bold; }
	#findPasswdArea fieldset p .red { color:red; font-weight:bold; }
	#findPasswdArea fieldset p .blue { color:blue; font-weight:bold; }
		#findPasswdForm { margin:5px 0 0 20px; }

	#favoriteArea { width:400px; position:absolute; right:0; top:42px; z-index:9; display:none; background-color:#fff; border:1px solid black; }
	#favoriteArea h2 { font-size:15px; font-weight:bold; }
	#favoriteArea .input-button { padding:3px 5px; }
	#favoriteArea fieldset p { margin:5px 50px 5px 0; position:relative; }
	#favoriteArea fieldset p label { margin:0 5px; }
	#favoriteArea .desc { display:block; color:#999; }
	#favoriteArea .search-text { font-weight:bold; color:red; }
		#favoriteArea .message { color:blue; font-weight:bold; }
	
		#favoriteForm { margin:5px 0 0 20px; }
</style>
<script type="text/javascript" src="../j/jquery.validate.js"></script>
<script type="text/javascript">
//<![CDATA[
	function showValidError( obj, msg ) {
		$(obj).siblings('span.valid').hide();
		$(obj).siblings('span.check').text('X');
		$(obj).siblings('span.check').removeClass('blue');
		$(obj).siblings('span.check').addClass('red');

		return msg;
	}
	function showValidSuccess( obj ) {
		$(obj).siblings('span.valid').show();
		$(obj).siblings('span.check').text('O');
		$(obj).siblings('span.check').removeClass('red');
		$(obj).siblings('span.check').addClass('blue');
	}

	$( function() {
		$('#btnFavorites').click( function() {
			
			<? if( !isset($_SESSION['USER_IDX']) || $_SESSION['USER_IDX'] == "" ) { ?>
				$('#loginArea').toggle('slow');
			<? } else { ?>
				$('#favoriteArea').toggle('slow');
			<? } ?>
			return false;
		});

		$('body').click( function(e) {
			if( $(e.target).closest('#joinArea').length < 1 )
				$('#joinArea').hide('slow');
			if( $(e.target).closest('#loginArea').length < 1 )
				$('#loginArea').hide('slow');
			if( $(e.target).closest('#findPasswdArea').length < 1 )
				$('#findPasswdArea').hide('slow');
			if( $(e.target).closest('#favoriteArea').length < 1 )
				$('#favoriteArea').hide('slow');
		});

		$('#joinArea .btn-cancel').click( function() {
			$('#joinArea').hide('slow');
			return false;
		});

		$('#joinForm').validate({
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
						url  : 'member/validEmail.php'
					}
				},
				policyAgree: 'required'
			},

			messages: {  
				joinPasswd: {
					required: function(r,el) { return showValidError( el, 'Error : Passwords do not match. Please retype and try again.' ); },
					minlength: function(r,el) { return showValidError( el, 'Error : Passwords do not match. Please retype and try again.' ); }
				},
				joinPasswdConfirm: {
					required: function(r,el) { return showValidError( el, 'Error : Passwords do not match. Please retype and try again.' ); },
					minlength: function(r,el) { return showValidError( el, 'Error : Passwords do not match. Please retype and try again.' ); },
					equalTo: function(r,el) { return showValidError( el, 'Error : Passwords do not match. Please retype and try again.' ); }
				},
				joinEmail: {
					required: function(r,el) { return showValidError( el, 'You may use this email account to change your password. So please enter an active, valid email address.' ); },
					email: function(r,el){ return showValidError( el,'Invalid email address. Please checke and try again.' ); },
					remote: function(r,el){ return showValidError( el, 'This mail is already registered.' ); }
				},
				policyAgree: {
					required: 'Check Agree for Terms of Use and Privacy Policies'
				}
			},

			success:function(em) {
				if( em.attr('for') != 'policyAgree' )
					showValidSuccess(em);
			},

			submitHandler: function (frm) {
				frm.submit();
			},
		});

		<? if( $_SESSION['USER_AGREE'] == 'n' ) { ?> 
			$('#joinConfirmArea').show();
		<? } ?>

		$('#joinConfirmForm').validate({
			errorElement:'span',
			rules: { policyAgreeConfirm: 'required' }, 
			messages: { policyAgreeConfirm: { required: 'Check Agree for Terms of Use and Privacy Policies' } },
			submitHandler: function (frm) { frm.submit(); }
		});
		$('#joinConfirmArea .btn-cancel ').click( function() {
			location.href = "member/logout.php";
		});

		$('#btnMemberLogin').click( function() {
			$("#joinArea").hide('slow');
			$("#loginArea").toggle('slow');
			return false;
		});

		$('#loginArea .btn-cancel').click( function() {
			$('#loginArea').hide('slow');
			return false;
		});

		$('#loginForm').validate({
			errorElement:'span',
			rules: {
				loginPasswd: {
				   required: true,
				   minlength: 6
			   },
				loginEmail: {
					required: true,
					email: true,
					remote: {
						type : 'POST',
						url  : 'member/validEmail.php'
					}
				}
			},
			messages: {
				loginPasswd: {
					required: function(r,el) { return showValidError( el, 'Error : Password must be at least 6 characters.' ); },
					minlength: function(r,el) { return showValidError( el, 'Error : Password must be at least 6 characters.' ); }
				},
				loginEmail: {
					required: function(r,el) { return showValidError( el, 'Sign in with your mail account.' ); },
					email: function(r,el){ return showValidError( el, 'Invalid email address. Please checke and try again.' ); },
					remote: function(r,el){ return showValidError( $('#loginEmail'), 'Error : This mail is not registered.' ); }
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
					url:'member/login.php',
					type:'post',
					dataType:'json',
					data:{
						loginEmail: l_email,
						loginPasswd: l_passwd,
						autoLogin: l_autoLogin,
					},
					success:function(data) {
						if( data.r == 'success' ) {
							opener.location.reload();
							location.reload();
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

		$('#loginArea .find-passwd').click( function() {
			$('#loginArea').hide('slow');
			$('#findPasswdArea').show('slow');
			return false; 
		});
		$('#loginArea .go-join').click( function() {
			$('#loginArea').hide('slow');
			$('#joinArea').show('slow');
			return false; 
		});

		$('#findPasswdArea .btn-cancel').click( function() {
			$('#findPasswdArea').hide('slow');
		});
		$('#findPasswdArea .btn-ok').attr('disabled',true);
		
		$('#findPasswdForm').validate({
			errorElement:'span',
			rules: {
				passwdEmail: {
					required: true,
					email: true,
					remote: {
						type : 'POST',
						url  : 'member/validEmail.php'
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
					url:'member/find_passwd.php',
					type:'post',
					dataType:'json',
					data:{ email: l_email },
					success:function(data) {
						$('#findPasswdArea .message').text(data.msg);
					}
				});
			}
		});

		$('#favoriteArea .btn-cancel').click( function() {
			$('#favoriteArea').hide('slow');
		});

		$('#favoriteArea .btn-ok').click( function() {
			var l_midx = "<?=$_SESSION['USER_IDX']?>";
			var l_cidx = "<?=$contentIdx?>";
			var l_keyword = "<?=$searchWord?>";
			var l_tags = $('#favoriteTags').val();

			if( l_midx == '' || l_cidx == '' ) {
				alert("Error : Not available to add");
				return false;
			}
			
			$.ajax({
				url:'favorite/add_favorite.php',
				type:'post',
				dataType:'json',
				data:{ 
					midx: l_midx,
					cidx: l_cidx,
					keyword:l_keyword,
					tags:l_tags
				},
				success:function(data) {
					if( data.r == 'success' ) {
						//location.reload();
						$('#favoriteArea .message').text(data.msg);
					} else {
						$('#favoriteArea .message').text(data.msg);
					}
				}
			});
		});
	});
//]]>
</script>

            <h1><a href="/">Let's CC</a></h1>
            <div id="gnb">
                <h2>Menu</h2>
                <ul>
                    <li class="favorite"><a href="">Most Featured</a></li>

                    <li><a href="" id="btnFavorites">Favorites</a></li>
                </ul>
            </div>
			<div id="joinArea">
				<h2>Sign Up</h2>
				<form id="joinForm" name="joinForm" method="post" action="member/join.php">
				<fieldset>
					<legend>Sign Up Form</legend>
					<p>
						<label for="joinEmail">E-mail</label><input type="text" id="joinEmail" name="joinEmail" /><span class="check"></span>
						<span class="valid">Insert another available mail account for changing your password.</span>
					</p>
					<p>
						<label for="joinPasswd">password</label><input type="password" id="joinPasswd" name="joinPasswd" /><span class="check"></span>
						<span class="valid">Error : Passwords do not match. Please retype and try again.</span>
					</p>
					<p>
						<label for="joinPasswdConfirm">Verify password</label><input type="password" id="joinPasswdConfirm" name="joinPasswdConfirm" /><span class="check"></span>
						<span class="valid"></span>
					</p>
					<p>
						<input type="checkbox" id="policyAgree" name="policyAgree" value='y' /><label for="policyAgree" class="checkboxText"><a href="#">
						I agree to terms of use</a>,<a href="#">and privacy policies.</a></label>
					</p>
					<p>
						<input type="button" class="btn-cancel input-button" value="Cancel" /><input type="submit" class="input-button" value="Sign Up" />
					</p>
					<p>
						<a href="/member/oauth/facebook/redirect.php"><img src="http://static.ak.fbcdn.net/rsrc.php/zB6N8/hash/4li2k73z.gif" alt="Sign in with Facebook" title="Sign in with Facebook" /></a>
						<a href="/member/oauth/twitter/redirect.php"><img src="/member/oauth/twitter/images/darker.png" alt="Sign in with Twitter" title="Sign in with Twitter" /></a>
					</p>
				</fieldset>
				</form>
			</div>
			<div id="joinConfirmArea">
				<h2>Sign Up</h2>
				<form id="joinConfirmForm" name="joinConfirmForm" method="post" action="member/join_confirm.php">
				<input type="hidden" name="user_idx" value="<?=$_SESSION['USER_IDX']?>" />
				<fieldset>
					<legend>Sign Up Form</legend>
					<p>
						Registration will be done after adding additional information 
					</p>
					<p>
						<span class='user-type'><?=$_SESSION['USER_TYPE']?></span>Now your account is registered
						<div class='user-profile'>
							<img src="<?=$_SESSION['USER_IMAGE']?>" alt="user image" />
							[<span class='user-name'><?=$_SESSION['USER_NAME']?></span>]
						</div>
					</p>
					<p>
						<input type="checkbox" id="policyAgreeConfirm" name="policyAgreeConfirm" value='y' /><label for="policyAgreeConfirm" class="checkboxText"><a href="#">
						I agree to terms of use</a>,<a href="#">and privacy policies.</a></label>
					</p>
					<p>
						<input type="button" class="input-button btn-cancel" value="Cancel" /><input type="submit" class="input-button btn-ok" value="Sign Up" />
					</p>
				</fieldset>
				</form>
			</div>
			<div id="loginArea">
				<h2>Sign In</h2>
				<form id="loginForm" name="loginForm" method="post" action="member/login.php">
				<fieldset>
					<legend>Sign In Form</legend>
					<p>
						<label for="loginEmail">E-mail</label><input type="text" id="loginEmail" name="loginEmail" /><span class="check"></span>
						<span class="valid"></span>
					</p>
					<p>
						<label for="loginPasswd">password</label><input type="password" id="loginPasswd" name="loginPasswd" /> <span class="check"></span>
						<span class="valid"></span>
					</p>
					<p>
						<input type="checkbox" id="autoLogin" name="autoLogin" value='y' /><label for="autoLogin">Check 'remember me' for automatic sign in</label>
					</p>
					<p>
						<input type="button" class="input-button btn-cancel" value="Cancel" /><input type="submit" class="input-button" value="OK" />
					</p>
					<p>
						<a href="/member/oauth/facebook/redirect.php"><img src="http://static.ak.fbcdn.net/rsrc.php/zB6N8/hash/4li2k73z.gif" alt="Sign in with Facebook" title="Sign in with Facebook" /></a>
						<a href="/member/oauth/twitter/redirect.php"><img src="/member/oauth/twitter/images/darker.png" alt="Sign in with Twitter" title="Sign in with Twitter" /></a>
					</p>
					<p>
						You can enjoy more useful services by Registration <a href="#" class="go-join">Go to Registration</a>
					</p>
					<p>
						<a href="#" class="find-passwd">Find my password</a>
					</p>
				</fieldset>
				</form>
			</div>
			<div id="findPasswdArea">
				<h2>Find my password</h2>
				<form id="findPasswdForm" name="findPasswdForm" method="post" action="">
				<fieldset>
					<legend>Find my password Form</legend>
					<p>
						<span class="message">
							Insert another available mail account you inserted in</br> registration form for sending temporary password for sign in
						</span>
					</p>
					<p>
						<label for="passwdEmail">E-mail</label><input type="text" id="passwdEmail" name="passwdEmail" /><span class="check"></span>
						<span class="valid"></span>
					</p>
					<p>
						<input type="button" class="input-button btn-cancel" value="Cancel" /><input type="submit" class="input-button btn-ok" value="OK"  />
					</p>
				</fieldset>
				</form>
			</div>
			<div id="favoriteArea">
				<h2>Add To Favorite</h2>
				<form id="favoriteForm" name="favoriteForm" method="post" action="">
				<fieldset>
					<legend>Add To Favorite Form</legend>
					<p>
						<span class="message"></span>
					</p>
					<p>
						검색어 : <span class="search-text"><?=$searchWord?></span>
					</p>
					<p>
						<label for="favoriteTags">Tags</label><input type="text" id="favoriteTags" name="favoriteTags" /><span class="check"></span>
						<span class="desc">divide your tags with comma(i.e. flower, start, )</span>
					</p>
					<p>
						<input type="button" class="input-button btn-cancel" value="Cancel" /><input type="button" class="input-button btn-ok" value="OK"  />
					</p>
				</fieldset>
				</form>
			</div>
