<?php 
// ###################################################################################################################
include('../body/header.php');

if($_SESSION['admin'] == 1){

	// REDIGER PRODUKTER

	if($_GET['p'] == 1){
	
	echo'
	<div class="admin">
		<form method="post" action="?p=3">
		<h1>Rediger Produkter<em><input type="submit" name="submitEditProduct" id="button" value="Gem"/></em></h1>
		
		
		<ul>';
		
		$chp = array();
		$sn = array("Ikke på lager", "Begrænset lager", "På lager", "Mange på lager");
		
	$load_products = sql("SELECT * FROM products ORDER BY 'name' DESC");
	while($lp = mysql_fetch_array($load_products)){
		
		
		$chp[$lp['id']] = "unchecked";
		
		
	echo'
		<li class="clearfix">
				<p>'.$lp['id'].'</p>
				<div>
					<input id="name" name="name'. $lp['id'] .'" type="text" value="'. $lp['name'] .'"/><br/>
					<input id="price" name="price'. $lp['id'] .'" type="text" value="'. $lp['price'] .'"/><em> DKK</em>
				</div>
				<div>
					<textarea  name="description'. $lp['id'] .'">'. $lp['description'] .'</textarea>
				</div>
				<div>
					<select name="item'. $lp['id'] .'">';
					$load_category = sql("SELECT * FROM category WHERE istype = 0 AND id =". $lp['item'] ." ORDER BY 'name' DESC");
					while($lc = mysql_fetch_array($load_category)){
					
					echo'<option value="'. $lc['name'] .'">'. $lc['name'] .'</option>';					
					}
					
					
					$load_category = sql("SELECT * FROM category WHERE istype = 0 AND id !=". $lp['item'] ." ORDER BY 'name' DESC");
					while($lc = mysql_fetch_array($load_category)){				
						echo'<option value="'. $lc['name'] .'">'. $lc['name'] .'</option>';					
					}
					
					echo'
					</select>
					<select name="stock'. $lp['id']. '">';		
					
					 if($lp['stock'] == 1){
						
						echo'
						<option value="1">Begrænset lager</option>
						<option value="3">Mange på lager</option>
						<option value="2">På lager</option>
						<option value="0">Ikke på lager</option>
						
						';
					
					}
					
					else if($lp['stock'] == 2){
					
						echo'
						<option value="2">På lager</option>
						<option value="1">Begrænset lager</option>
						<option value="3">Mange på lager</option>
						<option value="0">Ikke på lager</option>
						';
					
					}
					
					else if($lp['stock'] == 0) {
					
						echo'
						<option value="3">Mange på lager</option>
						<option value="2">På lager</option>
						<option value="1">Begrænset lager</option>
						<option value="0">Ikke på lager</option>
						';
					
					}
					
					else if($lp['stock'] == 3) {
					
						echo'
						<option value="3">Mange på lager</option>
						<option value="2">På lager</option>
						<option value="1">Begrænset lager</option>
						<option value="0">Ikke på lager</option>
						';
					}
					
					echo'	
					</select>	
					</div>
				<div class="last">
					<input type="checkbox" name="ch' . $lp['id'] . '" value="">
					<em>Slet</em>
				</div>';
	echo'			
		</li>
		
	';
	}
	
	echo'
		</ul>
		</form>
	</div>
	</div>';
	
	}
	
	// REDIGER KATEGORIER
	
	else if($_GET['p'] == 2) {
		echo'
		<div class="admin">
			<form method="post" action="?p=3">
			<h1>Rediger Kategorier<em><input type="submit" name="submitEditCategory" id="button" value="Gem"/></em></h1>
			
			
			<ul>';
			
			$chc = array();
			
		$load_category = sql("SELECT * FROM category ORDER BY 'name' DESC");
		while($lc = mysql_fetch_array($load_category)){
			
			
			$chc[$lc['id']] = "unchecked";
			
			
		echo'
			<li class="clearfix">
					<div>
						<input id="price" type="text" value="'. $lc['name'] .'" name="name'. $lc['id'] .'">
					</div>';
					
					
					if($lc['istype'] == 0){
					
						
						$load_categoryparrents = sql("SELECT * FROM category WHERE id = '". $lc['parrent'] ."' AND istype = 1 ORDER BY 'name'");
						$lcp = mysql_fetch_array($load_categoryparrents);
	
						echo'
						<div>
						<select name="parrent'. $lc['id'] .'">
							<option value="'. $lcp['id'] .'">'. $lcp['name'] .'</option>
						';
							
						
						$load_categorynotparrents = sql("SELECT * FROM category WHERE id != '". $lc['parrent'] ."' AND istype = 1 ORDER BY 'name' DESC");
						while($lcnp = mysql_fetch_array($load_categorynotparrents)){
						
							echo'<option value="'. $lcnp['id'] .'">'. $lcnp['name'] .'</option>';
						
						}
					
							
						echo'
						</select>
						</div>
						';
					
					} else {
						echo'
						<div>
							<select name="type'. $lc['id'] .'">
								<option value="1">Garn</option>
								<option value="2">Strikkepinde</option>
								<option value="3">Restgarn</option>
							</select>
						</div>
						';
					}
					
				echo'	
					<div class="last">
						<input type="checkbox" name="ch' . $lc['id'] . '" value="net">
						<em>Slet</em>
					</div>';
		echo'			
			</li>
			
		';
		}
		
		echo'
			</ul>
			</form>
		</div>
		</div>';
		
	
	}
	
	//GEM
	
	else if($_GET['p'] == 3){
	
	echo'
	<div class="admin">
	<h1>Gemt</h1>
	</div>
	';
	
	
	//EDIT CATEGORIES
	if($_POST['submitEditCategory']){
	$load_category = sql("SELECT * FROM category ORDER BY 'name' DESC");
	while($lc = mysql_fetch_array($load_category)){
		
		if(isset($_POST['ch' . $lc['id']])){
			
			$chc[$lc['id']] = 'checked';
			
			if($chc[$lc['id']] == 'checked'){
				
				
				sql("DELETE FROM category WHERE id='" . $lc['id'] ."'");
				
				if($lc['istype'] == 1){
				
					sql("DELETE FROM category WHERE parrent='" . $lc['id'] ."'");
				
				}
			
			}
			
		}
		
		$name = $_POST['name' . $lc['id']];
		$type = $_POST['type' . $lc['id']];
		$parrent = $_POST['parrent' . $lc['id']];
		
		sql("UPDATE category SET name='" . $name . "', type='" . $type . "', parrent='" . $parrent . "' WHERE id='" . $lc['id'] ."'") or die(mysql_error());
		
	}
	
	echo'</br><a href="?p=2">Tilbage til kategorier</a>';
	
	}
	
	
	//CREATE CATEGORIES
	if($_POST['submitCreateCategory']){
		
		$category = $_POST['categorynew'];
		$name = $_POST['namenew'];
		$type = $_POST['typenew'];
			
		if($category == 'none'){
		
			sql("INSERT INTO category (istype, name, type) VALUES ('1', '$name', '$type')");
		
		} else {
		
			sql("INSERT INTO category (parrent, name) VALUES ('$category', '$name')");
		
		}
		
		echo'</br><a href="?p=5">Opret flere kategorier</a>';
		echo'</br><a href="?p=2">Rediger kategorier</a>';
	
	}
	
	
	//EDIT PRODUCTS
	if($_POST['submitEditProduct']){
	$load_products = sql("SELECT * FROM products ORDER BY 'name' DESC");
	while($lp = mysql_fetch_array($load_products)){
		
		if(isset($_POST['ch' . $lp['id']])){
			sql("DELETE FROM products WHERE id='" . $lp['id'] ."'");
		} else {
		
		$name = $_POST['name' . $lp['id']];
		$price = $_POST['price' . $lp['id']];
		$description = $_POST['description' . $lp['id']];
		$item = $_POST['item' . $lp['id']];
		$stock = $_POST['stock' . $lp['id']];
		
		$load_category = sql("SELECT * FROM category WHERE name = '".$item."'");
		$lc = mysql_fetch_array($load_category);
		
		$item = $lc['id'];
		
		
		sql("UPDATE products SET name='" . $name . "', price='" . $price . "', description='" . $description . "', item='" . $item . "', stock='". $stock ."' WHERE id='" . $lp['id'] ."'") or die(mysql_error());

		}
	}
	
	echo'</br><a href="?p=1">Tilbage til Produkter</a>';
	}
	
	//CREATE PRODUCTS
	if($_POST['submitCreateProduct']){
	
		//FILE
		$paths = 'public_html/body/_gfx/products/';
		$filep = $_FILES['filenew']['tmp_name'];
		$ftp_server = 'linux27.unoeuro.com';
		$ftp_user_name = 'bundgaardsgarn.dk';
		$ftp_user_pass = 'M15a42012';
		$filetype = $_FILES["filenew"]["type"];
		$filesize = $_FILES["filenew"]["size"];
		
		
		//DATA
		$stock = $_POST['stocknew'];
		$price = $_POST['pricenew'];
		$item = $_POST['itemnew'];
		$name = $_POST['namenew'];
		$description = $_POST['descriptionnew'];
		
		
		if($price && $item && $name && $description){
		
			if($_FILES['filenew']){
			
				if($filetype == 'image/jpeg'){
				
					if($filesize < 1500000){
						
							sql("INSERT INTO products (price, item, name, description, stock) VALUES ('$price', '$item', '$name', '$description', '$stock')");
							
							//Saves the ID of the post
							$id_of_table=mysql_insert_id();
							
							$imgName = $id_of_table . '.png';
							
							// set up a connection to ftp server
							$conn_id = ftp_connect($ftp_server);
							
							// login with username and password
							$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
							
							// check connection and login result
							if ((!$conn_id) || (!$login_result)) {
							       echo "FTP connection has encountered an error!";
							       echo "Attempted to connect to $ftp_server for user $ftp_user_name....";
							       exit;
							   }
							
							// upload the file to the path specified
							$upload = ftp_put($conn_id, $paths.'/'.$imgName, $filep, FTP_BINARY);
							
							// check the upload status
							if (!$upload) {
							       echo "FTP upload has encountered an error!";
							   }
						
						echo'</br><a href="?p=1">Rediger Produkter</a>';
						echo'</br><a href="?p=4">Opret endnu et Produkt</a>';
						
					} else {
						echo'Filen fylder mere end 50kB';
						echo'</br><a href="?p=4">Prøv Igen</a>';
					}
				
				} else {
				
					echo'Billedet skal være i JPG-format';
					echo 'BILLEDETYPEN: ' . $filetype ;
					echo'</br><a href="?p=4">Prøv Igen</a>';
				
				}
			
			} else {
			
				echo'Du SKAL uploade et billede';
				echo'</br><a href="?p=4">Prøv Igen</a>';
			
			}
		
		} else {
		
			echo'Du skal udfylde alle felter';
			echo'</br><a href="?p=4">Prøv Igen</a>';
		
		}
	
	}
	
	//CREATE NEW RECIPE
	
	if($_POST['submitCreateNewRecipe']) {
	   
   	//FILES
   	$ftp_server = 'linux27.unoeuro.com';
   	$ftp_user_name = 'bundgaardsgarn.dk';
   	$ftp_user_pass = 'M15a42012';
   	
   	//PDF
   	$pdfPaths = 'public_html/body/pdf/';
   	$pdfFileP = $_FILES['pdfNewRecipe']['tmp_name'];
   	$pdfFileType = $_FILES["pdfNewRecipe"]["type"];
   	$pdfFileSize = $_FILES["pdfNewRecipe"]["size"];
   	
   	//PIC
   	$picPaths = 'public_html/body/_gfx/recipe/';
   	$picFileP = $_FILES['picNewRecipe']['tmp_name'];
   	$picFileType = $_FILES["picNewRecipe"]["type"];
   	$picFileSize = $_FILES["picNewRecipe"]["size"];	
   	
   	//DATA
   	$recipeName = $_POST['nameNewRecipe'];
   	$recipeDescription = $_POST['descriptionNewRecipe'];
   	$recipeCategory = $_POST['categoryNewRecipe'];
   	
   	echo $recipeName ."OG". $recipeDescription ."OG". $recipeCategory;
   	
   	
   	if($recipeName && $recipeDescription && $recipeCategory){
   	   echo'SAGER!';
   		if($_FILES['pdfNewRecipe'] && $_FILES['picNewRecipe']){	
   		
   			if($pdfFileType == 'application/pdf' && $picFileType == 'image/jpeg'){
   			
   				if($pdfFileSize < 3000000 && $picFileSize < 50000){
   					   
   					   echo'hej ';
   					   
   						sql("INSERT INTO `recipies` (name, description,category) VALUES ('$recipeName', '$recipeDescription','$recipeCategory')");
   						
   						//Saves the ID of the post
   						$id_of_table = mysql_insert_id();
   						
   						$pdfName = $id_of_table . '.pdf';
   						$picName = $id_of_table . '.png';
   						
   						
   						// set up a connection to ftp server
   						$conn_id = ftp_connect($ftp_server);
   						
   						// login with username and password
   						$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
   						
   						// check connection and login result
   						if ((!$conn_id) || (!$login_result)) {
   						       echo "FTP connection has encountered an error!";
   						       echo "Attempted to connect to $ftp_server for user $ftp_user_name....";
   						       exit;
   						   }
   						
   						// upload the file to the path specified
   						$upload = ftp_put($conn_id, $pdfPaths.'/'.$pdfName, $pdfFileP, FTP_BINARY);
   						$upload = ftp_put($conn_id, $picPaths.'/'.$picName, $picFileP, FTP_BINARY);

   						
   						// check the upload status
   						if (!$upload) {
   						       echo "FTP upload has encountered an error!";
   						   }
   					
   					echo'</br><a href="?p=7">Rediger Produkter</a>';
   					echo'</br><a href="?p=6">Opret endnu et Produkt</a>';
   					
   				} else {
   					echo'PDF\'en fylder mere end 100kB og eller Billedet fylder mere end 50kB';
   					echo'</br><a href="?p=6">Prøv Igen</a>';
   				}
   			
   			} else {
   			   
   			   echo'Du kan kun uploade en PDF som opskrift';
   				echo'Billedet skal være i JPG-format';
   				echo'</br><a href="?p=6">Prøv Igen</a>';
   			
   			}
   		
   		} else {
   		
   			echo'Du SKAL uploade et billede';
   			echo'</br><a href="?p=4">Prøv Igen</a>';
   		
   		}
   	
   	}
	
	}
	
	if($_POST['submitEditRecipe']){
	
      $load_recipies = sql("SELECT * FROM recipies ORDER BY 'name' DESC");
      while($lr = mysql_fetch_array($load_recipies)){
      	
      	if(isset($_POST['ch' . $lr['id']])){
      		
      		$chc[$lr['id']] = 'checked';
      		
      		if($chc[$lr['id']] == 'checked'){
      			
      			sql("DELETE FROM recipies WHERE id='" . $lr['id'] ."'");
      		
      		}
      		
      	}
      	
      	$name = $_POST['nameRecipe' . $lr['id']];
      	$description = $_POST['descriptionRecipe' . $lr['id']];
      	$category = $_POST['category' .$lr['id']];
      	
      	sql("UPDATE recipies SET name='" . $name . "', description='" . $description . "', category = '".$category."' WHERE id='" . $lr['id'] ."'") or die(mysql_error());
      	
      }
	}
	
	if($_POST['submitCreateNews']) {
		
	   	//FILES
	   	$ftp_server = 'linux27.unoeuro.com';
	   	$ftp_user_name = 'bundgaardsgarn.dk';
	   	$ftp_user_pass = 'M15a42012';
	   	
	   	//PIC
	   	$picPaths = 'public_html/body/_gfx/news/';
	   	$picFileP = $_FILES['pic']['tmp_name'];
	   	$picFileType = $_FILES["pic"]["type"];
	   	$picFileSize = $_FILES["pic"]["size"];	
	   	
	   	//DATA
	   	$name = $_POST['name'];
	   	$link = $_POST['link'];
	   	
	   	if($name && $link){
	   	
	   		if($_FILES['pic']){	
	   		
	   			if($picFileType == 'image/jpeg'){
	   			
	   				if($picFileSize < 50000){
	   					   
	   						sql("INSERT INTO `news` (name, link) VALUES ('$name', '$link')");
	   						
	   						//Saves the ID of the post
	   						$id_of_table = mysql_insert_id();
	   						$picName = $id_of_table . '.png';
	   						
	   						
	   						// set up a connection to ftp server
	   						$conn_id = ftp_connect($ftp_server);
	   						
	   						// login with username and password
	   						$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
	   						
	   						// check connection and login result
	   						if ((!$conn_id) || (!$login_result)) {
	   						       echo "FTP connection has encountered an error!";
	   						       echo "Attempted to connect to $ftp_server for user $ftp_user_name....";
	   						       exit;
	   						   }
	   						
	   						// upload the file to the path specified
	   						$upload = ftp_put($conn_id, $picPaths.'/'.$picName, $picFileP, FTP_BINARY);
	
	   						
	   						// check the upload status
	   						if (!$upload) {
	   						       echo "FTP upload has encountered an error!";
	   						   }
	   					
	   					echo'</br><a href="?p=9">Rediger Nyheder</a>';
	   					echo'</br><a href="?p=8">Opret nyheder</a>';
	   					
	   				} else {
	   					echo'Billedet fylder mere end 50kB';
	   					echo'</br><a href="?p=8">Prøv Igen</a>';
	   				}
	   			
	   			} else {
	   				echo'Billedet skal være i JPG-format';
	   				echo'</br><a href="?p=8">Prøv Igen</a>';
	   			
	   			}
	   		
	   		} else {
	   		
	   			echo'Du SKAL uploade et billede';
	   			echo'</br><a href="?p=8">Prøv Igen</a>';
	   		
	   		}
	   	
	   	}
		
		}
		
		if($_POST['submitEditNews']){
		
		   $load_news = sql("SELECT * FROM news ORDER BY 'name' DESC");
		   while($ln = mysql_fetch_array($load_news)){
		   	
		   	if(isset($_POST['ch' . $ln['id']])){
		   		
		   		$chc[$ln['id']] = 'checked';
		   		
		   		if($chc[$ln['id']] == 'checked'){
		   			
		   			sql("DELETE FROM news WHERE id='" . $ln['id'] ."'");
		   		
		   		}
		   		
		   	}
		   	
		   	$name = $_POST['name' . $ln['id']];
		   	$link = $_POST['link' . $ln['id']];
		   	
		   	sql("UPDATE news SET name='" . $name . "', link='" . $link . "' WHERE id='" . $ln['id'] ."'") or die(mysql_error());
		   	
		   }
		}
		
		
	
	
	
	echo'</div>';
}
	
	//OPRET PRODUKT
	
	else if($_GET['p'] == 4){
	
echo'
<div class="admin">
	<form method="post" action="?p=3" enctype="multipart/form-data">
	<h1>Opret Produkt<em><input type="submit" name="submitCreateProduct" id="button" value="Gem"/></em></h1>
		<ul>
			<li class="clearfix">
				<div>
					<input id="name" name="namenew" type="text" value="" placeholder="Navn" /><br/>
					<input id="price" name="pricenew" type="text" value="" placeholder="PRIS" /><em> DKK</em>
				</div>
				<div>
					<textarea  name="descriptionnew"></textarea>
				</div>
				<div>
					<select name="itemnew">';
					
					$load_category = sql("SELECT * FROM category WHERE istype= '0' ORDER BY 'name' DESC");
					while($lc = mysql_fetch_array($load_category)){
					
						echo '<option value="'. $lc['id'] .'">'. $lc['name'] .'</option>';
					
					}

				echo' 
					</select>
				</div>
				<div class="last">
					<input name="filenew" type="file" size="50">
					<select name="stocknew">
						<option value="3">Mange på lager</option>
						<option value="2">På lager</option>
						<option value="1">Begrænset lager</option>
						<option value="0">Ikke på lager</option>
					</select>
				</div>
			</li>
		</ul>
	</form>
</div>
</div>';
		
		
	
	}
	
	//OPRET KATEGORI
	
	else if($_GET['p'] == 5){
	
echo'
<div class="admin">
	<form method="post" action="?p=3">
		<h1>Opret Kategori<em><input type="submit" name="submitCreateCategory" id="button" value="Gem"/></em></h1>
		<ul>
			<li class="clearfix">
				<div>
					<select name="categorynew">
						<option value="none">Hoved Kategori</option>';
						
						$load_category = sql("SELECT * FROM category WHERE istype= '1' ORDER BY 'name' DESC");
						while($lc = mysql_fetch_array($load_category)){
						
						echo '<option value="'. $lc['id'] .'">Under '. $lc['name'] .'['.$lc['type'].']</option>';
						
						}		
						
						echo'
					</select>
				</div>
				<div>
					<input id="name" name="namenew" type="text" placeholder="Navn" />
					<select name="typenew">
						<option value="1">Garn</option>
						<option value="2">Strikkepinde</option>
						<option value="3">Restgarn</option>
					</select>
				</div>
			</li>
		</ul>
	</form>
</div>
</div>
';
	
	} 

	// CREATE RECIPI

   else if($_GET['p'] == 6) {	
	
	echo'
	<div class="admin">
		<form method="post" action="?p=3" enctype="multipart/form-data">
		<h1>Opret Opskrift<em><input type="submit" name="submitCreateNewRecipe" id="button" value="Gem"/></em></h1>
			<ul>
				<li class="clearfix">
					<div>
						<input id="name" name="nameNewRecipe" type="text" value="" placeholder="Navn" /><br/>
						<select style="width:150px;" name="categoryNewRecipe">';
						$load_category = sql("SELECT * FROM category WHERE istype = 0 ORDER BY 'name' DESC");
						while($lc = mysql_fetch_array($load_category)){
						
						echo'<option value="'. $lc['id'] .'">'. $lc['name'] .'</option>';					
						}
						
						
												
						echo'
						</select>
					</div>
					<div>
						<textarea  name="descriptionNewRecipe"></textarea>
					</div>
					<div>
					   <h3>Opskrift</h3>
						<input name="pdfNewRecipe" type="file" size="50">
					</div>
					<div class="last">
					   <h3>Billede</h3>
						<input name="picNewRecipe" type="file" size="50">
					</div>
				</li>
			</ul>
		</form>
	</div>
	</div>';
	
	}
	
	//REDIGER OPSKRIFTER
	else if($_GET['p'] == 7) {
	
	   echo'<div class="admin">
	   	<form method="post" action="?p=3" enctype="multipart/form-data">
	   	<h1>Rediger Opskrift<em><input type="submit" name="submitEditRecipe" id="button" value="Gem"/></em></h1>
	         <ul>';
	         
	         $load_recipes = sql("SELECT * FROM recipies ORDER BY 'name' DESC");
	         while($lr = mysql_fetch_array($load_recipes)) {
	            echo'
	            <li class="clearfix">
	               <div>
	                  <img class="pic" src="../body/_gfx/recipe/'.$lr['id'].'.png"/>
	                  <input type="checkbox" name="ch' . $lr['id'] . '" value="net">
	                  <em>Slet</em>
	               </div>
	               <div>';
	                  //<iframe src="../body/pdf/'.$lr['id'].'.pdf"></iframe>
	                  echo'</br>
	                  <i><p><a href="../body/pdf/'.$lr['id'].'.pdf">Vis</a></p></i>
	               </div>
	            	<div>
	            		<input id="name" name="nameRecipe'.$lr['id'].'" type="text" value="'.$lr['name'].'" placeholder="Navn" /><br/><br/>
	            		
	            		<select style="width:150px;" name="category'. $lr['id'] .'">';
	            		$load_category = sql("SELECT * FROM category WHERE istype = 0 AND id = ".$lr['category']." ORDER BY 'name' DESC");
	            		while($lc = mysql_fetch_array($load_category)){
	            		
	            		echo'<option value="'. $lc['id'] .'">'. $lc['name'] .'</option>';					
	            		}
	            		
	            		
	            		$load_category = sql("SELECT * FROM category WHERE istype = 0 AND id != ".$lr['category']." ORDER BY 'name' DESC");
	            		while($lc = mysql_fetch_array($load_category)){				
	            			echo'<option value="'. $lc['id'] .'">'. $lc['name'] .'</option>';					
	            		}
	            		
	            		echo'
	            		</select>
	            	</div>
	            	<div class="last">
	            		<textarea  name="descriptionRecipe'.$lr['id'].'">'.$lr['description'].'</textarea>
	            	</div>
	            	
	            </li>
	            ';
	         }

	   echo'</ul>
	   	</form>
	   </div>
	   </div>';
	
	}
	else if($_GET['p'] == 8) {	
	
	echo'
	<div class="admin">
		<form method="post" action="?p=3" enctype="multipart/form-data">
		<h1>Opret Nyhed<em><input type="submit" name="submitCreateNews" id="button" value="Gem"/></em></h1>
			<ul>
				<li class="clearfix">
					<div>
						<input id="name" name="name" type="text" value="" placeholder="Navn" /><br/>
					</div>
					<div>
						<input id="name" name="link" type="text" value="" placeholder="Link" /><br/>
					</div>
					<div class="last">
					   <h3>Billede</h3>
						<input name="pic" type="file" size="50">
					</div>
				</li>
			</ul>
		</form>
	</div>
	</div>';
	
	}
	
	
	//REDIGER NYHEDER
	else if($_GET['p'] == 9) {
		
		   echo'<div class="admin">
		   	<form method="post" action="?p=3" enctype="multipart/form-data">
		   	<h1>Rediger News<em><input type="submit" name="submitEditNews" id="button" value="Gem"/></em></h1>
		         <ul>';
		         
		         $load_news = sql("SELECT * FROM news ORDER BY 'name' DESC");
		         while($ln = mysql_fetch_array($load_news)) {
		            echo'
		            <li class="clearfix">
		               <div>
		                  <img class="pic" style="width:115px; height:60px;" src="../body/_gfx/news/'.$ln['id'].'.png"/>
		                  <input type="checkbox" name="ch' . $ln['id'] . '" value="net">
		                  <em>Slet</em>
		               </div>
		               
		               <div>
		                  <input id="name" name="name'.$ln['id'].'" type="text" value="'.$ln['name'].'" placeholder="Navn" /><br/>
		               </div>
		               
		            	<div class="last">
		            		<input id="name" name="link'.$ln['id'].'" type="text" value="'.$ln['link'].'" placeholder="Navn" /><br/>
		            	</div>
		            	
		            </li>
		            ';
		         }
	
		   echo'</ul>
		   	</form>
		   </div>
		   </div>';
		
		} else if($_GET['p'] == 10) 
		{
			if($_POST['editCompetition'])
			{
				$msg = "Fejl, udfyld det hele!";
				if($_POST['title'] && $_POST['description'] && $_FILES['picCompetition'])
				{	
					$ftp_server = 'linux27.unoeuro.com';
   					$ftp_user_name = 'bundgaardsgarn.dk';
   					$ftp_user_pass = 'M15a42012';

					//PIC
				   	$picPaths = 'public_html/body/_gfx/';
				   	$picFileP = $_FILES['picCompetition']['tmp_name'];
				   	$picFileType = $_FILES["picCompetition"]["type"];
				   	$picFileSize = $_FILES["picCompetition"]["size"];
				   	$picName = 'competition.png';
				   	
				   	//DATA
				   	$title = $_POST['title'];
				   	$description = $_POST['description'];

					$picName = 'competition.png';
					
					// set up a connection to ftp server
					$conn_id = ftp_connect($ftp_server);
					
					// login with username and password
					$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
					
					// check connection and login result
					if ((!$conn_id) || (!$login_result)) {
				       echo "FTP connection has encountered an error!";
				       echo "Attempted to connect to $ftp_server for user $ftp_user_name....";
				       exit;
					}
					
					// upload the file to the path specified
					$upload = ftp_put($conn_id, $picPaths.'/'.$picName, $picFileP, FTP_BINARY);

					
					// check the upload status
					if (!$upload) {
						echo "FTP upload has encountered an error!";
					}

					sql("TRUNCATE TABLE  `competition_data`");
					sql("INSERT INTO competition_data (title, description) VALUES ('".$title."', '".$description."')");
					$msg = "Gemt!";
				   	
				}
				echo '<div class="admin">
								<h1>'.$msg.'</h1>
							</div>
							</div>


				';
			} else {
				echo '<div class="admin">
						<form method="post" action="" enctype="multipart/form-data">
						<h1>Rediger Konkurrence <em><input type="submit" name="editCompetition" id="button" value="Gem"/></em></h1>
							<ul>
								<li class="clearfix">
									<div>
										<input id="name" name="title" type="text" value="" placeholder="Titel" /><br/>
									</div>
									<div>
										<textarea  name="description">Beskrivelse</textarea><br/>

									</div>
									<div class="last">
									   <h3>Billede</h3>
										<input name="picCompetition" type="file" size="50">
										<p>Billedet skal være: 380px X 200px</p>
									</div>
								</li>
							</ul>
						</form>
					</div>
					</div>';
			}

		}
		else if($_GET['p'] == 11) 
			{


				$load_competition_participants = sql("SELECT * FROM `competition_participants` ");
				$numberOfParticipants = 0;
				while($lcp = mysql_fetch_array($load_competition_participants)){
					$numberOfParticipants++;
					$cp[$numberOfParticipants] = $lcp;
				}

				
				





				if($_POST['editCompetition'] && $_POST['option'] == 'find'){

					$random = rand(1, $numberOfParticipants);
					$winner = $cp[$random];					

				}

				if($_POST['editCompetition'] && $_POST['option'] == 'delete'){
					sql("TRUNCATE TABLE  `competition_participants`");	
				}

				echo '<div class="admin">
						<form method="post" action="" enctype="multipart/form-data">
						<h1>Rediger Konkurrence <em><input type="submit" name="editCompetition" id="button" value="Opdater"/></em></h1>
							<ul>
								<li class="clearfix">
									<div>
									  <select name="option">
											<option value="find">Find vinder</option>
											<option value="">..</option>
											<option value="">..</option>
											<option value="">..</option>
											<option value="delete">Fjern deltager</option>
										</select>
									</div>
									<div style="width:300px;">
									<p>Der er <b>'.$numberOfParticipants.'</b> deltager</p>
									<br/>
									';
									
									if($winner)
									{
										echo '
											<p><b>Navn:</b></p>
											<p>'.$winner['name'].'</p>
											<br/>
											<p><b>Email:</b></p>
											<p>'.$winner['email'].'</p>
											<br/>
											<p><b>Tlf:</b></p>
											<p>'.$winner['phone'].'</p>
										';
									}
									echo'
								</li>
							</ul>
						</form>
					</div>
					</div>';
			}
		 else {
	
	echo'
	<div class="admin">
		<h1>Admin Panel</h1>
		<a href="?p=4">Opret produkter</a>
		<br/>
		<a href="?p=1">Rediger Produkter</a>
		<br/>
		<a href="?p=5">Opret Kategori</>
		<br/>
		<a href="?p=2">Rediger Kategorier</a>
		<br/>
		<a href="?p=6">Opret Opskrifter</a>
		<br/>
		<a href="?p=7">Rediger Opskrifter</a>
		<br/>
		<a href="?p=8">Opret Nyheder</a>
		<br/>
		<a href="?p=9">Rediger Nyheder</a>
		<br/>
		<a href="?p=10">Rediger Konkurrencen</a>
		<br/>
		<a href="?p=11">Find vinder / Fjern deltager</a>
		
	</div>
	</div>
	';
	
	}

} else { 
		
	echo'
	<form method="post"  $PHP_SELF >
		<input name="login_username" type="text" placeholder="Brugernavn">
		<input name="login_password" type="password" placeholder="Adgangskode">
		<input type="submit" id="button" value="Login"></input>';
		if($ErrorOnLogin)
		{echo'<p style="color: #900000;">*' . $ErrorOnLogin . '';} 
		echo'
	</form>
	</div>';
	
	

}


// ###################################################################################################################
include("../body/footer.php"); ?>

