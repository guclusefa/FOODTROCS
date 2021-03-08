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
    if (isset($_POST['formrnewsl'])) {
        if (isset($_POST['objet']) and isset($_POST['contenu'])) {

            $q = $bdd2->query("SELECT mail FROM membres, newsletter WHERE newsletter.idMembre = membres.id"); // requete
            $compteur=1; // variable pour compter les mails
            while ($r = mysqli_fetch_array($q)) {

            $e_mail = $r['mail']; //prend l'email de la table
            $titre = $_POST['objet'];
            $contenu = nl2br($_POST['contenu']);
            $from = "From: FOODTROCS <sefa.guclu38600@gmail.com>\nMime-Version:";
            $from .= " 1.0\nContent-Type: text/html; charset=ISO-8859-1\n";
            // envoie du mail

            mail($e_mail,$titre,$contenu,$from);
            echo'N° '.$compteur.' - '.$e_mail.' : envoyé avec succès!<br />';
            $compteur++; // ajoute 1 à la variable du compteur
            }
        }
    }
?>

    <head>
        <title>NEWSLETTER / FOODTROCS</title>
        <meta charset="UTF-8">
        <link rel="icon" type="image/png" href="images/foodtrocs.1.png" />
    </head>
    flemme css lol
    <form method="POST" action="" enctype="multipart/form-data">
        <p><textarea type="text" placeholder="Objet" cols="50" rows="6" required name="objet"></textarea></p>
        <p><textarea type="text" placeholder="Contenu" cols="50" rows="6" required name="contenu"></textarea></p>
        <p><input type="submit" name="formrnewsl"></button></p>
    </form>
<?php
} else {
    header("Location: accueil.php");
}
?>
