<!DOCTYPE html>
<?php
session_start();
$idm = $_SESSION['id'];
if (isset($_SESSION['id'])) {
  $bdd2 = new PDO('mysql:host=localhost;dbname=jajaydck_foodtrocs', 'jajaydck_root', '$1E3q_TkSYj6u9');
  $requser = $bdd2->prepare("SELECT * FROM membres WHERE id = ?");
  $requser->execute(array($_SESSION['id']));
  $user = $requser->fetch();
  $id = $user['id'];

  $admin = $bdd2->prepare("SELECT * FROM `admin` WHERE idAdmin = $idm");
  $admin->execute(array($_SESSION['id']));
  $useradmin = $admin->fetch();
  $idAdmin = $useradmin['idAdmin'];
  $bdd = mysqli_connect("localhost", "jajaydck_root", "$1E3q_TkSYj6u9", "jajaydck_foodtrocs");
  $sql = "SELECT * FROM membres, produits WHERE membres.id = produits.idMembre AND `type`= 'entree' AND produits.idMembre <> $id";
  $sql2 = "SELECT * FROM membres, produits WHERE membres.id = produits.idMembre AND `type`= 'plat chaud' AND produits.idMembre <> $id";
  $sql3 = "SELECT * FROM membres, produits WHERE membres.id = produits.idMembre AND `type`= 'dessert' AND produits.idMembre <> $id";
  $sql4 = "SELECT * FROM membres, produits WHERE membres.id = produits.idMembre AND provenance = 'Turquie' AND produits.idMembre <> $id";
?>

  <html>

  <head>
    <title>CATALOGUE / FOOD TROCS</title>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/foodtrocs.1.png" />
    <link href="logo/css/all.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Amatic+SC">
    <link rel="stylesheet" href="accueil/accueil.css">
    <link rel="stylesheet" href="catalogue/catalogue.css">

  <body>

    <!-- Navbar (sit on top) -->
    <?php
          if(isset($_POST['rech'])){
    header("location: recherche.php?search=".$_POST['rech']);
}
?>
    <div class="w3-top w3-hide-small">
      <div class="w3-bar w3-xlarge w3-black w3-opacity w3-hover-opacity-off" id="myNavbar">
        <?php
        if (isset($_SESSION['id'])) {
          $requser = $bdd2->prepare("SELECT * FROM membres WHERE id = ?");
          $requser->execute(array($_SESSION['id']));
          $userinfo = $requser->fetch();
        ?>
          <a href="deconnexion.php" style="font-size:1.52em; text-decoration: none;" class="w3-bar-item w3-button, couleurhover" title="Déconnexion !"><i class="fas fa-door-open"></i></a>
          <a href="profil.php" style="font-size:1.52em; text-decoration: none;" class="w3-bar-item w3-button, couleurhover"><?php echo '<img style="height:50px; width:50px; border-radius:50%; object-fit:cover;" src="data:image/jpeg;base64,' . base64_encode($userinfo['photo_profil']) . '"/>'; ?> <?php echo $userinfo['pseudo']; ?></a>
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
      </div><?php } ?>
    </div></br></br></br></br>

    <?php
    $bg = array('images/lol1.jpg', 'images/lol2.jpg', 'images/enon.jpg'); // array of filenames

    $i = rand(0, count($bg) - 1); // generate random number size of the array
    $selectedBg = "$bg[$i]"; // set variable equal to which random filename was chosen
    ?>
    <style>
      .bgimg1 {
        background-repeat: no-repeat;
        background-image: url("<?php echo $selectedBg; ?>");
        background-size: 100% auto;
        min-height: 90%;
        margin-top: -100px;
        font-weight: bold;
      }
    </style>

    <!-- Header with image -->
    <header class="bgimg1 w3-display-container w3-grayscale-min" id="home">
      <div class="w3-display-middle w3-center">
        <span class="" style="font-size:100px;background-color: black; color:white;">À la carte sur FOODTROCS</span>
        <p><a href="#entree" class="w3-button w3-xxlarge w3-black">Voir les entrées</a></p>
      </div>
    </header>

    <div class="wrap">
      <?php
      if ($result = mysqli_query($bdd, $sql) and $result2 = mysqli_query($bdd, $sql2) and $result3 = mysqli_query($bdd, $sql3)  and $result4 = mysqli_query($bdd, $sql4)) {
        if (mysqli_num_rows($result) > 0) {
      ?>

          <div class="row">
            <h1 id="entree">Les entrées</h1>
            <?php while ($row = mysqli_fetch_array($result)) { ?>
              <div class="col-1-4">
                <div class="show show-first">
                  <?php echo '<img style="height:400px; width:auto;" src="data:image/jpeg;base64,' . base64_encode($row['photo_produit']) . '"/>'; ?>
                  <div class="mask">
                    <h2><?php echo $row['intitule']; ?></h2>
                    <p class="price">Par <b><?php echo $row['pseudo']; ?></b></br>
                      Provenance :<b><?php echo $row['provenance']; ?></b></br>
                      Près de :<b><?php echo $row['localisation']; ?></b></br>
                      <b><?php echo $row['prix']; ?>€</b></p>
                    <?php echo "<a class='more' href='acheter.php?id=" . $row['id'] . "'><b>Acheter</b></a>"; ?>
                    <?php echo "<a class='more' href='user.php?id=" . $row['idMembre'] . "'><b>Voir tout</b></a>"; ?>
                  </div>
                </div>
              </div> <?php } ?>
          </div>
    </div>
    </br></br>
    <?php
          $bg2 = array('images/brf1.jpg', 'images/brf2.jpg', 'images/brf3.jpg'); // array of filenames

          $i2 = rand(0, count($bg) - 1); // generate random number size of the array
          $selectedBg2 = "$bg2[$i2]"; // set variable equal to which random filename was chosen
    ?>
    <style>
      .bgimg2 {
        background-repeat: no-repeat;
        background-image: url("<?php echo $selectedBg2; ?>");
        background-size: 100% auto;
        min-height: 90%;
        font-family: "Amatic SC", sans-serif;
        font-weight: bold;
      }
    </style>

    <!-- Header with image -->
    <header class="bgimg2 w3-display-container w3-grayscale-min" id="home">
      <div class="w3-display-middle w3-center">
        <span class="" style="font-size:100px;background-color: black; color:white;">Une petite ou</br> une grosse faim ?</span>
        <p><a href="#pc" class="w3-button w3-xxlarge w3-black">Voir les plats chaud</a></p>
      </div>
    </header>

    <div class="wrap">
      <div class="row">
        <h1 id="pc">Les plats chaud</h1>
        <?php while ($row = mysqli_fetch_array($result2)) { ?>
          <div class="col-1-4">
            <div class="show show-first">
              <?php echo '<img style="height:400px; width:auto; " src="data:image/jpeg;base64,' . base64_encode($row['photo_produit']) . '"/>'; ?>
              <div class="mask">
                <h2><?php echo $row['intitule']; ?></h2>
                <p class="price">Par <b><?php echo $row['pseudo']; ?></b></br>
                  Provenance :<b><?php echo $row['provenance']; ?></b></br>
                  Près de :<b><?php echo $row['localisation']; ?></b></br>
                  <b><?php echo $row['prix']; ?>€</b></p>
                <?php echo "<a class='more' href='acheter.php?id=" . $row['id'] . "'><b>Acheter</b></a>"; ?>
                <?php echo "<a class='more' href='user.php?id=" . $row['idMembre'] . "'><b>Voir tout</b></a>"; ?>
              </div>
            </div>
          </div> <?php } ?>
      </div>
    </div>
    </br></br>

    <?php
          $bg3 = array('images/d1.jpg', 'images/d2.jpg', 'images/d3.jpg'); // array of filenames

          $i3 = rand(0, count($bg3) - 1); // generate random number size of the array
          $selectedBg3 = "$bg3[$i3]"; // set variable equal to which random filename was chosen
    ?>
    <style>
      .bgimg3 {
        background-repeat: no-repeat;
        background-image: url("<?php echo $selectedBg3; ?>");
        background-size: 100% auto;
        min-height: 90%;
        font-family: "Amatic SC", sans-serif;
        font-weight: bold;
      }
    </style>

    <!-- Header with image -->
    <header class="bgimg3 w3-display-container w3-grayscale-min" id="home">
      <div class="w3-display-middle w3-center">
        <span class="" style="font-size:100px;background-color: black; color:white;">Vous croyez </br> être gourmand ? </span>
        <p><a href="#desserts" class="w3-button w3-xxlarge w3-black">Voir les desserts</a></p>
      </div>
    </header>

    <div class="wrap">
      <div class="row">
        <h1 id="desserts">LES DESSERTS</h1>
        <?php while ($row = mysqli_fetch_array($result3)) { ?>
          <div class="col-1-4">
            <div class="show show-first">
              <?php echo '<img style="height:400px; width:auto;" src="data:image/jpeg;base64,' . base64_encode($row['photo_produit']) . '"/>'; ?>
              <div class="mask">
                <h2><?php echo $row['intitule']; ?></h2>
                <p class="price">Par <b><?php echo $row['pseudo']; ?></b></br>
                  Provenance :<b><?php echo $row['provenance']; ?></b></br>
                  Près de :<b><?php echo $row['localisation']; ?></b></br>
                  <b><?php echo $row['prix']; ?>€</b></p>
                <?php echo "<a class='more' href='acheter.php?id=" . $row['id'] . "'><b>Acheter</b></a>"; ?>
                <?php echo "<a class='more' href='user.php?id=" . $row['idMembre'] . "'><b>Voir tout</b></a>"; ?>
              </div>
            </div>
          </div> <?php } ?>
      </div>
    </div>
<?php
        }
      }
?>


</br></br>
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
  header("Location: connexion.php");
}
?>