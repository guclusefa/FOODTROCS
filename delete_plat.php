<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=jajaydck_foodtrocs', 'jajaydck_root', '$1E3q_TkSYj6u9');
$id = $_SESSION['id'];
$idp = $_GET['id'];
$admin = $bdd->prepare("SELECT * FROM `admin` WHERE idAdmin = $id");
$admin->execute(array($_SESSION['id']));
$useradmin = $admin->fetch();
$idAdmin = $useradmin['idAdmin'];

$requser = $bdd->prepare("SELECT * FROM membres , produits WHERE membres.id = produits.idMembre AND produits.id = $idp");
$requser->execute(array($_SESSION['id']));
$userinfo = $requser->fetch();

if ($idAdmin == $userinfo['idMembre']) {
    header("location: mes_plats.php");
}
if (isset($idAdmin)) {
?>

    <head>
        <title>SUPPRIMER PLAT / FOODTROCS</title>
        <meta charset="UTF-8">
        <link rel="icon" type="image/png" href="images/foodtrocs.1.png" />
    </head>
    <?php
    $conn = mysqli_connect("localhost", "jajaydck_root", "$1E3q_TkSYj6u9", "jajaydck_foodtrocs");
    $sql = "DELETE FROM produits WHERE id = $idp";
    if (isset($_POST['formoui'])) {
        if (mysqli_query($conn, $sql)) {
            mysqli_close($conn);
            header('Location: admin.php'); //If book.php is your main page where you list your all records
            exit;
        }
    }
    if (isset($_POST['formnon'])) {
        header("location: admin.php");
    }
    ?>
    flemme css lol
    <form align="center" method="POST" action="" enctype="multipart/form-data">
        <h1>Etes vous sur de vouloir supprimer ce plat ?</h1>
        <button class="test2" type="submit" name="formnon">Non</button>
        <button class="test2" type="submit" name="formoui">Oui</button>
    </form>
    <?php echo '<img style="height:auto; width:auto;" src="data:image/jpeg;base64,' . base64_encode($userinfo['photo_produit']) . '"/>'; ?></br>
    par : <?php echo $userinfo['prenom'] ?> "<?php echo $userinfo['pseudo'] ?>" <?php echo $userinfo['nom'] ?></br>
    type : <?php echo $userinfo['type'] ?></br>
    intitule : <?php echo $userinfo['intitule'] ?></br>
    description : <?php echo nl2br($userinfo['description']) ?></br>
    provenace : <?php echo $userinfo['provenance'] ?></br>
    prix : <?php echo $userinfo['prix'] ?></br>
    nombre de ventes : <?php echo $userinfo['nbVentes'] ?></br>
    id : <?php echo $userinfo['id'] ?></br>
<?php
} else {
    header("Location: accueil.php");
}
?>