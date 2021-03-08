<!DOCTYPE html>
<?php
session_start();
$bg = array('images/jsp.jpg', 'images/photo-1466978913421-dad2ebd01d17.jpg', 'images/jsp2.jpg'); // array of filenames
$i = rand(0, count($bg) - 1); // generate random number size of the array
$selectedBg = "$bg[$i]"; // set variable equal to which random filename was chosen

$bdd = mysqli_connect("localhost", "jajaydck_root", "$1E3q_TkSYj6u9", "jajaydck_foodtrocs");
$bdd2 = new PDO('mysql:host=localhost;dbname=jajaydck_foodtrocs', 'jajaydck_root', '$1E3q_TkSYj6u9');
$sql = "SELECT * FROM membres, produits WHERE membres.id = produits.idMembre AND `type` = 'entree' ORDER BY nbVentes DESC LIMIT 4";
$sql2 = "SELECT * FROM membres, produits WHERE membres.id = produits.idMembre AND `type` = 'plat chaud' ORDER BY nbVentes DESC LIMIT 4";
$sql3 = "SELECT * FROM membres, produits WHERE membres.id = produits.idMembre AND `type` = 'dessert' ORDER BY nbVentes DESC LIMIT 4";
$connect = $bdd2->query("SELECT  COUNT(*) as total FROM membres WHERE connect = 1 " );
$donnees = $connect->fetch();
$connect->closeCursor();
$connect2 = $bdd2->query("SELECT  COUNT(*) as total FROM membres" );
$donnees2 = $connect2->fetch();
$connect2->closeCursor();

if (isset($_SESSION['id'])) {
  $id = $_SESSION['id'];
  $sql = "SELECT * FROM membres, produits WHERE membres.id = produits.idMembre AND `type` = 'entree' AND produits.idMembre <> $id ORDER BY nbVentes DESC LIMIT 4 ";
  $sql2 = "SELECT * FROM membres, produits WHERE membres.id = produits.idMembre AND `type` = 'plat chaud' AND produits.idMembre <> $id ORDER BY nbVentes DESC LIMIT 4 ";
  $sql3 = "SELECT * FROM membres, produits WHERE membres.id = produits.idMembre AND `type` = 'dessert' AND produits.idMembre <> $id ORDER BY nbVentes DESC LIMIT 4 ";
}

