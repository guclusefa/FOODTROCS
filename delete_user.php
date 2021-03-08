<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=jajaydck_foodtrocs', 'jajaydck_root', '$1E3q_TkSYj6u9');
$id = $_SESSION['id'];
$idm = $_GET['id'];
$admin = $bdd->prepare("SELECT * FROM `admin` WHERE idAdmin = $id");
$admin->execute(array($_SESSION['id']));
$useradmin = $admin->fetch();
$idAdmin = $useradmin['idAdmin'];

$requser = $bdd->prepare("SELECT * FROM membres WHERE membres.id = $idm");
$requser->execute(array($_SESSION['id']));
$userinfo = $requser->fetch();

if ($idAdmin == $userinfo['id']) {
    header("location: profil.php");
}
if (isset($idAdmin)) {
?>

    <head>
        <title>SUPPRIMER MEMBRE / FOODTROCS</title>
        <meta charset="UTF-8">
        <link rel="icon" type="image/png" href="images/foodtrocs.1.png" />
    </head>
    <?php
    $conn = mysqli_connect("localhost", "jajaydck_root", "$1E3q_TkSYj6u9", "jajaydck_foodtrocs");
    $sql = "DELETE FROM membres WHERE id = $idm";
    $sql2 = "DELETE FROM produits WHERE idMembre = $idm";
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
        <h1>Etes vous sur de vouloir supprimer ce membre et tout ses plats ?</h1>
        <button class="test2" type="submit" name="formnon">Non</button>
        <button class="test2" type="submit" name="formoui">Oui</button>
    </form>
    <?php echo '<img style="height:auto; width:auto;" src="data:image/jpeg;base64,' . base64_encode($userinfo['photo_profil']) . '"/>'; ?></br>
    nom : <?php echo $userinfo['prenom'] ?> <?php echo $userinfo['nom'] ?></br>
    pseudo : <?php echo $userinfo['pseudo'] ?></br>
    mail : <?php echo $userinfo['mail'] ?></br>
    tél : <?php echo $userinfo['num'] ?></br>
    localisation : <?php echo $userinfo['localisation'] ?></br>
    biographie : <?php echo nl2br($userinfo['bio']) ?></br>
    inscrit le : <?php echo $userinfo['date'] ?></br>
    monnaie : <?php echo $userinfo['monnaie'] ?></br>
    ventes : <?php echo $userinfo['ventes'] ?>€</br>
    nombre de ventes :<?php echo $userinfo['nbrVentes'] ?></br>
    achats : <?php echo $userinfo['achats'] ?>€</br>
    nombre d'achats : <?php echo $userinfo['nbrAchats'] ?></br>
    id : <?php echo $userinfo['id'] ?>
<?php
} else {
    header("Location: accueil.php");
}
?>