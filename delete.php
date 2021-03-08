<?php
session_start();
if (isset($_SESSION['id'])){
$bdd = new PDO('mysql:host=localhost;dbname=jajaydck_foodtrocs', 'jajaydck_root', '$1E3q_TkSYj6u9');  
$conn = mysqli_connect("localhost", "jajaydck_root", "$1E3q_TkSYj6u9", "jajaydck_foodtrocs");  
$idm = $_SESSION['id'];
$id = $_GET['id'];

$requser = $bdd->prepare("SELECT * FROM produits WHERE id = '$id' AND idMembre = '$idm'");
$requser->execute(array($_SESSION['id']));
$userinfo = $requser->fetch();
$idtest = $userinfo['idMembre'];

if(isset($idtest)){
$sql = "DELETE FROM produits WHERE id = $id"; 
$sql2 = "UPDATE commandes SET id_produit = 'SUPPR' WHERE id_produit = $id"; 

if (mysqli_query($conn, $sql) and mysqli_query($conn, $sql2)) {
    header('Location: mes_plats.php');
}
 else {
    echo "Error deleting record";
}
}else {
    header("location: accueil.php");
}
}else {
    header("location: accueil.php");
}
?>