if ($result = mysqli_query($bdd, $sql) and $result2 = mysqli_query($bdd, $sql2) and $result3 = mysqli_query($bdd, $sql3)) {
?>

  <html>

  <head>
    <title>ACCUEIL / FOOD TROCS</title>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/foodtrocs.1.png" />
    <link href="logo/css/all.css" rel="stylesheet">
    <meta name="viewport" content="width=1024">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Amatic+SC">
    <link rel="stylesheet" href="accueil/accueil.css">
    <style>
      .bgimg {
        background-repeat: no-repeat;
        background-size: cover;
        background-image: url("<?php echo $selectedBg; ?>");
        background-size: 100% auto;
        min-height: 90%;
        font-weight: bold;
      }
    </style>
  </head>

  <body>

    <!-- Navbar (sit on top) -->
    <div class="w3-top w3-hide-small">
      <div class="w3-bar w3-xlarge w3-black w3-opacity w3-hover-opacity-off" id="myNavbar">
        <?php
        if (isset($_SESSION['id'])) {
          $id = $_SESSION['id'];
          $admin = $bdd2->prepare("SELECT * FROM `admin` WHERE idAdmin = $id");
          $admin->execute(array($_SESSION['id']));
          $useradmin = $admin->fetch();
          $idAdmin = $useradmin['idAdmin'];

          $requser = $bdd2->prepare("SELECT * FROM membres WHERE id = ?");
          $requser->execute(array($_SESSION['id']));
          $userinfo = $requser->fetch();
        ?>
        <a href="deconnexion.php" style="font-size:1.52em; text-decoration: none;" class="w3-bar-item w3-button, couleurhover" title="Déconnexion !"><i class="fas fa-door-open"></i></a>
          <?php
          if(isset($_POST['rech'])){
    header("location: recherche.php?search=".$_POST['rech']);
}
?>
          <a href="profil.php" style="font-size:1.52em; text-decoration: none;" class="w3-bar-item w3-button, couleurhover"><?php echo '<img style="height:50px; width:50px; border-radius:50%; object-fit:cover;" src="data:image/jpeg;base64,' . base64_encode($userinfo['photo_profil']) . '"/>'; ?> <?php echo $userinfo['pseudo']; ?></a>
          <a href="mes_plats.php" style="font-size:1.52em; text-decoration: none;" class="w3-bar-item w3-button, couleurhover"><i class="fas fa-hamburger"></i> MES PLATS</a>
          <a href="catalogue.php" style="font-size:1.52em; text-decoration: none;" class="w3-bar-item w3-button, couleurhover"><i class="fas fa-shopping-cart"></i> CATALOGUE</a>
          <a href="classement.php" style="font-size:1.52em; text-decoration: none;" class="w3-bar-item w3-button, couleurhover"><i class="fas fa-award"></i> CLASSEMENT</a>
          <?php if (isset($idAdmin)) { ?>
            <a href="admin.php" style="font-size:1.52em; text-decoration: none;" class="w3-bar-item w3-button, couleurhover"><i class="fas fa-user-shield"></i> PAGE ADMIN</a>
          <?php } ?>

        <?php } else { ?>
          <a href="connexion.php" style="font-size:1.52em; text-decoration: none;" class="w3-bar-item w3-button, couleurhover"><i class="fa fa-fw fa-user"></i> CONNEXION / INCRIPTION</a>
        <?php } ?>
        <a href="accueil.php"><img class="w3-bar-item w3-button, couleurhover" style="height:70px; float:right" src="images/foodtrocs3.png" /></a>
        <?php if (isset($_SESSION['id'])) { ?>
        <form method="post">
          <input autocomplete="off" name="rech" id="rech" type="text" placeholder="Rechercher..." class="w3-bar-item" style="font-size:1.52em; background-color:black; color:white; margin-left:-1%;float:right"/>
          <button href="recherche.php" style="font-size:1.52em; background-color:black; color:white; cursor :pointer;float:right;" class="w3-bar-item couleurhover" title="Recherche !"><i class="fas fa-search couleurhover cursor :pointer;"></i></button>
          </form>
        <?php } ?>
      </div>
    </div>

    <!-- Header with image -->
    <header class="bgimg w3-display-container w3-grayscale-min" id="home">
      <div class="w3-display-bottomleft w3-padding">
        <span class="w3-tag w3-xlarge"><a href="membres_connecte.php" style="text-decoration:none; color:white;" >Membres connectés : <?php echo $donnees['total']." (".$donnees2['total']. " inscrits)"; ?></a></span></br></br>
        <span class="w3-tag w3-xlarge">NOUVEAU : PROFITEZ DE 5€ DE REDUCTION A PARTIR DE 20€ D'ACHATS AVEC LE CODE "<b>FTROCS5</b>"</span>
      </div>
      <div class="w3-display-middle w3-center">
        <span class="" style="font-size:100px;background-color: black; color:white;">Vos plats préférés, livrés</br> chez vous en un clic</span></br>
      </div>
    </header>

    <!--Photo Grid-->
    <?php if (mysqli_num_rows($result) > 0) { ?>
      <div class="w3-main w3-content w3-padding" style="max-width:1200px;margin-top:100px">
        <div class="w3-row-padding w3-padding-16 w3-center" id="food">
          <h1 id="menu" style="font-size:100px; color:black;" class="entree">Un petit aperçue des meilleurs plats !</h1>
          <?php while ($row = mysqli_fetch_array($result)) { ?>
            <div class="w3-quarter">
              <?php echo '<img style="width:100%" src="data:image/jpeg;base64,' . base64_encode($row['photo_produit']) . '"/>'; ?>
              <h3><b><?php echo $row['intitule']; ?></b> par <b><?php echo $row['pseudo']; ?></b></h3>
              <p>Provenance : <b><?php echo $row['provenance']; ?></b>, vendue près de <b><?php echo $row['localisation']; ?></b></br>
                <?php echo nl2br($row['description']); ?></br>
                Pour un prix de <b><?php echo $row['prix']; ?>€</b></br>
                <a href="catalogue.php"><span class="w3-button w3-xxlarge" style="font-size:20px; color:black; text-decoration:none;">DECOUVRIR</span></a></p>
            </div>
          <?php } ?>
        </div>
      <?php } ?>
      
      <!-- Second Photo Grid-->
      <?php if (mysqli_num_rows($result2) > 0) { ?>
        <div class="w3-row-padding w3-padding-16 w3-center" id="food">
          <?php while ($row = mysqli_fetch_array($result2)) { ?>
            <div class="w3-quarter">
              <?php echo '<img style="width:100%" src="data:image/jpeg;base64,' . base64_encode($row['photo_produit']) . '"/>'; ?>
              <h3><b><?php echo $row['intitule']; ?></b> par <b><?php echo $row['pseudo']; ?></b></h3>
              <p>Provenance : <b><?php echo $row['provenance']; ?></b>, vendue près de <b><?php echo $row['localisation']; ?></b></br>
                <?php echo nl2br($row['description']); ?></br>
                Pour un prix de <b><?php echo $row['prix']; ?>€</b></br>
                <a href="catalogue.php"><span class="w3-button w3-xxlarge" style="font-size:20px; color:black; text-decoration:none;">DECOUVRIR</span></a></p>
            </div>
          <?php } ?>
        </div>
      <?php } ?>

      <!-- third Photo Grid-->
      <?php if (mysqli_num_rows($result3) > 0) { ?>

        <div class="w3-row-padding w3-padding-16 w3-center" id="food">
          <?php while ($row = mysqli_fetch_array($result3)) { ?>
            <div class="w3-quarter">
              <?php echo '<img style="width:100%" src="data:image/jpeg;base64,' . base64_encode($row['photo_produit']) . '"/>'; ?>
              <h3><b><?php echo $row['intitule']; ?></b> par <b><?php echo $row['pseudo']; ?></b></h3>
              <p>Provenance : <b><?php echo $row['provenance']; ?></b>, vendue près de <b><?php echo $row['localisation']; ?></b></br>
                <?php echo nl2br($row['description']); ?></br>
                Pour un prix de <b><?php echo $row['prix']; ?>€</b></br>
                <a href="catalogue.php"><span class="w3-button w3-xxlarge" style="font-size:20px; color:black; text-decoration:none;">DECOUVRIR</span></a></p>
            </div>
          <?php } ?>
        </div>
      <?php } ?>
      </div>

      <!-- About Container -->
      <div class="w3-container w3-padding-64 backcouleur3 w3-grayscale w3-xlarge" id="about">
        <div class="w3-content">
          <h1 class="w3-center w3-jumbo" style="margin-bottom:64px">À propos</h1>
          <p>Chez FOODTROCS nous sommes des passionnés du “bon manger” et nous voulons proposer au plus grand nombre toujours plus de choix. Il existe partout des repas maison exceptionnels dont tout le monde devrait selon nous pouvoir profiter.</p>
          <p><strong>Le Chef?</strong> Mr. Sefa GUCLU lui-même<img src="images/foodtrocs0.png" style="width:150px" class="w3-circle w3-right" alt="Chef"></p>
          <p>Nous sommes fiers de notre plateforme.</p>
          <!--
          <p>test:</p>
<iframe src="https://olafwempe.com/mp3/silence/silence.mp3" type="audio/mp3" allow="autoplay" id="audio" style="display:none"></iframe>
<audio autoplay src="videos/dr-dre-feat-snoop-dogg-still-dre-lyrics.mp3" type="audio/mp3" controls>
</audio></br>
<video src='videos/oke.mp4' controls width='320px' height='200px' >
-->
        </div>
      </div>

      <!-- Contact -->
      <div class="w3-container w3-padding-64 backcouleur w3-grayscale-min w3-xlarge" id="contact" style="color:white;">
        <div class="w3-content">
          <h1 class="w3-center w3-jumbo" style="margin-bottom:64px">Contact</h1>
          <p><span class="w3-tag">INFO :</span> Nous offrons une plateforme à service complet pour tout événement, grand ou petit.</br>
            Nous comprenons vos besoins et nous approvisionnerons la nourriture pour satisfaire les plus grands critères de tous,</br>
            à la fois l'aspect et le goût.</p>
          <p class="w3-xxlarge">Vous avez des questions ? Posez nous les ici !</p>
          <?php
          if (isset($_POST['formquestion'])) {
            $pseudo = htmlspecialchars($_POST['pseudo']);
            $mail = htmlspecialchars($_POST['mail']);
            $question = htmlspecialchars($_POST['question']);

            if (!empty($_POST['pseudo']) and !empty($_POST['mail']) and !empty($_POST['question'])) {
            date_default_timezone_set('Europe/Paris');
            $date = date('Y-m-d H:i:s');
              $insertmbr = $bdd2->prepare("INSERT INTO faq(pseudo, mail, question, date_q, idMembre) VALUES (?,?,?,'$date', $id)");
              $insertmbr->execute(array($pseudo, $mail, $question));
              $erreur = "Votre question a bien été envoyé !";
            } else {
              $erreur = "Conncetez vous pour nous poser une question !";
            }
          }
          ?>
          <form method="POST" action="#contact">
            <p><input class="w3-input w3-padding-16 w3-border" type="text" placeholder="Pseudo" value="<?php if (isset($_SESSION['id'])) {
                                                                                                          echo $userinfo['pseudo'];
                                                                                                        } ?>" readonly required name="pseudo"></p>
            <p><input class="w3-input w3-padding-16 w3-border" type="email" placeholder="Adresse e-mail" value="<?php if (isset($_SESSION['id'])) {
                                                                                                                  echo $userinfo['mail'];
                                                                                                                } ?>" readonly required name="mail"></p>
            <?php if (isset($_SESSION['id'])) { ?><p><textarea class="w3-input w3-padding-16 w3-border" type="text" placeholder="Votre question" required name="question" rows="6"></textarea></p><?php } else { ?>
              <p><textarea class="w3-input w3-padding-16 w3-border" type="text" placeholder="Votre question" readonly required name="question" rows="6"><?php } ?></textarea></p>
            <p><button class="w3-button w3-light-grey w3-block" type="submit" name="formquestion">ENVOYER MESSAGE</button></p>
            <?php
            if (isset($erreur) and isset($_POST['formquestion'])) {
              echo '<font color="red"><b>' . $erreur . "</b></font>";
            } else []
            ?>
          </form>
          <?php ?>
        </div>
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
<?php } ?>