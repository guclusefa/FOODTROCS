<?php
error_reporting(0); 
session_start();
if (isset($_SESSION['id'])) {
  header("Location: accueil.php");
}
$bdd = new PDO('mysql:host=localhost;dbname=jajaydck_foodtrocs', 'jajaydck_root', '$1E3q_TkSYj6u9');
require 'recaptchalib.php';
$siteKey = '6LcCBtwZAAAAAMOfmpmzFneF5Lfc6oKZjOWI259N'; // votre clé publique
$secret = '6LcCBtwZAAAAAPnjkjOA-Q8KS8T7WU6QMoGEk8cv'; // votre clé privéee

if (isset($_POST['formconnexion'])) {
  $mailconnect = htmlspecialchars($_POST['mailconnect']);
  $mdpconnect = sha1($_POST['mdpconnect']);
  if (!empty($mailconnect) and !empty($mdpconnect)) {
    $requser = $bdd->prepare("SELECT * FROM membres WHERE mail = '$mailconnect' AND motedepasse = '$mdpconnect' OR pseudo = '$mailconnect' AND motedepasse = '$mdpconnect'");
    $requser->execute(array($mailconnect, $mdpconnect));
    $userexist = $requser->rowCount();
    if ($userexist == 1) {
      $userinfo = $requser->fetch();
      $_SESSION['id'] = $userinfo['id'];
      $id = $_SESSION['id'];
      $_SESSION['pseudo'] = $userinfo['pseudo'];
      $_SESSION['mail'] = $userinfo['mail'];
      $newstatut = 1;
              $statut = $bdd->prepare("UPDATE membres SET connect = $newstatut WHERE id = $id ");
              $statut->execute(array($newstatut, $_SESSION['id']));
      header("Location: accueil.php?id=");
    } else {
      $erreur = "Mauvais identifiant ou mot de passe !";
    }
  } else {
    $erreur = "Tous les champs doivent être complétés !";
  }
}
?>

