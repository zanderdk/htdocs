<?php include("functions.php");
	connect();
	session_start();

if($_POST['search']){

   header('Location: /opskrifter?search='.$_POST['search'].'');
}

if($_GET['a'] == 'logout'){
	session_unset('admin');
}
	
	//On login
	$username = "Bugle";
	$password = "M15a42012";
	
	if($_POST['login_username'] || $_POST['login_password']) {
		
		//Check tomme felter
		if($_POST['login_username'] && $_POST['login_password']) {
		
		
			// Check om brugeren er i databasen
			if($_POST['login_username'] == $username && $_POST['login_password'] == $password){
			
				$_SESSION['admin'] = 1;
				
			
			} else {
				$ErrorOnLogin = "Forkert login";
			}
			
		} else {
		
			$ErrorOnLogin = "Udfyld begge felter";
		
		}
	
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>Bundgaards Garn</title>

<!--Tags-->
<meta name="keywords" content="Garn, Mayflower, Strikkegarn, Håndstrik, Uld, Mohair, Hør, Bomduld, Opskrifter, Strikkeopskrifter, Strik, Bundgaard, Billigt garn, Salg af garn, Bubmo strømpegarn, strømpegarn, restgarn, Brønderslev, Nordjylland, Bumbo, Bumbo me, Bumbo you, Bumbo MIX, Garntilbud, billigt, Linate, Online, Udsalg, Polyester, Akryl, Fancy, Rest garn, Farveprøver, Strikkeopskrifter, Alpakka royal, Classic tweed, COSMO, Cotton 8, Cotton 8 Merceriseret, Cotton 8-8 Big, DAISY, DAISY PRINT, Divine, Easy Care, Easy Care Big, Easy Care Classic, Easy Care Classic, Easy Care Classic, Hit Ta-too, Italy, Kid Silk, Life, Mayflower 1, Mayflower 1, Mayflower 3, Mayflower strømpegarn Print, Merino royal, Sanzelize, Silk Royal, Sunset, Symfonie, CUBICS, NOVA Metal, KARBONZ, Nova Cubics, WAVES, Dreamz, Faste Rundpinde, Hæklenål enkelt ende, Hæklenål træ dobbelt ende, Jumperpinde, Afghansk/Tunesisk Hæklenål til wirer, Strikkepindesæt, Udskiftsbare Strikkepinde, Strømpepinde">
<meta name="description" content="Bundgaardsgarn.dk - Vi har et af de største udvalg i kvalitets-strikkegarn: Garn – Restgarn - Strikkepinde – Hæklepinde – Strikkeopskrifter – Mayflower – Bumbo – KnitPro">
<meta name="author" content="Bundgaards Garn">

<!-- Include style sheets -->
<link href="<?php if(isIndex()){ echo'';} else { echo '/..';}?>/body/styles/reset.css" rel="stylesheet" type="text/css">
<link href="<?php if(isIndex()){ echo'';} else { echo '/..';}?>/body/styles/grid.css" rel="stylesheet" type="text/css">
<link href="<?php if(isIndex()){ echo'';} else { echo '/..';}?>/body/styles/master.css" rel="stylesheet" type="text/css">
<link href="/body/styles/<?php if(isIndex()){ echo'index';} else {echo getSideName();}?>.css" rel="stylesheet" type="text/css">

<!-- Fav icon -->

<link rel="icon" href="/body/_gfx/fav.ico" type="image/ico"/>

<!-- Charset -->
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

<!-- GoogleFont -->
<link href='http://fonts.googleapis.com/css?family=Signika+Negative:300,400,600,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>

<!-- TOGGLE VISIBILITY -->
<script type="text/javascript">
    function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'table-row')
          e.style.display = 'none';
       else
          e.style.display = 'table-row';
    }
</script>

<!-- FACEBOOK LIKE -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/da_DK/all.js#xfbml=1&appId=71494918831";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<!-- GOOGLE ANALYTICS -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-33288293-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
  
  function clearText(field){
   
      if (field.defaultValue == field.value) field.value = '';
      else if (field.value == '') field.value = field.defaultValue;
   
  }

</script>

