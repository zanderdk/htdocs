<?php include('../body/header.php');

if($_POST['send']){

   if($_POST['email'] || $_POST['subject'] || $_POST['content'] || $_POST['name']){
   
   	$tried = true;
   	
   	if($_POST['email']){
   		
   		if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
   		
   			$email = true;
   		
   		} else {
   		
   			$email = false;
   		
   		}
   		
   	}
   	
   } else {
      $tried = true;
      $email = false;
      
   }
   
}

if($email == true && $_POST['subject'] && $_POST['content'] && $_POST['name']) {
	
	$to = "kontakt@bundgaardsgarn.dk";
	$subject = $_POST['subject'];
	$content = $_POST['content'];
	$headers = "From: Garnproblemer fra ". $_POST['name'] ." <". $_POST['email'] .">\r\n";
	$header .= "Message-Id:Garnproblemer:\r\n" . $_POST['name'];
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=utf-8\r\n";
	
	mail($to, $subject, $content, $headers);
	
   $sent = true;

}



echo'<div class="contact">';

    if(!$sent){
	
   	if($_GET['a'] == "vare") {
   	   echo'<h1>Farveprøver</h1>';
   	   echo'<p>Her kan du sende en email angående bestilling af farveprøver, fra menuen ”Restgarn” (det er pt. ikke muligt at bestille farveprøver, fra menuen ”Garn”). Det er vigtigt at du skriver følgende ting nede i beskeden:</p>';
   	   echo'<ul>';
   	      echo'<li>Fulde navn</li>';
   	      echo'<li>Adresse til modtagelse af farveprøver</li>';
   	      echo'<li>Liste med numre over de vare man gerne vil have farveprøver af</li>';
   	   echo'</ul>';
   	   echo'<p><b>NB:</b> Hvis du ønsker at bestille mere end 30 farveprøver så ring helst på: 30 36 95 22</p>';
   	} else {
   	   echo'<h1>Kontakt</h1>';
         echo'<p>Her kan du sende en email til Bundgaards Garn hvis du har spørgsmål eller problemer</p>';
      }
      
      //CONTENT
   	echo'<form name="input" action="" method="POST">';
   		echo'<h5>Navn';
   		if($_POST['name'] == null && $tried == true) {
   		   echo'<em style="color:#900000;">* Du skal indtast et navn</em>';
   		}
   		echo'</h5>';
   		   
   		echo'<input type="text" name="name" placeholder="Navn" />';
   		echo'<h5>Email';
   		if($email == false && $tried == true) {
   		   echo'<em style="color:#900000;">* Du skal indtaste en rigtig email</em>';
   		}
   		echo'</h5>';
   		echo'<input type="text" name="email" placeholder="Email" />';
   		echo'<h5>Emne';
   		if($_POST['subject'] == null && $tried == true) {
   		   echo'<em style="color:#900000;">* Du skal indtaste et emne</em>';
   		   }
   		echo'</h5>';
   		echo'<input type="text" name="subject" placeholder="Emne" />';
   		echo'<h5>Meddelse';
   		if($_POST['content'] == null && $tried == true) {
   		   echo'<em style="color:#900000;">* Du skal skrive en meddelelse</em>';
   		}
   		echo'</h5>';
   		echo'<textarea name="content"></textarea><br/><br/>';
   		echo'<div class="buttonholder">';
      		echo'<input type="submit" class="button" name="send" value="Send Besked" />';
   		echo'</div>';
   	echo'</form>';
	 } else {
	
	echo'<h1>Mail sendt</h1>';
	echo'<p>Din email er blevet sendt vi svare tilbage hurtigts muligt</p>';
	
	}

echo'</div>';
echo'</div>';


include('../body/footer.php'); ?>