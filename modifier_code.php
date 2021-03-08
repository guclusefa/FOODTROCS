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
        <title>MODIFIER CODE / FOODTROCS</title>
        <meta charset="UTF-8">
        <link rel="icon" type="image/png" href="images/foodtrocs.1.png" />
    </head>
    <?php
    $conn = mysqli_connect("localhost", "jajaydck_root", "$1E3q_TkSYj6u9", "jajaydck_foodtrocs");
    if (isset($_POST['formcode']) and isset($_POST['nomc']) and isset($_POST['txc']) and isset($_POST['pmc']) and isset($_POST['nbc'])) {
    $nom = $_POST['nomc'];
    $taux = $_POST['txc']; 
    $prixmin = $_POST['pmc']; 
    $nmbre = $_POST['nbc'];   
    if ($taux <= $prixmin){
    $sql = "UPDATE code_promo SET nom_code = '$nom', taux_code = '$taux', prixmin_code = '$prixmin', nmbre_code = '$nmbre' WHERE id_code = $idp";  
        if (mysqli_query($conn, $sql)) {
            mysqli_close($conn);
            header('Location: admin.php'); //If book.php is your main page where you list your all records
            exit;
        }
        } else {
            echo "<font color='red'>taux de réduction doit être inférieur ou égal au prix min!</font></br>";
        }
    }
    ?>
    flemme css lol</br>
    <form method="POST" action="" enctype="multipart/form-data">
    <label>nom code </label>
    <input type="text" placeholder="nom du code" value="<?php echo $userinfo['nom_code'] ?>" name="nomc" required/></br></br>
    <label>taux de réduction</label>
    <input type="number" min="0" placeholder="taux réduction" value="<?php echo $userinfo['taux_code'] ?>" name="txc" required/>€</br></br>
    <label>prix min </label>
    <input type="number" min="0" placeholder="prix min" value="<?php echo $userinfo['prixmin_code'] ?>" name="pmc" required/>€</br></br>
    <label>nombre de codes </label>
    <input type="number" min="0" placeholder="nombre de codes" value="<?php echo $userinfo['nmbre_code'] ?>" name="nbc" required/></br></br>
    <input type="submit" name="formcode"/>
    </form>
<?php
} else {
    header("Location: accueil.php");
}
?>