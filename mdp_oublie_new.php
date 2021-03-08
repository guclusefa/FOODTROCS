<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=jajaydck_foodtrocs', 'jajaydck_root', '$1E3q_TkSYj6u9');
$mail_v = $_SESSION['mail_v'];

if (isset($_POST['formmdp'])) {
    $mdp1 = sha1($_POST['newmdp1']);
    $mdp2 = sha1($_POST['newmdp2']);
    if (!empty($mdp1) and !empty($mdp2)) {
    if ($mdp1 == $mdp2) {
      $mdplength = strlen($_POST['newmdp1']);
      if ($mdplength >= 4) {   
      if (preg_match('/\s/', $_POST['newmdp1'])  == 0) {    
      $insertmdp = $bdd->prepare("UPDATE membres SET motedepasse = '$mdp1' WHERE mail = '$mail_v'");
      $insertmdp->execute();
      $msg = "Changements effectués !";
      session_destroy();
      header("Refresh: 2;url=connexion.php");
    } else {
     $msg = "Votre mot de passe ne doit pas contenir d'espaces";
    }
    } else {
     $msg = "Votre mot de passe doit avoir un minimum de 4 caractères";
    }
    } else {
      $msg = "Vos deux mdp ne correspondent pas !";
    }
  } else {
      $msg = "Tous les champs doivent être complétés !";
  }
  }
if (isset($msg)){
    echo "<font color='red'>".$msg ."</font></br></br>";
}
?>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MDP OUBLIÉ / FOOD TROCS</title>
  <meta charset="UTF-8">
  <link rel="icon" type="image/png" href="images/foodtrocs.1.png" />
</head>
<form method="POST" action="" enctype="multipart/form-data">
<label class="lbl">Nouveau mot de passe :</label>
<input type="password" name="newmdp1" placeholder="Mot de passe" size="30" required/><br /><br />
<label class="lbl">Confirmation nouveau mot de passe :</label>
<input type="password" name="newmdp2" placeholder="Confirmation du mot de passe" size="40" required/><br /><br />
<input type="submit" name="formmdp"/>
</form>
<?php
if (empty($_SESSION['test'])) {
  header("Location: accueil.php");
}
if (isset($_SESSION['id'])) {
  header("Location: accueil.php");
}
?>