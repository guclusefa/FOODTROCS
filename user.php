<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=jajaydck_foodtrocs', 'jajaydck_root', '$1E3q_TkSYj6u9');
$id = $_GET['id'];
if (isset($_SESSION['id'])) {
    $idm = $_SESSION['id'];
if (isset($_GET['id'])) {
  $admin = $bdd->prepare("SELECT * FROM `admin` WHERE idAdmin = $id");
  $admin->execute(array($_SESSION['id']));
  $useradmin = $admin->fetch();
  $idAdmin = $useradmin['idAdmin'];

  $admin2 = $bdd->prepare("SELECT * FROM `admin` WHERE idAdmin = $idm");
  $admin2->execute(array($_SESSION['id']));
  $useradmin2 = $admin2->fetch();
  $idAdmin2 = $useradmin2['idAdmin'];

  $requser = $bdd->prepare("SELECT * FROM membres WHERE id = ?");
  $requser->execute(array($_GET['id']));
  $user = $requser->fetch();
  $benef = $user['ventes'] - $user['achats'];

  $requser = $bdd->prepare("SELECT * FROM membres WHERE id = $id");
  $requser->execute(array($_GET['id']));
  $userinfo = $requser->fetch();

  $requser = $bdd->prepare("SELECT * FROM membres WHERE membres.id = ?");
  $requser->execute(array($_SESSION['id']));
  $usermembre = $requser->fetch();
  $idMembre = $usermembre['id'];

  $requser2 = $bdd->prepare("SELECT * FROM produits WHERE idMembre = $id ORDER BY nbVentes DESC");
  $requser2->execute(array($_SESSION['id']));
  $produits = $requser2->fetch();
  $p1 = $produits['intitule'];

  $requser3 = $bdd->prepare("SELECT * FROM produits WHERE idMembre = $id ORDER BY nbVentes ASC");
  $requser3->execute(array($_SESSION['id']));
  $produits2 = $requser3->fetch();
  $p2 = $produits2['intitule'];

  if ($id == $idMembre) {
    header("Location: profil.php");
  }
?>
  <!DOCTYPE html>
  <html>

  <head>
    <title><?php echo $userinfo['pseudo'] ?> / FOOD TROCS</title>
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
    <link rel="stylesheet" href="mes_plats/mes_plats.css">
  </head>

  <body class="w3-light-grey">
    <div class="backg">


      <div class="w3-top w3-hide-small">
      <?php
          if(isset($_POST['rech'])){
    header("location: recherche.php?search=".$_POST['rech']);
}
?>
        <div class="w3-bar w3-xlarge w3-black w3-opacity w3-hover-opacity-off" id="myNavbar">
          <a href="deconnexion.php" style="font-size:1.52em; text-decoration: none;" class="w3-bar-item w3-button, couleurhover" title="Déconnexion !"><i class="fas fa-door-open"></i></a>
          <a href="profil.php" style="font-size:1.52em; text-decoration: none;" class="w3-bar-item w3-button, couleurhover"><?php echo '<img style="height:50px; width:50px; border-radius:50%; object-fit:cover;" src="data:image/jpeg;base64,' . base64_encode($usermembre['photo_profil']) . '"/>'; ?> <?php echo $usermembre['pseudo']; ?></a>
          <a href="mes_plats.php" style="font-size:1.52em; text-decoration: none;" class="w3-bar-item w3-button, couleurhover"><i class="fas fa-hamburger"></i> MES PLATS</a>
          <a href="catalogue.php" style="font-size:1.52em; text-decoration: none;" class="w3-bar-item w3-button, couleurhover"><i class="fas fa-shopping-cart"></i> CATALOGUE</a>
          <a href="classement.php" style="font-size:1.52em; text-decoration: none;" class="w3-bar-item w3-button, couleurhover"><i class="fas fa-award"></i> CLASSEMENT</a>
          <?php if (isset($idAdmin2)) { ?>
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
                <?php echo '<img style="width:100%;" src="data:image/jpeg;base64,' . base64_encode($userinfo['photo_profil']) . '"/>'; ?></br></br></br></br></br>

                <div class="w3-display-bottomleft w3-container w3-text-black">
                  <h2><?php echo $userinfo['prenom']; ?> "<?php echo $userinfo['pseudo']; ?>" <?php echo $userinfo['nom']; ?></br> <?php if (isset($idAdmin)) {
                                                                                                                                      echo "<font color='#9a0606'><i class='fas fa-user-shield' style='color:#9a0606'></i> <b>admin</b></font>";
                                                                                                                                    } ?></h2>
                </div>
              </div>
              <div class="w3-container">
                <?php if($userinfo['connect'] == 0){?>
                <p style="color:red"><i class="fas fa-circle fa-fw w3-margin-right w3-large" style="color:red"></i><b>Statut : Déconnecté</b></p>
                <?php }?>
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
                <p>Ventes: <b><?php echo $userinfo['nbrVentes']; ?></b> ventes soit <b><?php echo $userinfo['ventes']; ?>€</b></p>
                <p>Achats : <b><?php echo $userinfo['nbrAchats']; ?></b> achats soit <b><?php echo $userinfo['achats']; ?>€</b></p>
                <p>Bénéfices : <b><?php echo $benef ?>€</b></p>
                <p>Plat le plus vendue : <b><?php echo $p1 ?></b></p>
                <p>Plat le moins vendue : <b><?php echo $p2 ?></b></p>
              </div>
            </div><br>

            <!-- End Left Column -->
          </div>

          <!-- Right Column -->
          <div class="w3-twothird">
            <?php if($id == 1){
            ?>
            <video width="100%" height="auto" controls>
            <source src="videos/goat.mp4" type="video/mp4">
            Your browser does not support the video tag.
            </video>
            <?php
            } 
            ?>
            <div class="w3-container w3-card w3-white w3-margin-bottom">
              <h1 class="w3-text-grey w3-padding-16"><b><i class="fas fa-pen fa-fw w3-margin-right w3-xxlarge"></i>Les plats de <?php echo $userinfo['pseudo'] ?></b></h1>
              <div class="w3-container">
                <?php
                $link = mysqli_connect("localhost", "jajaydck_root", "$1E3q_TkSYj6u9", "jajaydck_foodtrocs");
                $sql = "SELECT * FROM membres, produits WHERE membres.id = produits.idMembre AND membres.id = $id AND `type`='Entree' ";
                $sql2 = "SELECT * FROM membres, produits WHERE membres.id = produits.idMembre AND membres.id = $id AND `type`='Plat Chaud' ";
                $sql3 = "SELECT * FROM membres, produits WHERE membres.id = produits.idMembre AND membres.id = $id AND `type`='Dessert' ";
                if ($result = mysqli_query($link, $sql) and $result2 = mysqli_query($link, $sql2) and $result3 = mysqli_query($link, $sql3)) {
                ?>

                  <div class="w3-container w3-padding-64 w3-xxlarge" id="menu">
                    <div class="w3-content">

                      <div class="w3-row w3-center w3-border w3-border-dark-grey">
                        <a href="javascript:void(0)" onclick="openMenu(event, 'Pizza');" id="myLink">
                          <div class="w3-col s4 tablink w3-padding-large hover0">ENTREES</div>
                        </a>
                        <a href="javascript:void(0)" onclick="openMenu(event, 'Pasta');">
                          <div class="w3-col s4 tablink w3-padding-large hover0">PLATS CHAUD</div>
                        </a>
                        <a href="javascript:void(0)" onclick="openMenu(event, 'Starter');">
                          <div class="w3-col s4 tablink w3-padding-large hover0">DESSERTS</div>
                        </a>
                      </div>



                      <div id="Pizza" class="w3-container menu w3-padding-32 w3-white">
                        <?php if (mysqli_num_rows($result) === 0) { ?>
                          <h1 class="deconnexion">AUCUNE ENTREE</h1>
                      </div>
                    <?php
                        } else if (mysqli_num_rows($result) > 0) {
                    ?>
                      <?php while ($row = mysqli_fetch_array($result)) { ?>
                        <?php echo '<img style="height:auto; width:100%;" src="data:image/jpeg;base64,' . base64_encode($row['photo_produit']) . '"/>'; ?>
                        <p><b><?php echo $row['intitule'] ?></b><span class="w3-right w3-tag backcouleur0 w3-round test"><?php echo $row['prix'] ?>€</span></br>
                          <?php echo nl2br($row['description']) ?></br>
                          Provenance : <b><?php echo $row['provenance'] ?></b></br>
                          Vendue <b><?php echo $row['nbVentes'] ?> fois</b></br>
                          <span class="submitBtn2 bold"><?php echo "<a style='text-decoration:none;' href='acheter.php?id=" . $row['id'] . "'>ACHETER</a>"; ?></span>
                          <hr>
                        <?php } ?>
                    </div>

                  <?php
                        }
                  ?>
                  <div id="Pasta" class="w3-container menu w3-padding-32 w3-white">
                    <?php if (mysqli_num_rows($result2) === 0) { ?>
                      <h1 class="deconnexion">AUCUN PLAT CHAUD</h1>
                  </div>
                <?php
                    } else if (mysqli_num_rows($result2) > 0) {
                ?>
                  <?php while ($row = mysqli_fetch_array($result2)) { ?>
                    <?php echo '<img style="height:auto; width:100%;" src="data:image/jpeg;base64,' . base64_encode($row['photo_produit']) . '"/>'; ?>
                    <p><b><?php echo $row['intitule'] ?></b><span class="w3-right w3-tag backcouleur0 w3-round test"><?php echo $row['prix'] ?>€</span></br>
                      <?php echo nl2br($row['description']) ?></br>
                      Provenance : <b><?php echo $row['provenance'] ?></b></br>
                      Vendue <b><?php echo $row['nbVentes'] ?> fois</b></br>
                      <span class="submitBtn2 bold"><?php echo "<a style='text-decoration:none;' href='acheter.php?id=" . $row['id'] . "'>ACHETER</a>"; ?></span>
                      <hr>
                    <?php } ?>
                  </div>
              <?php
                    }
                  }
              ?>

              <div id="Starter" class="w3-container menu w3-padding-32 w3-white">
                <?php if (mysqli_num_rows($result3) === 0) { ?>
                  <h1 class="deconnexion">AUCUN DESSERT</h1>
              </div>
            <?php
                } else if (mysqli_num_rows($result3) > 0) {
            ?>
              <?php while ($row = mysqli_fetch_array($result3)) { ?>
                <?php echo '<img style="height:auto; width:100%;" src="data:image/jpeg;base64,' . base64_encode($row['photo_produit']) . '"/>'; ?>
                <p><b><?php echo $row['intitule'] ?></b><span class="w3-right w3-tag backcouleur0 w3-round test"><?php echo $row['prix'] ?>€</span></br>
                  <?php echo nl2br($row['description']) ?></br>
                  Provenance : <b><?php echo $row['provenance'] ?></b></br>
                  Vendue <b><?php echo $row['nbVentes'] ?> fois</b></br>
                  <span class="submitBtn2 bold"><?php echo "<a style='text-decoration:none;' href='acheter.php?id=" . $row['id'] . "'>ACHETER</a>"; ?></span>
                  <hr>
                <?php } ?>
              </div>
            <?php
                }
            ?>
            </div><br>
          </div>
        </div>

        <script>
          // Tabbed Menu
          function openMenu(evt, menuName) {
            var i, x, tablinks;
            x = document.getElementsByClassName("menu");
            for (i = 0; i < x.length; i++) {
              x[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablink");
            for (i = 0; i < x.length; i++) {
              tablinks[i].className = tablinks[i].className.replace(" backcouleur0", "");
            }
            document.getElementById(menuName).style.display = "block";
            evt.currentTarget.firstElementChild.className += " backcouleur0";
          }
          document.getElementById("myLink").click();
        </script>


      </div>
    </div>

    <!-- End Right Column -->
    </div>

    <!-- End Grid -->
    </div>

    <!-- End Page Container -->
    </div>
    </br>
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
}
} else {
    header("Location: https://foodtrocs.go.yj.fr/connexion.php");
} 
?>