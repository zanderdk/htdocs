<?php



include('../body/header.php');
echo'<head>';

if($_GET['search'] == "Søg her"){

    redirect('/opskrifter/');

}

if($_GET['category'] || $_GET['search'] && $_GET['category']){

$load_recepies = sql("SELECT * FROM recipies WHERE category = ".$_GET['category']." ORDER BY 'id' DESC");
   $c = 0;
   while($lr = mysql_fetch_array($load_recepies)){
   
   $c ++;
   
   }
   
   if(!$c){
      redirect('/opskrifter/');
   }
}
$load_recepies = sql("SELECT * FROM recipies ORDER BY 'id' DESC");

while($lr = mysql_fetch_array($load_recepies)){
echo'
<link rel="alternate" media="print" href="../body/pdf/'.$lr['id'].'.pdf">';

}

echo'</head>';
echo'</div>';


echo'<div class="recipies">';
   echo'<ul>';
   
      
   if($_GET['category']) {
      
      $load_categories = sql("SELECT * FROM category WHERE id = ".$_GET['category']." ORDER BY 'name DESC'");
         while($lc = mysql_fetch_array($load_categories)){
         echo'<h1>Opskrifter - I kategorien '.$lc['name'].' </h1>';
       }
      
      $sqlStr = "SELECT * FROM recipies WHERE category = ".$_GET['category']." ORDER BY 'name' DESC ";
   
   }
   else if($_GET['search'] || $_GET['category'] && $_GET['search']) {
   
      echo'<h1>Opskrifter - Søgning på "'.$_GET['search'].'"</h1>';
      
      $sqlStr = "SELECT * FROM recipies WHERE name LIKE '%".$_GET['search']."%' ORDER BY 'name' DESC";
   
   }
   else {
      echo'<h1>Alle Opskrifter</h1>';
      $sqlStr = "SELECT * FROM recipies ORDER BY 'name' DESC";
   
   }
   
   
   if($_GET['side']){
      $side = $_GET['side'];	
   } else {
      $side = 0;
   }
   
   $itemsPerPage = 10;
   
   $size = 0;
   $load_size = sql($sqlStr);
   while($ls = mysql_fetch_array($load_size)){
      $size++;
   }
   
   if($side < 0) {
      $side = 0;
   } else if(($size-($side*$itemsPerPage)) < 0) {
      $side = floor($size / $itemsPerPage);
   }
   
   $sqlStr = $sqlStr . " LIMIT ".($side*$itemsPerPage).",". $itemsPerPage; 
   $load_recepies = sql($sqlStr);

   while($lr = mysql_fetch_array($load_recepies)){
   
      echo'</b>';   
      echo'<li class="clearfix">';
         echo'<img class="avatar" src="../body/_gfx/recipe/'.$lr['id'].'.png"/>';
         echo'<div class="info">';
            echo'<h2>'.$lr['name'].'</h2>';
            echo'<p>'.$lr['description'].'</p>';
            echo'<a href="../body/pdf/'.$lr['id'].'.pdf" target="_blank"><div class="pdf"><img class="icon" src="../body/_gfx/icons/preview.png"/></br><p>Åben</p></div></a>';
            
            echo'<iframe style="display:none;" src="../body/pdf/'.$lr['id'].'.pdf"" id="PDFtoPrint'.$lr['id'].'"></iframe>';
            echo'<a href="" onclick="document.getxElementById(\'PDFtoPrint'.$lr['id'].'\').focus(); document.getElementById(\'PDFtoPrint'.$lr['id'].'\').contentWindow.print();">
           <div class="pdf"><img class="icon" src="../body/_gfx/icons/print.png"/></br><p>Print</p></div></a>';
         echo'</div>';    
      echo'</li>';
      echo'</b>';
   }
   
   if($_GET['category']){
     $prevStr = "?category=".$_GET['category']."&side=" . ($side-1);
     $nextStr = "?category=".$_GET['category']."&side=" . ($side+1);
   }
   else if($_GET['search'] || $_GET['category'] && $_GET['search']) {
      $prevStr = "?search=".$_GET['search']."&side=" . ($side-1);
      $nextStr = "?search=".$_GET['search']."&side=" . ($side+1);
   }
   else {
      $prevStr = '?side=' . ($side-1);
      $nextStr = '?side=' . ($side+1);
   }
   
   if($side > 0){
   echo'<a  class="button" style="width:100px; text-align:center; float:left; margin-top:10px" href="'.$prevStr.'">Tilbage</a>';
   }
   
   if(floor($size / $itemsPerPage) != $side){ 
   echo'<a class="button" style="width:100px; text-align:center; float:right; margin-top:10px" href="'.$nextStr.'">Frem</a>';
   }  
   echo'</ul>';
echo'</div>';

include('../body/footer.php'); 
?>