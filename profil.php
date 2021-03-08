<?php
session_start();

$bdd = new PDO('mysql:host=localhost;dbname=jajaydck_foodtrocs', 'jajaydck_root', '$1E3q_TkSYj6u9');
$id = $_SESSION['id'];
if (isset($_SESSION['id'])) {
  $admin = $bdd->prepare("SELECT * FROM `admin` WHERE idAdmin = $id");
  $admin->execute(array($_SESSION['id']));
  $useradmin = $admin->fetch();
  $idAdmin = $useradmin['idAdmin'];

  $requser = $bdd->prepare("SELECT * FROM membres WHERE id = '$id'");
  $requser->execute(array($_SESSION['id']));
  $userinfo = $requser->fetch();
  $benef = $userinfo['ventes'] - $userinfo['achats'];
  $photo = $userinfo['photo_profil'];
  $photofile = 'data:image/jpeg;base64,' . base64_encode($photo);
  $_SESSION['pseudo'] = $userinfo['pseudo'];
  $_SESSION['mail'] = $userinfo['mail'];

  $requser2 = $bdd->prepare("SELECT * FROM produits WHERE idMembre = ? ORDER BY nbVentes  DESC");
  $requser2->execute(array($_SESSION['id']));
  $produits = $requser2->fetch();
  $p1 = $produits['intitule'];

  $requser3 = $bdd->prepare("SELECT * FROM produits WHERE idMembre = ? ORDER BY nbVentes ASC");
  $requser3->execute(array($_SESSION['id']));
  $produits2 = $requser3->fetch();
  $p2 = $produits2['intitule'];

  $requser4 = $bdd->prepare("SELECT * FROM newsletter WHERE idMembre = '$id'");
  $requser4->execute(array($_SESSION['id']));
  $produits4 = $requser4->fetch();
  $news_id = $produits4['idMembre'];

  if (isset($_POST['newpseudo']) and !empty($_POST['newpseudo']) and $_POST['newpseudo'] != $userinfo['pseudo']) {
    $pseudolength = strlen($_POST['newpseudo']);
    if ($pseudolength <= 20) {
    if (preg_match('/\s/', $_POST['newpseudo'])  == 0) {  
    $pseudo = $_POST['newpseudo'];
    $reqpseudo = $bdd->prepare("SELECT * FROM membres WHERE pseudo = '$pseudo'");
    $reqpseudo->execute(array($pseudo));
    $pseudoexist = $reqpseudo->rowCount();

    if ($pseudoexist == 0){
    $newpseudo = htmlspecialchars($_POST['newpseudo']);
    $insertpseudo = $bdd->prepare("UPDATE membres SET pseudo = ? WHERE id = ?");
    $insertpseudo->execute(array($newpseudo, $_SESSION['id']));
    header('Location: profil.php?id=' . $_SESSION['id']);
    $msg = "Changements effectués";
  } else {
      $msg ="Pseudo indisponible";
  }
  } else {
      $msg = "Votre pseduo ne doit pas contenir d'espaces";
  } } else { $msg = "votre pseudo ne doit pas dépasser 20 caractères";}}

  if (isset($_POST['newnom']) and !empty($_POST['newnom']) and $_POST['newnom'] != $userinfo['nom']) {
    $newnom = htmlspecialchars($_POST['newnom']);
    $insertnom = $bdd->prepare("UPDATE membres SET nom = ? WHERE id = ?");
    $insertnom->execute(array($newnom, $_SESSION['id']));
    header('Location: profil.php?id=' . $_SESSION['id']);
    $msg = "Changements effectués";
  }
  if (isset($_POST['newprenom']) and !empty($_POST['newprenom']) and $_POST['newprenom'] != $userinfo['prenom']) {
    $newprenom = htmlspecialchars($_POST['newprenom']);
    $insertprenom = $bdd->prepare("UPDATE membres SET prenom = ? WHERE id = ?");
    $insertprenom->execute(array($newprenom, $_SESSION['id']));
    header('Location: profil.php?id=' . $_SESSION['id']);
    $msg = "Changements effectués";
  }
  if (isset($_POST['newmail']) and !empty($_POST['newmail']) and $_POST['newmail'] != $userinfo['mail']) {
    if (filter_var($_POST['newmail'], FILTER_VALIDATE_EMAIL)) {  
    $mail = $_POST['newmail'];
    $reqmail = $bdd->prepare("SELECT * FROM membres WHERE mail = '$mail'");
    $reqmail->execute(array($mail));
    $mailexist = $reqmail->rowCount(); 
    if ($mailexist == 0){
    $newmail = htmlspecialchars($_POST['newmail']);
    $insertmail = $bdd->prepare("UPDATE membres SET mail = ? WHERE id = ?");
    $insertmail->execute(array($newmail, $_SESSION['id']));
    header('Location: profil.php?id=' . $_SESSION['id']);
    $msg = "Changements effectués";
  }else {
      $msg ="Mail indisponible";
  }
  } else{
    $msg = "votre adresse mail n'est pas valide !";
  }
  }

  if (isset($_POST['newnum']) and !empty($_POST['newnum']) and $_POST['newnum'] != $userinfo['num']) {
    $newnum = htmlspecialchars($_POST['newnum']);
    $insertnum = $bdd->prepare("UPDATE membres SET num = ? WHERE id = ?");
    $insertnum->execute(array($newnum, $_SESSION['id']));
    header('Location: profil.php?id=' . $_SESSION['id']);
    $msg = "Changements effectués";
  }
  if (isset($_POST['newbio']) and !empty($_POST['newbio']) and $_POST['newbio'] != $userinfo['bio']) {
    $newbio = htmlspecialchars($_POST['newbio']);
    $insertbio = $bdd->prepare("UPDATE membres SET bio = ? WHERE id = ?");
    $insertbio->execute(array($newbio, $_SESSION['id']));
    header('Location: profil.php?id=' . $_SESSION['id']);
    $msg = "Changements effectués";
  }
  if (isset($_POST['newloc']) and !empty($_POST['newloc']) and $_POST['newloc'] != $userinfo['localisation']) {
    $newloc = htmlspecialchars(strtolower($_POST['newloc']));
    $insertloc = $bdd->prepare("UPDATE membres SET localisation = ? WHERE id = ?");
    $insertloc->execute(array($newloc, $_SESSION['id']));
    header('Location: profil.php?id=' . $_SESSION['id']);
    $msg = "Changements effectués";
  }
  if (isset($_POST['newadre']) and !empty($_POST['newadre']) and $_POST['newadre'] != $userinfo['adresse']) {
    $newadre = htmlspecialchars(strtolower($_POST['newadre']));
    $insertadre = $bdd->prepare("UPDATE membres SET adresse = ? WHERE id = ?");
    $insertadre->execute(array($newadre, $_SESSION['id']));
    header('Location: profil.php?id=' . $_SESSION['id']);
    $msg = "Changements effectués";
  }
  if (isset($_POST['newcp']) and !empty($_POST['newcp']) and $_POST['newcp'] != $userinfo['code_postal']) {
    $newcp = htmlspecialchars(strtolower($_POST['newcp']));
    $insertadre = $bdd->prepare("UPDATE membres SET code_postal = ? WHERE id = ?");
    $insertadre->execute(array($newcp, $_SESSION['id']));
    header('Location: profil.php?id=' . $_SESSION['id']);
    $msg = "Changements effectués";
  }
  if (isset($_POST['news']) and empty($news_id) and $_POST['news'] == 'oui'){
    $insertnewnews = $bdd->prepare("INSERT INTO newsletter(idMembre) VALUES ('$id')");
    $insertnewnews->execute();
    header('Location: profil.php?id=' . $_SESSION['id']);
    $msg = "Abonnement à la newsletter avec succès !";
  }
  if (isset($_POST['news']) and !empty($news_id) and $_POST['news'] == 'non'){
    $insertnewnews = $bdd->prepare("DELETE FROM newsletter WHERE idMembre = '$id'");
    $insertnewnews->execute();
    header('Location: profil.php?id=' . $_SESSION['id']);
    $msg = "Desabonnement à la newsletter avec succès !";
  }
  if (isset($_FILES['myfile']['tmp_name']) and !empty($_FILES['myfile']['tmp_name'])) {
    $data = file_get_contents($_FILES['myfile']['tmp_name']);
    $stmt = $bdd->prepare("UPDATE membres SET photo_profil = ? WHERE id=$id");
    $stmt->bindParam(1, $data);
    $stmt->execute();
    header('Location: profil.php?id=' . $_SESSION['id']);
  }
  if (!@getimagesize($photofile)) {
      $img = 'images/dp.png';
        $data = file_get_contents($img);
        $stmt = $bdd->prepare("UPDATE membres SET photo_profil = ? WHERE id=$id");
        $stmt->bindParam(1, $data);
        $stmt->execute();
        header('Location: profil.php?id=' . $_SESSION['id']);
    }
  if (isset($_POST['newmdp0']) and !empty($_POST['newmdp0']) or isset($_POST['newmdp1']) and !empty($_POST['newmdp1']) or isset($_POST['newmdp2']) and !empty($_POST['newmdp2'])) {
    $mdp0 = sha1($_POST['newmdp0']);
    $mdp1 = sha1($_POST['newmdp1']);
    $mdp2 = sha1($_POST['newmdp2']);
    if (!empty($mdp0) and !empty($mdp1) and !empty($mdp2)) {
    if ($mdp0 == $userinfo['motedepasse']){
    if ($mdp1 == $mdp2) {
      $mdplength = strlen($_POST['newmdp1']);
      if ($mdplength >= 4) {   
      if (preg_match('/\s/', $_POST['newmdp1'])  == 0) {    
      $insertmdp = $bdd->prepare("UPDATE membres SET motedepasse = ? WHERE id = ?");
      $insertmdp->execute(array($mdp1, $_SESSION['id']));
      $msg = "Changements effectués";
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
      $msg = "Mauvais mot de passe actuel !";
  }
  } else {
      $msg = "Tous les champs doivent être complétés !";
  }
  }
?>
  <?php
  if (isset($_POST['ajout'])) {
    $ajout = htmlspecialchars($_POST['ajout']);
    $insertmonnaie = $bdd->prepare("UPDATE membres SET monnaie = monnaie + ? WHERE id = ?");
    $insertmonnaie->execute(array($ajout, $_SESSION['id']));
    header('Location: profil.php?id=' . $_SESSION['id']);
  }
  if (isset($_POST['retire'])) {
    $retire = htmlspecialchars($_POST['retire']);
    $takemonnaie = $bdd->prepare("UPDATE membres SET monnaie = monnaie - ? WHERE id = ?");
    $takemonnaie->execute(array($retire, $_SESSION['id']));
    header('Location: profil.php?id=' . $_SESSION['id']);
  }
  ?>
  <!DOCTYPE html>
  <html>

  <head>
    <title>MON PROFIL / FOOD TROCS</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/png" href="images/foodtrocs.1.png" />
    <link href="logo/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Amatic+SC">
    <link rel="stylesheet" href="accueil/accueil.css">
    <link rel="stylesheet" href="profil/profil.css">
  </head>
<style>       
        .lbl { 
            display: table-cell; 
            width: 1px; 
            white-space: nowrap;
        } 
          
        input[type=text],input[type=password], input[type=number], input[type=email], input[type=tel], input[type=submit]  { 
            display: table-cell; 
            padding: 0 4px 0 6px; 
            width: 100%;
        } 
</style>
  <body class="w3-light-grey">
    <div class="backg">


      <div class="w3-top w3-hide-small">
        <div class="w3-bar w3-xlarge w3-black w3-opacity w3-hover-opacity-off" id="myNavbar">
        <?php
          if(isset($_POST['rech'])){
    header("location: recherche.php?search=".$_POST['rech']);
}
?>
          <a href="deconnexion.php" style="font-size:1.52em; text-decoration: none;" class="w3-bar-item w3-button, couleurhover" title="Déconnexion !"><i class="fas fa-door-open"></i></a>
          <a href="profil.php" style="font-size:1.52em; text-decoration: none;" class="w3-bar-item w3-button, couleurhover">
          <?php echo '<img style="height:50px; width:50px; border-radius:50%; object-fit:cover;" src="data:image/jpeg;base64,' . base64_encode($userinfo['photo_profil']) . '"/>'; ?>
          <?php echo $userinfo['pseudo']; ?></a>
          <a href="mes_plats.php" style="font-size:1.52em; text-decoration: none;" class="w3-bar-item w3-button, couleurhover"><i class="fas fa-hamburger"></i> MES PLATS</a>
          <a href="catalogue.php" style="font-size:1.52em; text-decoration: none;" class="w3-bar-item w3-button, couleurhover"><i class="fas fa-shopping-cart"></i> CATALOGUE</a>
          <a href="classement.php" style="font-size:1.52em; text-decoration: none;" class="w3-bar-item w3-button, couleurhover"><i class="fas fa-award"></i> CLASSEMENT</a>
          <?php if (isset($idAdmin)) { ?>
            <a href="admin.php" style="font-size:1.52em; text-decoration: none;" class="w3-bar-item w3-button, couleurhover"><i class="fas fa-user-shield"></i> PAGE ADMIN</a>
          <?php } ?>
          <a href="accueil.php"><img class="w3-bar-item w3-button, couleurhover" style="height:70px; float:right" src="images/foodtrocs3.png" /></a>
          <form method="post">
          <input autocomplete="off" name="rech" id="rech" type="text" placeholder="Rechercher..." class="w3-bar-item" style="font-size:1.52em; background-color:black; color:white; margin-left:-1%;float:right"/>
          <button href="recherche.php" style="font-size:1.52em; background-color:black; color:white; cursor :pointer;float:right;" class="w3-bar-item couleurhover" title="Recherche !"><i class="fas fa-search couleurhover cursor :pointer;"></i></button>
          </form>
        </div>
      </div>
      </br></br></br>

      <!-- Page Container -->
      <div class="w3-content w3-margin-top" style="max-width:1400px;">

        <!-- The Grid -->
        <div class="w3-row-padding">

          <!-- Left Column -->
          <div class="w3-third">

            <div class="w3-white w3-text-grey w3-card-4">
              <div class="w3-display-container">
                <?php echo '<img style="width:100%;" src="data:image/jpeg;base64,' . base64_encode($userinfo['photo_profil']) . '"/>'; ?></br></br></br></br></br></br>
                <div class="w3-display-bottomleft w3-container w3-text-black">
                  <h2><?php echo $userinfo['prenom']; ?> "<?php echo $userinfo['pseudo']; ?>" <?php echo $userinfo['nom']; ?></br> <?php if (isset($idAdmin)) {
                                                                                                                                      echo "<font color='#9a0606'><i class='fas fa-user-shield' style='color:#9a0606'></i> <b>admin</b></font>";
                                                                                                                                    } ?></h2>
                </div>
              </div>
              <div class="w3-container">
                <?php if($userinfo['connect'] == 1){?>
                <p style="color:green"><i class="fas fa-circle fa-fw w3-margin-right w3-large" style="color:green"></i><b>Statut : Connecté</b></p>
                <?php }?>
                <?php if($userinfo['connect'] == 2){?>
                <p><i class="fa fa-info fa-fw w3-margin-right w3-large"></i><b>Statut : Inactif</b></p>
                <?php }?>
                <?php if($userinfo['connect'] == 3){?>
                <p><i class="fa fa-info fa-fw w3-margin-right w3-large"></i><b>Statut : Déconnecté</b></p>
                <?php }?>
                <p><i class="fa fa-info fa-fw w3-margin-right w3-large"></i><b><?php echo nl2br($userinfo['bio']); ?></b></p>
                <p><i class="fa fa-home fa-fw w3-margin-right w3-large"></i><b><?php echo $userinfo['adresse']; ?></b></br>
                <i class="fa fa-home fa-fw w3-margin-right w3-large" style="color:white"></i><b><?php echo $userinfo['localisation']; ?> / <?php echo $userinfo['code_postal']; ?></b></p>
                <p><i class="fa fa-envelope fa-fw w3-margin-right w3-large"></i><b><?php echo $userinfo['mail']; ?></b></p>
                <p><i class="fa fa-phone fa-fw w3-margin-right w3-large"></i><b><?php echo $userinfo['num']; ?></b></p>
                <p><i class="fa fa-calendar fa-fw w3-margin-right w3-large"></i>Date d'inscription : <b><?php echo $userinfo['date']; ?></b></p>
                <p><i class="fab fa-orcid fa-fw w3-margin-right w3-large"></i>id : <b><?php echo $userinfo['id']; ?></b></p>
                <hr>

                <p class="w3-large"><b><i class="fas fa-chart-bar wave-alt fa-fw w3-margin-right"></i>Statistiques</b></p>
                <p><a href="mes_ventes.php">Ventes: <b><?php echo $userinfo['nbrVentes']; ?></b> ventes soit <b><?php echo $userinfo['ventes']; ?>€</b></a></p>
                <p><a href="mes_achats.php">Achats : <b><?php echo $userinfo['nbrAchats']; ?></b> achats soit <b><?php echo $userinfo['achats']; ?>€</b></a></p>
                <p>Bénéfices : <b><?php echo $benef ?>€</b></p>
                <p>Plat le plus vendue : <b><?php echo $p1 ?></b></p>
                <p>Plat le moins vendue : <b><?php echo $p2 ?></b></p>
              </div>
            </div><br>

            <!-- End Left Column -->
          </div>

          <!-- Right Column -->
          <div class="w3-twothird">

            <div class="w3-container w3-card w3-white w3-margin-bottom">
              <h1 class="w3-text-grey w3-padding-16"><b><i class="fas fa-pen fa-fw w3-margin-right w3-xxlarge"></i>Edition de mon profil</b></h1>
              <div class="w3-container">
                <form method="POST" action="" enctype="multipart/form-data">
                  <label class="lbl">Photo de profil :</label>
                  <input type="file" name="myfile"  accept="image/*" style="border:1px black;"/> </br></br>
                  <label class="lbl">Pseudo :</label>
                  <input type="text" name="newpseudo" placeholder="Pseudo" value="<?php echo $userinfo['pseudo']; ?>" size="25" /><br /><br />
                  <label class="lbl">Nom :</label>
                  <input type="text" name="newnom" placeholder="Nom" value="<?php echo $userinfo['nom']; ?>" size="25" /><br /><br />
                  <label class="lbl">Prenom :</label>
                  <input type="text" name="newprenom" placeholder="Prenom" value="<?php echo $userinfo['prenom']; ?>" size="25" /><br /><br />
                  <label class="lbl">Mail :</label>
                  <input type="email" name="newmail" placeholder="Mail" value="<?php echo $userinfo['mail']; ?>" size="40" /><br /><br />
                  <label class="lbl" for ="newbio" style="vertical-align:top;">Votre bio:</label>
                  <textarea style="max-width: 100%;width:100%;" type="text"  rows="6" name="newbio" placeholder="Biographie"><?php echo $userinfo['bio']; ?></textarea><br /><br />
                  <label class="lbl">Adresse :</label>
                  <input type="text" name="newadre" placeholder="Adresse" value="<?php echo $userinfo['adresse']; ?>" size="54" /><br /><br />
                  <label class="lbl" style="display: inline-block">Ville :</label><label style="width:40%;float:right;display: inline-block">Code postal :</label></br>
                  <input style="width:55%;" type="text" name="newloc" placeholder="Ville" value="<?php echo $userinfo['localisation']; ?>"/>
                  <input style="width:40%;float:right;" type="text" name="newcp" placeholder="Code Postal" value="<?php echo $userinfo['code_postal']; ?>" size="25" pattern="[0-9]*"/><br /><br />
                  <label class="lbl">Numéro :</label>
                  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
                  <script>
                  $(document).ready(function () {
    $("#newnum").keyup(function (e) {
      if($(this).val().length === 14) return;
      if(e.keyCode === 8 || e.keyCode === 37 || e.keyCode === 39) return;
      let newStr = '';
      let groups = $(this).val().split('-');
      for(let i in groups) {
       if (groups[i].length % 2 === 0) {
        newStr += groups[i] + "-"
       } else {
        newStr += groups[i];
       }
      }
      $(this).val(newStr);
    });
})
</script>
                  <input type="tel" id="newnum" name="newnum" placeholder="Num" pattern="[0]{1}[4-9]{1}-[0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}" maxlength="14" value="<?php echo $userinfo['num']; ?>" size="25" /> format : <b>06-12-34-56-78</b><br /><br />
                  <label class="lbl">Mot de passe actuel :</label>
                  <input type="password" name="newmdp0" placeholder="Mot de passe actuel" size="30" /><br /><br />
                  <label class="lbl">Nouveau mot de passe :</label>
                  <input type="password" name="newmdp1" placeholder="Mot de passe" size="30" /><br /><br />
                  <label class="lbl">Confirmation nouveau mot de passe :</label>
                  <input type="password" name="newmdp2" placeholder="Confirmation du mot de passe" size="40" /><br /><br />
                  <span style="width:50%;"><label for="news">Abonner à la newsletter </label><input type="radio" id="news" name="news" value="oui" <?php if(!empty($news_id)){echo 'CHECKED';}?>/></span>
                  <span style="float:right"><label for="news2">Désabonner à la newsletter </label><input type="radio" id="news2" name="news" value="non" <?php if(empty($news_id)){echo 'CHECKED';}?>/></span></br></br>
                  <input class="test2" type="submit" value="Mettre à jour mon profil !" /></br></br>
                </form>
                <?php
                if (isset($msg)) {
                  echo '<font color="red">' . $msg . "</font>";
                }
                ?>
              </div>
            </div>

            <div class="w3-container w3-card w3-white w3-margin-bottom">
              <h1 class="w3-text-grey w3-padding-16"><b><i class="fas fa-money-bill-wave-alt fa-fw w3-margin-right w3-xxlarge"></i>Porte monnaie</b></h1>
              <div class="w3-container">
                <form method="POST" action="" enctype="multipart/form-data">
                  Vous avez : <strong style="font-size:40px"><?php echo $userinfo['monnaie'] ?>€</strong></br></br>
                  <label>Ajoutez :</label>
                  <input type="number" name="ajout" placeholder="Ajoutez" min="1" max="50" /><span style="margin-left:-35px;">€</span><br /><br />
                  <label>Retirer :</label>
                  <input type="number" name="retire" placeholder="Retirer" min="1" max="<?php echo $userinfo['monnaie']; ?>"/><span style="margin-left:-35px;">€</span><br /><br />
                  <input class="test2" type="submit" value="Confirmer !" /></br></br>
                </form>
              </div>
            </div>

            <?php
            $bdd2 = mysqli_connect("localhost", "jajaydck_root", "$1E3q_TkSYj6u9", "jajaydck_foodtrocs");
            $sql = "SELECT * FROM faq WHERE idMembre = $id ORDER BY reponse DESC";
            if ($result = mysqli_query($bdd2, $sql)) {
              if (mysqli_num_rows($result) > 0) {
            ?>
                <div class="w3-container w3-card w3-white w3-margin-bottom">
                  <h1 class="w3-text-grey w3-padding-16"><b><i class="fas fa-question fa-fw w3-margin-right w3-xxlarge"></i>Mes questions</b></h1>
                  <div class="w3-container">
                    <form method="POST" action="" enctype="multipart/form-data">
                      <?php while ($row = mysqli_fetch_array($result)) { ?>
                        <h2>Envoyé le <b><?php echo $row['date_q']; ?></b></br>
                          <?php echo nl2br($row['question']); ?></br>
                          <?php if ($row['reponse'] == 'AUCUNE REPONSE') {
                            echo '<font color="red"><b>' . nl2br($row['reponse']) . '</font></b></br></h2>';
                          } else { ?>
                            <b>
                              <font color="green">Répondue le <?php echo $row['date_r'];?> par <a href="user.php?id=<?php echo $row['id_a']; ?>"><?php echo $row['pseudo_a']; ?></a>
                            </b></font></br>
                            <?php echo nl2br($row['reponse']); ?></br></h2><?php } ?></br>
                <?php }
                    }
                  } ?>
                    </form>
                  </div>
                </div>
          </div>

          <!-- End Right Column -->
        </div>

        <!-- End Grid -->
      </div>

      <!-- End Page Container -->
    </div>

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
  </body>

  </html>
<?php
} else {
  header("Location: accueil.php");
}
?>