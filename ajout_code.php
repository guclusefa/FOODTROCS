<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=jajaydck_foodtrocs', 'jajaydck_root', '$1E3q_TkSYj6u9');
$bdd2 = mysqli_connect("localhost", "jajaydck_root", "$1E3q_TkSYj6u9", "jajaydck_foodtrocs");
$id = $_SESSION['id'];
$pseudo = $_SESSION['pseudo'];
date_default_timezone_set('Europe/Paris');
$date = date('Y-m-d H:i:s');
$admin = $bdd->prepare("SELECT * FROM `admin` WHERE idAdmin = $id");
$admin->execute(array($_SESSION['id']));
$useradmin = $admin->fetch();
$idAdmin = $useradmin['idAdmin'];

if (isset($idAdmin)) {
    if (isset($_POST['formreponse'])) {
            $nom = htmlspecialchars($_POST['nom']);
            $taux = htmlspecialchars($_POST['taux']);
            $prixmin = htmlspecialchars($_POST['prixmin']);
            $nmbre = htmlspecialchars($_POST['nmbre']);
            if ($taux <= $prixmin){
            $insertrep = $bdd->prepare("INSERT INTO code_promo(nom_code, taux_code, prixmin_code, nmbre_code) VALUES ('$nom', '$taux', '$prixmin', '$nmbre')");
            $insertrep->execute();
            echo "<font color='red'>code ajouté avec succès !</font></br>";
    } else {
        echo "<font color='red'>taux de réduction doit être inférieur ou égal au prix min!</font></br>";
    }
    }
?>

    <head>
        <title>AJOUT CODE / FOODTROCS</title>
        <meta charset="UTF-8">
        <link rel="icon" type="image/png" href="images/foodtrocs.1.png" />
    </head>
    flemme css lol
    <form method="POST" action="" enctype="multipart/form-data">
        <p><input type="text" placeholder="Nom code" size="48" required name="nom"></p>
        <p><input type="number" min="0" placeholder="Taux de réduction" size="48" required name="taux">€</p>
        <p><input type="number" min="0" placeholder="Prix minimum" size="48" required name="prixmin">€</p>
        <p><input type="number" min="0" placeholder="Nombre de code" size="48" required name="nmbre"></p>
        <p><input type="submit" name="formreponse"></button></p>
    </form>
<?php
} else {
    header("Location: accueil.php");
}
?>
