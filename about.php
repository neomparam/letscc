<?
	require_once('comm/inc.session.php');
?>

<? require_once 'comm/inc.top.php';?>

<style type="text/css">
#searchType li a{ padding-left:16px; padding-right:2px;}
#searchType .cc-license { letter-spacing:-2px; }

#searchResult { padding-top:0px; }
#searchResult h2 { display:block; padding:33px 0 20px 33px; }

#introArea { position:relative; width:718px; padding-left:69px; overflow:hidden; }


#introArea dt{
	margin:10px 0 12px;
	padding-left:21px;
	background:url(i/ico_q.gif) 0 2px no-repeat;
	font-weight:bold;
	font-size:16px;
	letter-spacing:-1px;
}
#introArea dd { padding:15px 0 15px 20px;  line-height:19px; font-size:12px; color:#6e6e6e; font-family:"돋움"; }
#introArea .img-1 {
	float:left;
	width:208px;
	height:105px;
	padding:16px 0 0 5px;
}
#introArea .img-2 {
	float:right;
	position:relative;
	width:240px;
	height:230px;
	padding-left:90px;
}
#introArea .img-2 img {
	position:absolute;
	top:-10px;
}
#introArea .img-3 { padding-top:30px; }
#introArea .img-4 { padding:10px 0; }
#introArea dd ol { padding-left:15px; }
#introArea dd ol li {  list-style-type:decimal; font-weight:bold; font-size:12px; color:#373737; font-family:"굴림"; }
#introArea dd ol li span { color:#8f8f8f; }
</style>

<script type="text/javascript">
//<!CDATA[
//]]>
</script>
</head>
<body>
<?require_once 'comm/inc.go_detail.php';?>
<div id="wrap" >
<?require_once 'comm/inc.header.php';?>

	<div id="body" class="all">
		<?require_once 'comm/inc.leftmenu.pages.php';?>
		<script type="text/javascript">
		<!--
			$('.cc-intro').addClass('selected');
		//-->
		</script>
		<div id="searchResult">
		  <h2><img src="i/title_cc_intro.gif" alt="about LetsCC" title="about LetsCC"/></h2>
			<dl id="introArea">
		    <dd>
				  <div><img src="i/intro_1.gif" alt="What is Let's CC" title="What is Let's CC" /></div>
<p><br>
  <strong>Let’s CC </strong>help you easily find relevant CC-licensed contents.</p>
			  </dd>

			  <dt> What is Let's CC</dt>
		    <dd>
					<div class="img-2"><img src="i/intro_2.gif" alt="Let's CC" title="Let's CC" /></div>
<p><strong>Let’s CC</strong> is not a search engine, but rather offers quick and easy access to search services provided by other independent organizations from one single page just like <a href="http://search.creativecommons.org" target="_blank">search.creativecommons.org</a>. CC Korea has no control over the results that are returned and makes no warranties whatsoever regarding the results. If you are in doubt you should contact the copyright holder directly, or try to contact the site where you found the contents.<br>
<br></p>
<p>
<strong>Let's CC</strong> uses  APIs provided by Fiickr, Jamendo, ccMixter, Youtube and Slideshare, so you can find CC-licensed images, sounds, videos and docs at once with just one click. You can also save your favorite works and add tags to them. They are stored in <span style="font-style:italic">My Favorites</span> folder, so you can see them anytime you want. On <span style="font-style:italic">My Favorites</span> page, you can manage previously marked as favorite contents  and add tags to them. Moreover, Let's CC contents that have been marked as favorite will appear at the top of the search results so that users will be able to find more relevant contents easily.</p> 
</dd>
              <dt> What is Creative Commons License</dt>
<dd>
                  <div style="position:relative; float:left; width:210px; height:100px"><img src="i/creativeC2.png" alt="Creative Commons" title="Creative Commons" /></div>
<p>CCL (Creative Commons License) helps creators retain copyright while allowing others to copy, distribute or make uses of their work under certain specified conditions. 
Please click <a href="http://creativecommons.org/licenses/" target="_blank">here</a> to learn more about CCL.<br>
<br>
<br>
</p>
              </dd>
			  <dt> Who we are</dt>
			  <dd>
                <p>
 - Creative Commons Korea (CC Korea) is a nonprofit organization devoted to expanding the use of Creative Commons Licenses and to helping people better understand what copyright is and how it works for the enhancement of open culture in the digital era. 
<br>
<br></p>
<p> - Volunteers are the core asset of CC Korea. With their support and assistance, we have conducted various experiments and projects - e.g. public campaigns, workshops, seminars, etc. -  in the areas of art, scholarship, public administration, education, media and others to spread the value of OPEN and SHARE to the public. <br>
<br></p>
<p> - As of 2011, more than 70 countries use CCL in their local languages. CCL was originally launched in the U.S. in 2002 and since then it has been fast spread throughout the world and has been widely used. CC affiliates including CC Korea have grown to form a global network of communities thanks to consistent international collaboration and exchange of ideas. <br><a href="http://www.cckorea.org" target="_blank">Please click here to learn more about CC Korea.</a></p>
</dd>
				<dt> Why CCL Matters</dt>
			  <dd>
<p> - Originally, copyright law was intended to protect the interests of authors and creators and to give them incentives while contributing to cultural development of the society as a whole. But in the late 20th century, the emergence of information and communications technology (ICT) has changed this approach - it has blurred the boundaries between users and creators, allowed ordinary people to take a more active role as producers of creative works rather than being mere passive users.The new trend has  brought greater innovation and creativity into the world, especially through the web. <br>
  To make it successful, there is a growing need for the copyright regime to reflect the new realities and take a different approach.<br>
<br></p>
<p>
 - In this sense, Creative Commons License  was developed  to enable people to open and share their work while retaining their copyrights, within the boundaries of copyright law, and hence to contribute to more creativity in the world. CCL is one of the internationally recognizable copyright licenses along with GNU General Public License (GPL).<br>
<br></p>
<p>
- With a Creative Commons License, people can allow others to easily access and use their creations for wider distribution and for more use of those works for derivative works. We believe this virtuous cycle could benefit all stakeholders through an open system of sharing and creating knowledge, ideas and innovations.</p>
			  </dd>
			</dl>
		</div>
	</div>
<?require_once 'comm/inc.footer.php';?>
</div>
<?require_once 'comm/inc.fixed.php';?>
</body>
</html>
