<?php
session_start();
$bdd2 = new PDO('mysql:host=localhost;dbname=jajaydck_foodtrocs', 'jajaydck_root', '$1E3q_TkSYj6u9');
if (isset($_SESSION['id'])) {
  $id = $_SESSION['id'];
  $newstatut = 0;
    $statut = $bdd2->prepare("UPDATE membres SET connect = $newstatut WHERE id = $id");
    $statut->execute(array($newstatut, $_SESSION['id']));
}
session_destroy();
header("Location: accueil.php");
?>