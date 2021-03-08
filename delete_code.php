<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=jajaydck_foodtrocs', 'jajaydck_root', '$1E3q_TkSYj6u9');
$id = $_SESSION['id'];
$idp = $_GET['id'];
$admin = $bdd->prepare("SELECT * FROM `admin` WHERE idAdmin = $id");
$admin->execute(array($_SESSION['id']));
$useradmin = $admin->fetch();
$idAdmin = $useradmin['idAdmin'];

$requser = $bdd->prepare("SELECT * FROM code_promo WHERE id_code = $idp");
$requser->execute(array($_SESSION['id']));
$userinfo = $requser->fetch();

if (isset($idAdmin)) {
?>

    <head>
        <title>SUPPRIMER CODE / FOODTROCS</title>
        <meta charset="UTF-8">
        <link rel="icon" type="image/png" href="images/foodtrocs.1.png" />
    </head>
    <?php
    $conn = mysqli_connect("localhost", "jajaydck_root", "$1E3q_TkSYj6u9", "jajaydck_foodtrocs");
    $sql = "DELETE FROM code_promo WHERE id_code = $idp";
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
        <h1>Etes vous sur de vouloir supprimer ce code ?</h1>
        <button class="test2" type="submit" name="formnon">Non</button>
        <button class="test2" type="submit" name="formoui">Oui</button>
    </form>
    id : <?php echo $userinfo['id_code'] ?></br>
    nom : <?php echo $userinfo['nom_code'] ?></br>
    taux réduction : <?php echo $userinfo['taux_code'] ?>€</br>
    prix min : <?php echo nl2br($userinfo['prixmin_code']) ?>€</br>
    nombre de codes : <?php echo $userinfo['nmbre_code'] ?></br>
<?php
} else {
    header("Location: accueil.php");
}
?>