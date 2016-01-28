<?php
// CONNECT TO MYSQL

// #################################################### COPY THIS ####################################################
// MySQL
$mysql_host = "mysql13.unoeuro.com";
$mysql_user = "bundgaardsg_dk";
$mysql_db = "bundgaardsgarn_dk_db3";
$mysql_pass = "M15a42012";
$con = "";

// ###################################################################################################################

// Connect to MySQL
function connect() {
	global $mysql_host,$mysql_user,$mysql_pass,$con,$mysql_db;
	$con = mysql_connect($mysql_host,$mysql_user,$mysql_pass) or die(mysql_error());
	mysql_select_db($mysql_db);
	
	//Sets Danish charset
	mysql_query("SET NAMES utf8");
	mysql_query("SET character_set_results=’utf8′");
}

// Disconnect from MySQL
function unconnect() {
	global $con;
	mysql_close($con) or die(mysql_error());
}

// Do Sql
function sql($sql) {
	return mysql_query($sql);
}


//RETURN URL

function isURL($this_url) {

$url = $_SERVER[REQUEST_URI];

	if($url == $this_url) {
		return true;
	}
	return false;
}

function getSideName() {

	$subject = $_SERVER["SCRIPT_NAME"];
	
	$search = "/";
	$replace = "";
	$url = str_replace($search, $replace, $subject);
	$search ="index.php";
	$url = str_replace($search, $replace, $url);
	
	return $url;

}

function isIndex() {

	if(getSideName() == "") {
		return true;
	} else {
		return false;
	}
}

function do_alert($msg) 
    {
        echo '<script type="text/javascript">alert("' . $msg . '"); </script>';
    }
    
 function redirect($url) {
    
    echo'<script type="text/javascript">
    window.location = "'.$url.'"
    </script>';
    
 }

 function isemail($email) {
    return preg_match('|^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]{2,})+$|i', $email);
}
?>