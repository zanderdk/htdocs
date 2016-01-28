<?php
include("body/header.php");
$_SESSION['bugle'] = 'ja';
session_start();
if($_GET['t']){

$load_type = sql("SELECT * FROM category WHERE istype = 1 ORDER BY 'name' DESC");
while($lt = mysql_fetch_array($load_type)){

if($_GET['t'] == $lt['id'])

$load_subtype = sql("SELECT * FROM category WHERE istype = 0 AND parrent =". $lt['id'] ." ORDER BY 'name' DESC");
while($lst = mysql_fetch_array($load_subtype)){

if($_GET['st'] == $lst['id']){ 

echo'<h1>'. $lst['name'] .'</h1>';

$load_products = sql("SELECT * FROM products WHERE item = ". $lst['id'] ." ORDER BY ID DESC");
while($lp = mysql_fetch_array($load_products)){

if($lp['stock'] == 3){

$stockmsg = '<em style="color:#009900;">Mange på lager</em>';

}

else if($lp['stock'] == 2){

$stockmsg = '<em style="color:#909000;">Begrænset lager</em>';

}

else if($lp['stock'] == 1){

$stockmsg = '<em style="color:#904500;">Få på lager</em>';

}

else {

$stockmsg = '<em style="color:#900000;">Ikke på lager</em>';

}

echo'<div class="product">';
echo'<div class="info clearfix">';
echo'<h2>'. $lp['name'] .'</h2>';
echo'<div class="img">';
echo'<img src="/body/_gfx/products/'.$lp['id'].'.png"/>';
echo'</div>';
echo'<h3>'. $lp['price'] * 1.25 .' DKK '. $stockmsg .'</h3>';
echo'<p>'. $lp['description'] .'</p>';
echo'<form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">';
echo'<input type="hidden" name="business" value="bugledk@gmail.com">';
echo'<input type="hidden" name="cmd" value="_cart">';
echo'<input type="hidden" name="add" value="1">';
echo'<input type="hidden" name="item_name" value="'. $lp['name'] .'">';
echo'<input type="hidden" name="amount" value="'. $lp['price'] .'">';
echo'<input type="hidden" name="tax_rate" value="25.000">';
echo'<input type="hidden" name="shipping" value="42">';
echo'<input type="hidden" name="currency_code" value="DKK">';
echo'<input type="submit" class="button" name="submit" border="0" src="" value="Tilføj til kurv">';
echo'</form>';
echo'</div>';
echo'</div>';				
}
echo'</div>';
}

}

}
} else {


echo'<img src="body/_gfx/front.jpg" alt=""/>';
echo'</div>'; 

echo'</div>'; 

echo'<div class="container grid_10">';

echo'<div class="content clearfix">';


$space = 1;
$load_news = sql("SELECT * FROM news ORDER BY 'id' DESC LIMIT 0,3");
while($ln = mysql_fetch_array($load_news)) {

echo'<a href="'.$ln['link'].'"><div class="news">';

echo'<img src="/body/_gfx/news/'.$ln['id'].'.png"/>';
echo'<h3>'.$ln['name'].'</h3>';

echo'</div></a>';

if($space == 1 || $space == 2){

echo'<div class="space_news"></div>';

}

$space++;

}

echo'</div>';


}

include("body/footer.php"); 

?>