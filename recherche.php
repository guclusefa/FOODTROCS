<?php
error_reporting(0); 
session_start();
$bdd = mysqli_connect("localhost", "jajaydck_root", "$1E3q_TkSYj6u9", "jajaydck_foodtrocs");
$bdd2 = new PDO('mysql:host=localhost;dbname=jajaydck_foodtrocs', 'jajaydck_root', '$1E3q_TkSYj6u9');
    if(isset($_SESSION['id'])){
?>

<head>
    <title>RECHERCHE / FOOD TROCS</title>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/foodtrocs.1.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>

<p><a href="accueil.php">accueil</a></br>
sieste sur le css car c'est : ennuaynt</p>
<form method="POST" action="">
<input type="text" placeholder="Rechercher dans FOODTROCS" name="membre" id="membre" style="width:300px;" >
<input type="submit" value="RECHERCHER">
</form>
<p>ex : france, turquie, fontaine, seyssinet, spaghetti....</p>

<?php
    if (isset($_POST['membre'])){
        $var = $_POST['membre'];
        header("location: recherche.php?search=".$var);
    }
        if (isset($_GET['search']) and strlen($_GET['search']) > 0){
            if (isset($_GET['search'])){
                $recherche = $_GET['search'];
            } else {
                $recherche = htmlspecialchars($_POST['membre']);
            }
            $sql = "SELECT * FROM membres WHERE nom LIKE '%$recherche%' OR prenom LIKE '%$recherche%' OR pseudo LIKE '%$recherche%' OR localisation LIKE '%$recherche%'";
            $sql2 = "SELECT * FROM produits WHERE intitule LIKE '%$recherche%' OR type LIKE '%$recherche%' OR provenance LIKE '%$recherche%'";

            $sql3 = "SELECT COUNT(*) as total FROM produits WHERE intitule LIKE '%$recherche%' OR type LIKE '%$recherche%' OR provenance LIKE '%$recherche%'";
            $sql4 = "SELECT COUNT(*) as total FROM membres WHERE nom LIKE '%$recherche%' OR prenom LIKE '%$recherche%' OR pseudo LIKE '%$recherche%' OR localisation LIKE '%$recherche%'";

            $result = mysqli_query($bdd, $sql);
            $result2 = mysqli_query($bdd, $sql2);

            $result3 = mysqli_query($bdd, $sql3);
            $result4 = mysqli_query($bdd, $sql4);
            $rowr3 = mysqli_fetch_array($result3);
            $rowr4 = mysqli_fetch_array($result4);
                if (mysqli_num_rows($result) > 0 or mysqli_num_rows($result2) > 0) {
                $rtotal = $rowr3['total']+$rowr4['total'];
                if ($rtotal == 1) {
                echo "<b>".$rtotal."</b> résultat pour <b>\"".$recherche."\"</b></br></br>"; 
                } else {
                    echo "<b>".$rtotal."</b> résultats pour <b>\"".$recherche."\"</b></br></br>"; 
                }
                }
                if (mysqli_num_rows($result) > 0){
                while ($row = mysqli_fetch_array($result)) {
?>
<a href="user.php?id=<?php echo $row['id'] ?>">
<?php
                echo '<img style="height:50px; width:50px;" src="data:image/jpeg;base64,' . base64_encode($row['photo_profil']) . '"/>';
                echo ' ';
                echo $row['nom'];
                echo ' "';
                echo $row['pseudo'];
                echo '" ';
                echo $row['prenom'];
                echo ' ';
                echo $row['localisation']."</br></br>";
?>
</a>
<?php
                }    
            }
            if (mysqli_num_rows($result2) > 0) {
                while ($row2 = mysqli_fetch_array($result2)) {
?>
<a href="acheter.php?id=<?php echo $row2['id'] ?>">
<?php
                echo '<img style="height:50px; width:50px;" src="data:image/jpeg;base64,' . base64_encode($row2['photo_produit']) . '"/>';
                echo ' ';    
                echo $row2['type'];
                echo ' "';
                echo $row2['intitule'];
                echo '" ';
                echo $row2['provenance'];
                echo '" ';
                echo $row2['description']."</br>";
?>
</a>
<?php
                }    
            }
            if (mysqli_num_rows($result) == 0 and mysqli_num_rows($result2) == 0) {
                echo "Aucun résultat pour <b>\"$recherche\"</b>";
            }
        }

} else {
    header("location: connexion.php");
}
?>
