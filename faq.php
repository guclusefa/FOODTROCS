<?php
session_start();
$bdd = mysqli_connect("localhost", "jajaydck_root", "$1E3q_TkSYj6u9", "jajaydck_foodtrocs");
$bdd2 = new PDO('mysql:host=localhost;dbname=jajaydck_foodtrocs', 'jajaydck_root', '$1E3q_TkSYj6u9');
$sql = "SELECT * FROM faq WHERE reponse <> 'AUCUNE REPONSE'";
?>

<head>
    <title>FAQ / FOOD TROCS</title>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/foodtrocs.1.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<p><a href="accueil.php">accueil</a></br>
sieste sur le css car c'est : ennuaynt</p>
<p>poser une <a href="accueil.php#contact">question</a></p>
<iframe src="https://olafwempe.com/mp3/silence/silence.mp3" type="audio/mp3" allow="autoplay" id="audio" style="display:none"></iframe>
<p><audio autoplay src="videos/gorgeous.mp3" controls></audio></p>
<?php
    if ($result = mysqli_query($bdd, $sql)){
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
            echo "question de <b><a href='user.php?id=" .$row['idMembre']. "'>" . $row['pseudo']."</a></b> :</br> ".nl2br($row['question'])."</br>";
            echo "reponse de <b><a href='user.php?id=".$row['id_a']. "'>" .$row['pseudo_a']."</a></b> :</br> ".nl2br($row['reponse'])."</br></br>";
            }  
        }
    }
?>