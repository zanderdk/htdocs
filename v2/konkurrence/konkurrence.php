<?php
include('../body/functions.php');

connect();
session_start();

if($_SESSION['facebook'] != 'true'){
    header("Location: http://www.bundgaardsgarn.dk");
    die;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>Bundgaards Garn</title>

<!--Tags-->
<meta name="keywords" content="Garn,Mayflower,Strikkegarn,Håndstrik,Uld,Mohair,Hør,Bomduld,Opskrifter,Strikkeopskrifter,Strik,Bundgaard,Billigt garn,Salg af garn, Bubmo strømpegarn, strømpegarn, restgarn, Brønderslev, Nordjylland,Bumbo,Bumbo me, Bumbo you, Bumbo MIX, Garntilbud, billigt, Linate, Online, Udsalg, Polyester, Akryl, Fancy, ">

<!--Charset-->
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

<!--CSS-->
<link href="../body/styles/konkurrence.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php



if($_POST['submitCompetition'] && !$_SESSION['sucess'])
{

    $msg = 'Du skal udfylde alle felter!';

    if($_POST['name'] && $_POST['email'] && $_POST['phone'])
    {
        if(isemail($_POST['email'])){

            $name = mysql_real_escape_string($_POST['name']);
            $email = mysql_real_escape_string($_POST['email']);
            $phone = mysql_real_escape_string($_POST['phone']);

            sql("INSERT INTO competition_participants (name, email, phone) VALUES ('".$name."','".$email."','".$phone."')");
            $_SESSION['sucess'] = true;
        }
        $msg = 'Du skal indtaste en rigtig emailadresse';
    } 


}

$load_competition_data = sql("SELECT * FROM `competition_data` ");
$lcd = mysql_fetch_array($load_competition_data);

echo '<div class="container">';
    echo '<h1>'.$lcd['title'].'</h1>';
    echo '<div class="img_container">';
        echo '<div class="img" style="">';
        echo '</div>';
    echo '</div>';
    if(!$_SESSION['sucess']){
        echo '<b><p>'.$lcd['description'].'</p></b>';
        echo '<p>For at deltage i konkurrencen skal vi bruge dit navn, email og telefonnummer. Vi bruger ikke disse oplysninger til andet end at kontakte dig hvis det er dig som bliver den heldige vinder.</p>';
        echo "<em>".$msg."</em>";
        echo '<form method="post" action="">';
            echo '<div class="">';
                echo '<h5>Navn:</h5>';   
                echo '<input id="text" name="name" type="text" value="'.$_POST['name'].'"/><br/>';
            echo '</div>';
            echo '<div class="">';
                echo '<h5>Email:</h5>';
                echo '<input id="text" name="email" type="text" value="'.$_POST['email'].'"/><br/>';
            echo '</div>';
            echo '<div class="">';
                echo '<h5>Tlf:</h5>';
                echo '<input id="text" name="phone" type="text" value="'.$_POST['phone'].'"/><br/>';
            echo '</div>';
            echo'<input type="submit" name="submitCompetition" id="button" class="grey" value="Deltag"/>';
        echo '</form>';
    } else {
        echo "<b><p>Du deltager i konkurrencen vi kontakter dig på email hvis du er den heldige vinder!</p></b>";
    }

unconnect();
    ?>
</div>

</body>

