<?php
session_start();
$bdd = mysqli_connect("localhost", "jajaydck_root", "$1E3q_TkSYj6u9", "jajaydck_foodtrocs");
$bdd2 = new PDO('mysql:host=localhost;dbname=jajaydck_foodtrocs', 'jajaydck_root', '$1E3q_TkSYj6u9');
    $sql = "SELECT DISTINCT provenance FROM produits";
?>

<head>
    <title>CUISINE / FOOD TROCS</title>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/foodtrocs.1.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<p><a href="accueil.php">accueil</a></br>
sieste sur le css car c'est : ennuaynt</p>

<?php
    if ($result = mysqli_query($bdd, $sql)){
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
            echo "<a href='recherche.php?search=".$row['provenance']."'>".$row['provenance']."</br>";
?>
<img style="height:200px; width:auto;" src="images/<?php echo $row['provenance'] ?>.jpg"></a></br></br>
<?php
            }  
        }
    }
?>