</head>

	<body>
	
	<div class="header_wrapper">
	
		<div class="header container_12 clearfix">
	
		<a href="http://bundgaardsgarn.dk"><h1>Bundgaards Garn</h1></a>
		<ul>
			<li><a href="<?php if(!isIndex()){echo'/';}?>kontakt">Kontakt</a></li>
			<li><a href="<?php if(!isIndex()){echo'/';}?>betingelser">Betingelser</a></li>
			<li><a href="<?php if(!isIndex()){echo'/';}?>om">Om os</a></li>
			<li><a href="<?php if(!isIndex()){echo'/';}?>opskrifter/kategorier/">Opskrifter</a></li>
			<li><a href="<?php if(!isIndex()){echo'/';}?>kontakt/?a=vare">Farveprøver</a></li>
		</ul>
		<a href="http://mayflower.dk/"><h2>Mayflower</h2></a>
		
		</div>
		
	</div>
	
	

	<div class="container_12 clearfix">
	
		<div class="menu grid_2">

		<!-- #################################################### COPY THIS #################################################### -->
		<table>
		
		<?php
		
		
		$load_type = sql("SELECT * FROM category WHERE istype = 1 ORDER BY type");
		
		$counter = 0;
		$latest_type = 0;
		$type_names = array(1 => "Garn", 2 => "Strikkepinde", 3 =>"Restgarn");

		while($lt = mysql_fetch_array($load_type)){

		$counter++;

		if($lt['type'] != $latest_type)
		{
			$latest_type = $lt['type'];
			echo'
			<tr class="title">
				<td>'.$type_names[$lt['type']].'</td>
			</tr>
			';
		}
		
		echo'
			<tr onclick="toggle_visibility('. $lt['id'] .')" class="head">
				<td>'. $lt['name'] .'</td>
			</tr>
			<tr ';
			
			if($_GET['t'] == $lt['id']){
			
				echo'style="display: table-row; ';
			
			}
			
			echo' id="'. $lt['id'] .'" class="body">
			<td>
				<ul>';
					$load_subtype = sql("SELECT * FROM category WHERE istype = '0' AND parrent = '". $lt['id'] ."' ORDER BY name");
					while($lst = mysql_fetch_array($load_subtype)){
					echo'
					<li><a href="';
					
					if(isIndex()){ echo'';} else { echo '/..';}
					
					echo'?t='. $lt['id'] .'&st='. $lst['id'] .'">'. $lst['name'] .'</a></li>
					';
					}
				echo'</ul>
			</td>
			</tr>
			';
			}
			echo'</table>';
			
			
			
			?>
			</table>
			<!-- ################################################################################################################### -->

			
			   
			   
			   <table>
				<tr class="title">
					<td>Opskrifter</td>
				</tr>
				<tr class="recipe">
					<td>
					<?php 
					echo'
					<form method="post" action="">
					   <input type="text" name="search" value="Søg her" onFocus="clearText(this)" onBlur="clearText(this)"/></br>
					   <input class="button" type="submit" value="Søg"/>				   
					</form>
					';
					 ?>
					 <a href="/opskrifter/kategorier/"><p>Se kategorier</p></a>
					</td>
				</tr>
				</table>
			
			
			
			<table>
			
			<tr class="title">
			   <td>Indkøbskurv</td>
			</tr>
			<tr class="cards">
			   <td>
			   <p>Vi godtager</p>
			   <img src="/body/_gfx/icons/visa.png">
			   <img src="/body/_gfx/icons/visa_alt.png">
			   </br>
			   <img src="/body/_gfx/icons/mastercard.png">
			   <img src="/body/_gfx/icons/amex.png">
			   <p><a style="font-size: 10pt; color: #900000;" href="/guides/">Guide til indkøb</a></p>
			   </td>
			</tr>
			<tr class="cart">
				<td><form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
				
				<!-- Identify your business so that you can collect the payments. -->
				<input type="hidden" name="business" value="bugledk@gmail.com">
				
				<!-- Specify a PayPal Shopping Cart View Cart button. -->
				<input type="hidden" name="cmd" value="_cart">
				<input type="hidden" name="display" value="1">
				
				<!-- Display the View Cart button. -->
				<input type="submit" class="button" name="submit" border="0" src="" value="Se Indkøbskurv">
				</form> </td>
			</tr>

			
		</table>
		
		<?php 
	
		if($_SESSION['admin'] == 1){
		echo'
		<table>
		   <tr class="title">
			   <td>Admin</td>
		   </tr>
		   <tr class="head">
		      <td><a href="../admin/">Kontrolpanel</a></td>
		   </tr>
		   <tr class="head">
		      <td><a href="?a=logout">Log ud</a></td>
		   </tr>
		</table>
		';
		}
		
		 ?>
		</div>
		
		<div class="container grid_10">
			<div class="content clearfix">