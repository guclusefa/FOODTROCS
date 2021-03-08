<!DOCTYPE html>
<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=jajaydck_foodtrocs', 'jajaydck_root', '$1E3q_TkSYj6u9');
$idm = $_SESSION['id'];
$codenom = "";

if (isset($_SESSION['id']) and (isset($_GET['id']))) {
  $idp = $_GET['id'];
  $admin = $bdd->prepare("SELECT * FROM `admin` WHERE idAdmin = $idm");
  $admin->execute(array($_SESSION['id']));
  $useradmin = $admin->fetch();
  $idAdmin = $useradmin['idAdmin'];

  $requser = $bdd->prepare("SELECT * FROM membres WHERE id = $idm");
  $requser->execute(array($_GET['id']));
  $user = $requser->fetch();
  $monnaie = $user['monnaie'];

  $requser = $bdd->prepare("SELECT * FROM produits , membres WHERE membres.id = produits.idMembre AND produits.id = $idp");
  $requser->execute(array($_GET['id']));
  $produit = $requser->fetch();
  $provenance = $produit['provenance'];
  $type = $produit['type'];
  $prix = $produit['prix'];
    if(isset($_POST['newqte'])){
        $qte = $_POST['newqte'];
        $prix = $prix * $qte;
    }
  $nbVentes = $produit['nbVentes'];
  $idMembre = $produit['idMembre'];
  $localisation = $produit['localisation'];
  if ($produit['idMembre'] == $idm) {
    header("location: mes_plats.php");
  }

  if (isset($_POST['formcode']) and !empty($_POST['newcode'])) {
    $codenom =  $_POST['newcode'];
    $reqcode = $bdd->prepare("SELECT * FROM code_promo WHERE nom_code = '$codenom' AND prixmin_code <= $prix AND nmbre_code > 0");
    $reqcode->execute(array($_POST['newcode']));
    $promocode = $reqcode->fetch();
    $prixpromo = $prix - $promocode['taux_code'];
    $erreurcode = "Le code ". $promocode['nom_code']. " d'une réduction de " .$promocode['taux_code']. "€ a bien été appliquer !";
    if (empty($promocode['nom_code'])){
        $erreurcode = "Ce code a expiré ou ne remplis pas les critères";
    }
  } elseif (isset($_POST['formcode'])) {
        $erreurcode = "aucun code appliquer";
    }

  if (isset($_POST['formnon'])) {
    header('Location: acheter.php?id=' . $idp);
  }

  if (isset($_POST['newcode'])){
    $codenom =  $_POST['newcode'];
    $reqcode = $bdd->prepare("SELECT * FROM code_promo WHERE nom_code = '$codenom' AND prixmin_code <= $prix AND nmbre_code > 0");
    $reqcode->execute(array($_POST['newcode']));
    $promocode = $reqcode->fetch();
    $prixpromo = $prix - $promocode['taux_code'];
    date_default_timezone_set('Europe/Paris');
    $date = date('Y-m-d H:i:s');
    if (isset($_POST['formoui']) and $monnaie >= $prixpromo) {
        $insertp = $bdd->prepare("UPDATE produits SET nbVentes = nbVentes + $qte  WHERE id = $idp");
        $insertp->execute(array($_SESSION['id']));
        $insertm = $bdd->prepare("UPDATE membres SET monnaie = monnaie - $prixpromo, achats = achats + $prixpromo, nbrAchats = nbrAchats + $qte WHERE id = $idm");
        $insertm->execute(array($_SESSION['id']));
        $insertm2 = $bdd->prepare("UPDATE membres SET ventes = ventes + $prixpromo, monnaie = monnaie + $prixpromo,  nbrVentes = nbrVentes + $qte WHERE id = $idMembre");
        $insertm2->execute(array($_SESSION['id']));

        $insertcode = $bdd->prepare("UPDATE code_promo SET nmbre_code = nmbre_code - 1 WHERE nom_code = '$codenom'");
        $insertcode->execute(array($_SESSION['id']));

        $insertcommande = $bdd->prepare("INSERT INTO commandes(id_client, id_vendeur, id_produit, prix_commande, date_commande, qte_commande) VALUES ($idm, $idMembre, $idp, $prixpromo, '$date', $qte)");
        $insertcommande->execute(array($_SESSION['id']));

        $erreur = "ACHAT FAIT AVEC SUCCÈS !";
    } else $erreur = "VOUS N'AVEZ PAS ASSEZ DE SOUS !";
  }

?>
  <html>

  <head>
    <title>ACHETER / FOOD TROCS</title>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/foodtrocs.1.png" />
    <link href="logo/css/all.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Amatic+SC">
    <link rel="stylesheet" href="accueil/accueil.css">
    <link rel='stylesheet' href='https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css'>
    <link rel="stylesheet" href="acheter/acheter.css">
    <link rel="stylesheet" href="popup/style.css">
  </head>

  <body>
    <!-- Navbar (sit on top) -->
    <?php
          if(isset($_POST['rech'])){
    header("location: recherche.php?search=".$_POST['rech']);
}
?>
    <div class="w3-top w3-hide-small">
      <div class="w3-bar w3-xlarge w3-black w3-opacity w3-hover-opacity-off" id="myNavbar">
        <a href="deconnexion.php" style="font-size:1.52em; text-decoration: none;" class="w3-bar-item w3-button, couleurhover" title="Déconnexion !"><i class="fas fa-door-open"></i></a>
        <a href="profil.php" style="font-size:1.52em; text-decoration: none;" class="w3-bar-item w3-button, couleurhover"><?php echo '<img style="height:50px; width:50px; border-radius:50%; object-fit:cover;" src="data:image/jpeg;base64,' . base64_encode($user['photo_profil']) . '"/>'; ?> <?php echo $user['pseudo']; ?></a>
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
    </div></br></br></br>

    <main>
      <div class="container">
        <div class="grid second-nav">
        </div>
        <div class="grid product">
          <div class="column-xs-12 column-md-7">
            <div class="product-gallery">
              <div class="product-image">
              <?php $filename1 = 'images/'.$provenance.'.jpg'; $filename2 = 'images/'.$localisation.'.jpg';?>
                <?php echo '<img class="active" src="data:image/jpeg;base64,' . base64_encode($produit['photo_produit']) . '"/>'; ?>
              </div>
              <ul class="image-list">
              <?php 
                if (file_exists($filename1) or file_exists($filename2)) {?>
                <li class="image-item"><?php echo '<img src="data:image/jpeg;base64,' . base64_encode($produit['photo_produit']) . '"/>'; ?></li>
                <?php }
                if (file_exists($filename1)) {?>
                <li class="image-item"><img src="images/<?php echo $provenance ?>.jpg"></li>
                <?php 
                }
                if (file_exists($filename2)) {?>
                <li class="image-item"><img src="images/<?php echo $localisation ?>.jpg"></li>
                <?php 
                }?>
              </ul>
            </div>
          </div>
          <div class="column-xs-12 column-md-5">
            <h1><b><?php echo $produit['intitule']; ?></b> de <b><?php echo $produit['pseudo']; ?><span style="float:right; color:red;">
            <?php if (isset($promocode['nom_code'])) {
                echo "<span style='text-decoration: line-through;'>". $prix ."€</span></br>";
                echo $prixpromo."€</br>";
            } else {
            echo $prix."€"; }?></b></span></h1>

            <form method="POST" action="" enctype="multipart/form-data">
            <label>Quantité : </label><input type="number" value=<?php if(isset($qte)){echo '"'.$qte.'"';} else {echo '"1"';}?> min="1" max="10" name="newqte" /><input type="submit" value="OK" name="formqte"></br></br>
            CODE PROMOTIONNEL <input type="text" name="newcode" value=<?php echo '"'.$codenom.'"'?>><input type="submit" value="appliquer" name="formcode">
            </br>test : FTROCS5 à partir de 20€ d'achats ou FTROCS20 à pattir de 100€ d'achats
             <?php
            if (isset($erreurcode)) {
              echo '</br><b><font color="red">' . $erreurcode . "</font></b>";
            }
            ?>

            <div class="description">
              <h3>Origine :<b> <?php echo $produit['provenance']; ?></b> </br>vendue près de <b><?php echo $produit['localisation']; ?></b></h3>
              <h3><?php echo nl2br($produit['description']); ?></h3>
            </div> 
              <span class="submitBtn2 bold" class="cd-popup-trigger"><a href="" class="cd-popup-trigger" style="text-decoration:none;">ACHETER</a></button></span>
              <div class="cd-popup" role="alert">
                <div class="cd-popup-container">
                  <h1>êtes-vous sûr de vouloir d'acheter</br> ce plat ?</h1>
                  <ul class="cd-buttons">
                    <li><button class="oui" type="submit" name="formoui">Oui</button></li>
                    <li><button class=" non" type="submit" name="formnon">Non</button</li> </ul> </div> <!-- cd-popup-container -->
                </div> <!-- cd-popup -->
                <!-- partial -->
                <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
                <script src="popup/script.js"></script>
                <span class="submitBtn bold"><a href="catalogue.php" style="text-decoration:none;">Revenir au catalogue</a></button></span>
            </form>
            <?php
            if (isset($erreur) and isset($_POST['formoui'])) {
              echo '<b><font color="red">' . $erreur . "</font></b>";
            }
            ?>
          </div>
        </div>

        <div class="grid related-products">
          <div class="column-xs-12">
            <h1><b>PLUS DE <?php echo $produit['pseudo'] ?> <?php echo "<a style='float:right'class='w3-button w3-xxlarge w3-black' href='user.php?id=" . $produit['id'] . "'><b>Voir tout</b></a>"; ?></b></h1>
          </div>
          <?php
          $bdd2 = mysqli_connect("localhost", "jajaydck_root", "$1E3q_TkSYj6u9", "jajaydck_foodtrocs");
          $sql = "SELECT * FROM membres , produits WHERE membres.id = produits.idMembre AND produits.idMembre = $idMembre AND produits.id <> $idp LIMIT 3";
          if ($result = mysqli_query($bdd2, $sql)) {
            if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_array($result)) {
          ?>
                <div class="column-xs-12 column-md-4">
                  <?php echo "<a href='acheter.php?id=" . $row['id'] . "'>"; ?><?php echo '<img src="data:image/jpeg;base64,' . base64_encode($row['photo_produit']) . '"/>'; ?></a>
                  <h2><b><?php echo $row['intitule'] ?></b></br><?php echo $row['prix'] ?>€</h2>
                  <h4 class="price"><?php echo nl2br($row['description']) ?></br>
                    Origine : <b><?php echo $row['provenance'] ?></b></br>
                    Vendue près de : <b><?php echo $row['localisation'] ?></b></br></h4>
                </div><?php } ?>
        </div>
      </div>
  <?php }
          } ?>
    </main></br></br>


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

    <!-- script -->
    <script src="acheter/acheter.js"></script>

  </body>

  </html>
<?php
}
?>