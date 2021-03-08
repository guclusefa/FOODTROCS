<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=jajaydck_foodtrocs', 'jajaydck_root', '$1E3q_TkSYj6u9');
?>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MDP OUBLIÉ / FOOD TROCS</title>
  <meta charset="UTF-8">
  <link rel="icon" type="image/png" href="images/foodtrocs.1.png" />
</head>

<?php 
if (!empty($_POST['formmail']) and !empty($_POST['mail'])){
    $mail = $_POST['mail'];
    $reqmail = $bdd->prepare("SELECT * FROM membres WHERE mail = '$mail'");
    $reqmail->execute(array($mail));
    $mailexist = $reqmail->rowCount();

    if ($mailexist > 0) {
            $_SESSION['mail_v'] = $mail;
            $code_v = rand();
            $_SESSION['code_v'] = $code_v;
            $titre = 'Réinitialisation mot de passe';
            $contenu = 'votre code de validation : ' .$code_v;
            $from = "From: FOODTROCS <sefa.guclu38600@gmail.com>\nMime-Version:";
            $from .= " 1.0\nContent-Type: text/html; charset=ISO-8859-1\n";
            mail($mail,$titre,$contenu,$from);
            header("location: mdp_oubliebis.php");
    } else {
        $msg = "mail ne correspond à aucun compte !";
    }   
}
if (isset($msg)){
    echo "<font color='red'>".$msg."</font></br></br>";
}
?>

<form method="POST" action="" enctype="multipart/form-data">
<label>Votre email : </label>
<input type="email" placeholder="votre email" required name="mail"/>
<input type="submit" name="formmail"/>
</form>

<?php 
if (isset($_SESSION['id'])) {
  header("Location: accueil.php");
}
?>