<?php
if (isset($_POST['forminscription'])) {
    $reCaptcha = new ReCaptcha($secret);
    if(isset($_POST["g-recaptcha-response"])) {
    $resp = $reCaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $_POST["g-recaptcha-response"]
        );
    if ($resp != null && $resp->success) {
  $pseudo = htmlspecialchars($_POST['pseudo']);
  $nom = htmlspecialchars($_POST['nom']);
  $prenom = htmlspecialchars($_POST['prenom']);
  $adre = htmlspecialchars($_POST['adre']);
  $codep = htmlspecialchars($_POST['codep']);
  $loca = htmlspecialchars(strtolower($_POST['loca']));
  $img = 'images/dp.png';
  $data = file_get_contents($img);
  $mail = htmlspecialchars($_POST['mail']);
  $mail2 = htmlspecialchars($_POST['mail2']);
  $mdp = sha1($_POST['mdp']);
  $mdp2 = sha1($_POST['mdp2']);
  date_default_timezone_set('Europe/Paris');
  $date = date('Y-m-d H:i:s');

  if (!empty($_POST['pseudo']) and !empty($_POST['nom']) and !empty($_POST['prenom']) and !empty($_POST['loca']) and !empty($_POST['mail']) and !empty($_POST['mail2']) and !empty($_POST['mdp']) and !empty($_POST['mdp2']) and !empty($_POST['adre']) and !empty($_POST['codep'])) {

    $pseudolength = strlen($pseudo);
    if ($pseudolength <= 20) {
    if (preg_match('/\s/', $pseudo)  == 0) {
      if ($mail == $mail2) {
        if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
          $reqmail = $bdd->prepare("SELECT * FROM membres WHERE mail = ?");
          $reqmail->execute(array($mail));
          $mailexist = $reqmail->rowCount();

          $reqpseudo = $bdd->prepare("SELECT * FROM membres WHERE pseudo = ?");
          $reqpseudo->execute(array($pseudo));
          $pseudoexist = $reqpseudo->rowCount();
          if ($mailexist == 0 AND $pseudoexist == 0) {

            if ($mdp == $mdp2) {
              $mdplength = strlen($_POST['mdp']);
              if ($mdplength >= 4) {   
              if (preg_match('/\s/', $_POST['mdp'])  == 0) {    
              $insertmbr = $bdd->prepare("INSERT INTO membres(pseudo, nom, prenom, localisation, photo_profil, mail, motedepasse, date, adresse, code_postal) VALUES (?,?,?,?,?,?,?,'$date', '$adre', '$codep')");
              $insertmbr->execute(array($pseudo, $nom, $prenom, $loca, $data, $mail, $mdp));
              $insertmbr->bindParam(5, $data);

            $requsernws = $bdd->prepare("SELECT * FROM membres WHERE pseudo = '$pseudo'");
            $requsernws->execute();
            $userinfonws = $requsernws->fetch();
            $id_nws = $userinfonws['id'];

            $insertmbr2 = $bdd->prepare("INSERT INTO newsletter(idMembre) VALUES ('$id_nws')");
            $insertmbr2->execute();


              $erreur = "Votre compte a bien été crée !"; 
            } else {
              $erreur = "Votre mot de passe ne doit pas contenir d'espaces";
            }
            } else {
              $erreur = "Votre mot de passe doit avoir un minimum de 4 caractères";
            }
            } else {
              $erreur = "Vos mots de passe ne correspondent pas";
            }
          } else {
            $erreur = "Adresse mail ou pseudo déjà utilisée !";
          }
        } else {
          $erreur = "votre adresse mail n'est pas valide !";
        }
      } else {
        $erreur = "Vos adresses mail ne correspondent pas !";
      }
    } else{
        $erreur = "votre pseudo ne doit pas contenir d'espaces";
    }
    } else {
      $erreur = "votre pseudo ne doit pas dépasser 20 caractères";
    }
  } else {
    $erreur = "tous les champs doivent être complétés !";
  }
}else {
    $erreur = "Veuillez valider le reCAPTCHA !";
  }
}else {
    $erreur = "Veuillez valider le reCAPTCHA !";
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="connexion/connexion.css">
  <title>Connexion / Inscription / FOOD TROCS</title>
  <meta charset="UTF-8">
  <link rel="icon" type="image/png" href="images/foodtrocs.1.png" />
  <link href="logo/css/all.css" rel="stylesheet">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Amatic+SC">
  <link rel="stylesheet" href="accueil/accueil.css">
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<style>
  .backg {
    background: url("images/fond-gris-peint_53876-94041.jpg");
  }
  /* vertical line */
.vl {
  position: absolute;
  left: 50%;
  transform: translate(-50%);
  border: 2px solid #ddd;
  height :96%;
}
</style>

<div class="backg">
  <div class="w3-top w3-hide-small">
    <div class="w3-bar w3-xlarge w3-black w3-opacity w3-hover-opacity-off" id="myNavbar">
      <a href="connexion.php" style="font-size:1.52em; text-decoration: none;" class="w3-bar-item w3-button, couleurhover"><i class="fa fa-fw fa-user"></i> CONNEXION / INCRIPTION</a>
      <a href="accueil.php"><img class="w3-bar-item w3-button, couleurhover" style="height:70px; float:right" src="images/foodtrocs3.png" /></a>
    </div>
  </div>
  </br></br></br>

  <div class="container">
    <div class="row">
      <div class="vl">
        <span class="vl-innertext">ou</span>
      </div>

      <div class="col">
        <form method="POST" action="">
          <h1 style="text-align:center"><b>Connexion</b></h1>
          <input type="text" name="mailconnect" placeholder="Adresse e-mail ou pseudo"></br></br>
          <input type="password" name="mdpconnect" placeholder="Mot de passe"></br>
          <a href="mdp_oublie.php" style="text-decoration:none;color:white;">mot de passe oublié</a></br></br>
          <input type="submit" name="formconnexion" value="Se connecter">
        </form>
        <?php
        if (isset($erreur) and isset($_POST['formconnexion'])) {
          echo '<b><font color="red">' . $erreur . "</font></b>";
        }
        ?>
      </div>

      <div class="col">
        <div class="hide-md-lg">
          <p>Ou</p>
        </div>
        <div>
          <h1 style="text-align:center"><b>Inscription</b></h1>
          <form method="POST" action="" enctype="multipart/form-data">
            <input type="text" placeholder="Votre nom" id="nom" name="nom"></br></br>
            <input type="text" placeholder="Votre prenom" id="pseudo" name="prenom"></br></br>
            <input type="text" placeholder="Votre pseudo" id="pseudo" name="pseudo"></br></br>
            <input type="text" placeholder="Adresse" id="adre" name="adre"></br></br>
            <input type="text" placeholder="Ville" id="loca" name="loca" style="width:58%;float:left;">
            <input type="text" placeholder="Code Postal" id="codep" name="codep" style="width:40%;float:right;" pattern="[0-9]*"></br></br></br>
            <input type="email" placeholder="Votre mail" id="mail" name="mail"></br></br>
            <input type="email" placeholder="Confirmez votre mail" id="mail2" name="mail2"></br></br>
            <input type="password" placeholder="Votre mot de passe" id="mdp" name="mdp"></br></br>
            <input type="password" placeholder="Confirmez votre mdp" id="mdp2" name="mdp2"></br></br>
            <div class="g-recaptcha" data-sitekey="<?php echo $siteKey; ?>"></div></br>
            <input type="submit" type="submit" name="forminscription" value="Je m'inscris">
          </form>
          <?php
          if (isset($erreur) and isset($_POST['forminscription'])) {
            echo '<font color="red"><b>' . $erreur . "</b></font>";
          }
          ?>
        </div>

      </div>
    </div>
  </div>
</div>
</body>
<!-- Footer -->
<footer class="w3-center w3-black w3-padding-48 w3-xxlarge">
        <img class="lol" src="images/foodtrocs3.png" />
        <div class="w3-row">
          <div class="w3-col s6">
            <p><a href="faq.php">FAQ</a></p>
            <p><a href="contact.php">Nous contacter</a></p>
            <p><a href="cuisine.php">Type de cuisine</a></p>
          </div>
          <div class="w3-col s6">
            <p><a href="a-propos.php">A propos</a></p>
            <p><a href="confidentialite.php">Confidentialité</a></p>
            <p><a href="cookies.php">Cookies</a></p>
          </div>
          <div style="margin-bottom:-70px;" align="center">
            <p>crée par le grand sefacilee</p>
          </div>
        </div>
      </footer>
</html>