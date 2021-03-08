<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=jajaydck_foodtrocs', 'jajaydck_root', '$1E3q_TkSYj6u9');
$bdd2 = mysqli_connect("localhost", "jajaydck_root", "$1E3q_TkSYj6u9", "jajaydck_foodtrocs");
$id = $_SESSION['id'];
$pseudo = $_SESSION['pseudo'];
$idp = $_GET['id'];
date_default_timezone_set('Europe/Paris');
$date = date('Y-m-d H:i:s');
$admin = $bdd->prepare("SELECT * FROM `admin` WHERE idAdmin = $id");
$admin->execute(array($_SESSION['id']));
$useradmin = $admin->fetch();
$idAdmin = $useradmin['idAdmin'];

$requser = $bdd->prepare("SELECT * FROM faq WHERE id = $idp");
$requser->execute(array($_SESSION['id']));
$userinfo = $requser->fetch();
if (isset($idAdmin)) {
    if (isset($_POST['formreponse'])) {
        if (isset($_POST['reponse'])) {
            $reponse = htmlspecialchars($_POST['reponse']);
            $insertrep = $bdd->prepare("UPDATE faq SET reponse = ? , date_r = ?, pseudo_a = ?, id_a = ? WHERE id = ?");
            $insertrep->execute(array($reponse, $date, $pseudo, $id, $idp));

            $titre = 'Réponse à votre question';
            $contenu = 'Bonjour '.$userinfo['pseudo'].'! <br /><br />Question : '.nl2br($userinfo['question']).'<br /><br />';
            $contenu .= 'Réponse : '.nl2br($reponse);
            $contenu .= "<br /><br />Bien cordialement,<br />L'équipe FOODTROCS(juste moi mdr)";
            $from = "From: FOODTROCS <sefa.guclu38600@gmail.com>\nMime-Version:";
            $from .= " 1.0\nContent-Type: text/html; charset=ISO-8859-1\n";
            // envoie du mail
            mail($userinfo['mail'],$titre,$contenu,$from);
            echo "<font color='red'>reponse avec succés</font></br></br>";
            header("Refresh: 2;url=admin.php");
        }
    }
?>

    <head>
        <title>REPONDRE / FOODTROCS</title>
        <meta charset="UTF-8">
        <link rel="icon" type="image/png" href="images/foodtrocs.1.png" />
    </head>
    flemme css lol
    <form method="POST" action="" enctype="multipart/form-data">
        <p><input type="text" placeholder="Nom" value="<?php echo $userinfo['pseudo']; ?>" size="48" readonly required name="pseudo"></p>
        <p><input type="email" placeholder="Adresse e-mail" value="<?php echo $userinfo['mail']; ?>" size="48" readonly required name="mail"></p>
        <p><textarea type="text" placeholder="Question" cols="50" rows="6" readonly required name="question"><?php echo $userinfo['question']; ?></textarea></p>
        <p><textarea type="text" placeholder="Réponse" cols="50" rows="6" required name="reponse"></textarea></p>
        <p><input type="submit" name="formreponse"></button></p>
    </form>
<?php
} else {
    header("Location: accueil.php");
}
?>
