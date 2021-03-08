<!DOCTYPE html>
<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=jajaydck_foodtrocs', 'jajaydck_root', '$1E3q_TkSYj6u9');
$bdd2 = mysqli_connect("localhost", "jajaydck_root", "$1E3q_TkSYj6u9", "jajaydck_foodtrocs");
$id = $_SESSION['id'];
?>
<html>

<head>
  <title>MES PLATS / FOOD TROCS</title>
  <meta charset="UTF-8">
  <link rel="icon" type="image/png" href="images/foodtrocs.1.png" />
  <link href="logo/css/all.css" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Amatic+SC">
  <link rel="stylesheet" href="accueil/accueil.css">
  <link rel="stylesheet" href="mes_plats/mes_plats.css">
  <link rel="stylesheet" href="popup/style2.css">
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
      <?php
      if (isset($_SESSION['id'])) {
        $requser = $bdd->prepare("SELECT * FROM membres WHERE id = ?");
        $requser->execute(array($_SESSION['id']));
        $userinfo = $requser->fetch();

        $admin = $bdd->prepare("SELECT * FROM `admin` WHERE idAdmin = $id");
        $admin->execute(array($_SESSION['id']));
        $useradmin = $admin->fetch();
        $idAdmin = $useradmin['idAdmin'];
      ?>
        <a href="deconnexion.php" style="font-size:1.52em; text-decoration: none;" class="w3-bar-item w3-button, couleurhover" title="Déconnexion !"><i class="fas fa-door-open"></i></a>
        <a href="profil.php" style="font-size:1.52em; text-decoration: none;" class="w3-bar-item w3-button, couleurhover"><?php echo '<img style="height:50px; width:50px; border-radius:50%; object-fit:cover;" src="data:image/jpeg;base64,' . base64_encode($userinfo['photo_profil']) . '"/>'; ?> <?php echo $userinfo['pseudo']; ?></a>
      <?php } else { ?>
        <a href="connexion.php" style="font-size:1.52em; text-decoration: none;" class="w3-bar-item w3-button, couleurhover"><i class="fa fa-fw fa-user"></i> CONNEXION / INCRIPTION</a>
      <?php } ?>
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

  <?php
  if (isset($_SESSION['id'])) {
    $requser = $bdd->prepare("SELECT * FROM membres WHERE membres.id = ?");
    $requser->execute(array($_SESSION['id']));
    $user = $requser->fetch();
    $idMembre = $user['id'];
    if (isset($_POST['formajoutez'])) {
      $newint = htmlspecialchars($_POST['newint']);
      $newpro = htmlspecialchars(strtolower($_POST['newpro']));
      $newprix = htmlspecialchars($_POST['newprix']);
      $newdesc = htmlspecialchars($_POST['newdesc']);
      $data = file_get_contents($_FILES['myfile']['tmp_name']);
      $image_info = @getimagesize($_FILES['myfile']['tmp_name']);
      $newtype = htmlspecialchars($_POST['newtype']);
      if (!empty($_POST['newtype']) and !empty($_POST['newint']) and !empty($_POST['newdesc']) and !empty($_POST['newpro']) and !empty($_POST['newprix']) and !empty($_FILES['myfile']['tmp_name'])) {
          if($image_info == false) {
              $erreur = "Photo invalide !";
          }else {
        $insertmbr = $bdd->prepare("INSERT INTO produits(intitule, provenance, prix, `description`, photo_produit, `type`, idMembre) VALUES (?,?,?,?,?,?, $idMembre)");
        $insertmbr->execute(array($newint, $newpro, $newprix, $newdesc, $data, $newtype));
        $insertmbr->bindParam(5, $data);
        $erreur = "Votre plat a bien été ajoutez !";}
      }
    }

    $link = mysqli_connect("localhost", "jajaydck_root", "$1E3q_TkSYj6u9", "jajaydck_foodtrocs");
    $sql = "SELECT * FROM membres, produits WHERE membres.id = produits.idMembre AND membres.id = $idMembre AND `type`='Entree' ";
    $sql2 = "SELECT * FROM membres, produits WHERE membres.id = produits.idMembre AND membres.id = $idMembre AND `type`='Plat Chaud' ";
    $sql3 = "SELECT * FROM membres, produits WHERE membres.id = produits.idMembre AND membres.id = $idMembre AND `type`='Dessert' ";
    if ($result = mysqli_query($link, $sql) and $result2 = mysqli_query($link, $sql2) and $result3 = mysqli_query($link, $sql3)) {
  ?>

      <div class="lolilol">
        <form class="container" method="POST" action="" enctype="multipart/form-data"></br></br>
          <div class="introduction">
            <h1 class="title title-1" id="title">Ajoutez un plat</h1>
            <p class="text">
              Merci de renseigner les informations demandées ci-dessous afin de valider l'ajout de votre plat.
            </p>
          </div>

          <div class="form-container">
            <div class="infoClient">
              <div class="form__group">
                <input type="text" class="form__input" id="newint" name="newint" placeholder="Intitule" required>
                <label for="name" class="form__label" id="name-label">Intitule</label>
              </div>

              <div class="form__group">
                <input type="text" class="form__input" id="newpro" name="newpro" placeholder="Provenace" required>
                <label for="email" class="form__label" id="email-label">Provenace</label>
              </div>

              <div class="form__group">
                <input type="number" class="form__input" id="newprix" name="newprix" placeholder="Prix" required step="1" min="1" max="99" required>
                <label for="number" class="form__label" id="number-label">Prix en €</label>
              </div>

              <div class="form__group">
                <textarea type="text" class="form__input" id="newdesc" name="newdesc" placeholder="Description" cols="50" rows="6" required></textarea>
                <label for="name" class="form__label" id="name-label">Desctiption</label>
              </div>

            </div>

            <div class="reservation">
              <div class="form__group">
                <input type="file" name="myfile" class="form__input"  accept="image/*" required>
                <label for="name" class="form__label" id="name-label"> Photo du plat</label>
              </div>
              <div class="form__group">
                <p class="text bold">Type de plat :</p>
                <div class="radio-btn">
                  <select class="form__input" id="newtype" name="newtype">
                    <option>Entree</option>
                    <option>Plat Chaud</option>
                    <option>Dessert</option>
                  </select>
                </div>
              </div>

              <div class="submit">
                <input type="submit" class="submitBtn bold" onclick="window.location.href = '#';" type="submit" name="formajoutez" value="Ajoutez !">
              </div>
            </div>
        </form>
        <?php
        if (isset($erreur)) {
          echo '<h1><font color="red"><b>' . $erreur . "</b></font><h1>";
        }
        ?>
      </div>


      </div>
      </div>
      </div>


      <!-- Menu Container -->
      <div class="w3-container w3-padding-64 w3-xxlarge" id="menu">
        <div class="w3-content">

          <h1 class=" w3-jumbo" style="margin-bottom:64px">MES PLATS</h1>
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
              <form method="POST" action="" enctype="multipart/form-data">
                <span class="submitBtn2 bold"><a href="modifier.php?id=<?php echo $row['id'] ?>" style="text-decoration:none;">MODIFIER</a></span>

                <span class="submitBtn bold" class="cd-popup-trigger"><a href="#0" class="cd-popup-trigger" style="text-decoration:none;">RETIRER</a></button></span>
                <div class="cd-popup" role="alert">
                  <div class="cd-popup-container2">
                    <h1>êtes-vous sûr de vouloir retirer</br> ce plat ?</h1>
                    <ul class="cd-buttons" style="list-style:none">
                      <li><button class="non" type="submit"><a style="background-color:#9a0606; text-decoration:none;" href="delete.php?id=<?php echo $row['id']; ?>">Oui</a></button></li>
                      <li><button class="oui" type="submit" name="formnon">Non</button></li>
                    </ul>
                  </div> <!-- cd-popup-container -->
                </div> <!-- cd-popup -->
                <!-- partial -->
                <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
                <script src="popup/script2.js"></script>
              </form>
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

          <form method="POST" action="" enctype="multipart/form-data">
            <span class="submitBtn2 bold"><a href="modifier.php?id=<?php echo $row['id'] ?>" style="text-decoration:none;">MODIFIER</a></span>

            <span class="submitBtn bold" class="cd-popup-trigger"><a href="#0" class="cd-popup-trigger" style="text-decoration:none;">RETIRER</a></button></span>
            <div class="cd-popup" role="alert">
              <div class="cd-popup-container2">
                <h1>êtes-vous sûr de vouloir retirer</br> ce plat ?</h1>
                <ul class="cd-buttons" style="list-style:none">
                  <li><button class="non" type="submit"><a style="background-color:#9a0606; text-decoration:none;" href="delete.php?id=<?php echo $row['id']; ?>">Oui</a></button></li>
                  <li><button class="oui" type="submit" name="formnon">Non</button></li>
                </ul>
              </div> <!-- cd-popup-container -->
            </div> <!-- cd-popup -->
            <!-- partial -->
            <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
            <script src="popup/script2.js"></script>
          </form>
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
      <form method="POST" action="" enctype="multipart/form-data">
        <span class="submitBtn2 bold"><a href="modifier.php?id=<?php echo $row['id'] ?>" style="text-decoration:none;">MODIFIER</a></span>

        <span class="submitBtn bold" class="cd-popup-trigger"><a href="#0" class="cd-popup-trigger" style="text-decoration:none;">RETIRER</a></button></span>
        <div class="cd-popup" role="alert">
          <div class="cd-popup-container2">
            <h1>êtes-vous sûr de vouloir retirer</br> ce plat ?</h1>
            <ul class="cd-buttons" style="list-style:none">
              <li><button class="non" type="submit"><a style="background-color:#9a0606; text-decoration:none;" href="delete.php?id=<?php echo $row['id']; ?>">Oui</a></button></li>
              <li><button class="oui" type="submit" name="formnon">Non</button></li>
            </ul>
          </div> <!-- cd-popup-container -->
        </div> <!-- cd-popup -->
        <!-- partial -->
        <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
        <script src="popup/script2.js"></script>
      </form>
      <hr>
    <?php } ?>
    </div>
  <?php
    }
  ?>
  </div><br>
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

<?php
  } else {
    header("Location: accueil.php");
  }
?>
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

</body>

</html>