<?php



include('../../body/header.php');

echo'<style>

 .category h1 {
	font-size: 22pt;
	color: #000;
	padding: 0 15px 15px 15px;
	border-bottom: 1px solid #ccc;
	margin-left: -15px;
	margin-right: -15px;
	margin-bottom: 8px;
}

 .category .big_button {
   font-size:16pt;
   width:226px;
   float:left;
   padding:20px 0;
   text-decoration:none; 
   margin:10px 11px;
   text-align:center;
}

 .category .lastWithTwo {
   margin:0 0 0 123px;
}

 .category .lastWithOne {
   margin:0 0 0 247px;
}
</style>';

echo'<div class="category">';

echo'<h1>Kategorier</h1>';

$categories = array();


$load_categories = sql("SELECT * FROM category ORDER BY 'id' DESC");
while($lc = mysql_fetch_array($load_categories)){
      sql("UPDATE category SET hasRecipe= '0' WHERE id='" . $lc['id'] ."'") or die(mysql_error());
}

$size = 0;
$load_recepies = sql("SELECT * FROM recipies ORDER BY 'category' DESC");
while($lr = mysql_fetch_array($load_recepies)){
   $load_categories = sql("SELECT * FROM category ORDER BY 'id' DESC");
   while($lc = mysql_fetch_array($load_categories)){
      if($lr['category'] == $lc['id']){
         sql("UPDATE category SET hasRecipe= '1' WHERE id='" . $lc['id'] ."'") or die(mysql_error());
         $ize++;
      }      
   }
}

$c = 1;
$load_categories = sql("SELECT * FROM category WHERE hasRecipe = '1' ORDER BY 'id' DESC");
while($lc = mysql_fetch_array($load_categories)){

   $rest = $size % 3;

   
      if($c == 1){
         echo'<div class="clearfix">';
      }
      else if($c % 3 == 1){
         echo'</div>';
         echo'<div class="clearfix';
         if($rest != 0 && ($size - $size%3) <= $c){
            if($rest == 1){
               echo " lastWithOne";
            }
            else if($rest == 2) {
               echo " lastWithTwo";
            }
            
         }
         echo'">';
      }
      
      echo'<a class="button big_button"href="../?category='.$lc['id'].'">'.$lc['name'].'</a>';
   
      $c++;
}




   echo'</div>';
echo'</div>';
echo'<hr style="height:0px; border:none; border-bottom:1px dashed #bbb;">';
echo'<a class="button" style="display:block;width:400px; font-size:16pt; padding:10px 0; margin:20px 170px; text-align:center;" href="..">Alle Opskrifter</a>';


echo'</div>';
include('../../body/footer.php'); 
?>