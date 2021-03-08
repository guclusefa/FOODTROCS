<?php
session_start();
$bdd = mysqli_connect("localhost", "jajaydck_root", "$1E3q_TkSYj6u9", "jajaydck_foodtrocs");
$bdd2 = new PDO('mysql:host=localhost;dbname=jajaydck_foodtrocs', 'jajaydck_root', '$1E3q_TkSYj6u9');
    $sql = $bdd2->prepare("SELECT * FROM cookies WHERE id=1");
    $sql->execute();
    $sqlres = $sql->fetch();
    $nbre = $sqlres['clique_cookie'];

if (isset($_POST['lol'])){
        $nbre = $nbre +1;
        $insertcookie = $bdd2->prepare("UPDATE cookies SET clique_cookie = $nbre WHERE id = 1");
        $insertcookie->execute(array($nbre));
    }
?>

<head>
    <title>COOKIES / FOOD TROCS</title>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/foodtrocs.1.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<p><a href="accueil.php">accueil</a></br>
sieste sur le css car c'est : ennuaynt</p>

<p>le cookie a été cliqué <b><?php echo $nbre;?></b> fois</br>
cliqué 1000fois pour débloquer une récompense !</p>
<form method="POST" action="">
<button style="background-color:white; border:none; outline:none;" name="lol"><img src="images/cookies.png" style="height:30%; width:30%; background-color:white; border:none;  cursor:pointer"/></button>
</form>