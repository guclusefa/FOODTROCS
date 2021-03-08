<!DOCTYPE html>
<?php
session_start();
$bdd = mysqli_connect("localhost", "jajaydck_root", "$1E3q_TkSYj6u9", "jajaydck_foodtrocs");
$bdd2 = new PDO('mysql:host=localhost;dbname=jajaydck_foodtrocs', 'jajaydck_root', '$1E3q_TkSYj6u9');
$sql = "SELECT DISTINCT * FROM membres ORDER BY nbrAchats DESC LIMIT 30 ";
$sql2 = "SELECT * FROM membres ORDER BY nbrAchats DESC LIMIT 30";
$id = $_SESSION['id'];
?>
<?php
if (isset($_SESSION['id'])) {
  $requser = $bdd2->prepare("SELECT * FROM membres WHERE id = ?");
  $requser->execute(array($_SESSION['id']));
  $userinfo = $requser->fetch();

  $admin = $bdd2->prepare("SELECT * FROM `admin` WHERE idAdmin = $id");
  $admin->execute(array($_SESSION['id']));
  $useradmin = $admin->fetch();
  $idAdmin = $useradmin['idAdmin'];

  $rang = $bdd2->prepare("SELECT pseudo, test FROM (SELECT *, row_number() OVER (ORDER BY nbrachats DESC) test FROM membres) membres WHERE id = ?");
  $rang->execute(array($_SESSION['id']));
  $r_rang = $rang->fetch();
?>
  <html>

  <head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Oswald|Overpass+Mono&amp;display=swap'>
    <link rel="stylesheet" href="classement/style.css">
    <title>CLASSEMENT / FOOD TROCS</title>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/foodtrocs.1.png" />
    <link href="logo/css/all.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Amatic+SC">
    <link rel="stylesheet" href="accueil/accueil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Oswald|Overpass+Mono&amp;display=swap'>
  </head>

  <?php
  date_default_timezone_set('Europe/Paris');
  $date = date('Y-m-d H:i:s');
  ?>

  <body>
    <!-- menu -->
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
          <input autocomplete="off" name="rech" id="rech" type="text" placeholder="Rechercher..." class="w3-bar-item font" style="margin-top:6.5px; font-size:1.52em; background-color:black; color:white; margin-left:-1%;float:right"/>
          <button href="recherche.php" style="margin-top:6.5px; font-size:1.52em; background-color:black; color:white; cursor :pointer;float:right;" class="w3-bar-item couleurhover font" title="Recherche !"><i class="fas fa-search couleurhover cursor :pointer;"></i></button>
          </form>
      </div>
    </div></br></br></br>

    <!-- partial:index.partial.html -->
    <div class="classement">
      <div class="l-wrapper">
        <div class="c-header"><img class="c-logo" src="images/foodtrocs0.1.png" draggable="false" style="height:100px; width:auto;" />
          <select class="c-button c-button--primary" onchange="location = this.value;">
            <option>Par nombre d'achats</option></a>
            <option value="classement.php">Par nombre de ventes</option>
          </select>
        </div>
        <div class="l-grid">
          <div class="l-grid__item l-grid__item--sticky">
            <div class="c-card u-bg--light-gradient u-text--dark">
              <div class="c-card__body">
                <div class="u-display--flex u-justify--space-between">
                  <div class="u-text--left">
                    <div class="u-text--small , blanc">Mon rang</div>
                    <h2 class="blanc"><?php echo $r_rang['test'];?></h2>
                  </div>
                  <div class="u-text--right">
                    <div class="u-text--small , blanc">Mes achats</div>
                    <h2 class="blanc"><?php echo $userinfo['nbrAchats'] ?> soit <?php echo $userinfo['achats'] ?>€</h2>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="l-grid__item">
            <div class="c-card">
              <div class="c-card__header">
                <h3>Classement FOODTROCS</h3>
                <select class="c-select">
                  <option selected="selected"><?php echo $date ?></option>
                </select>
              </div>
              <div class="c-card__body">
                <ul class="c-list" id="list">
                  <li class="c-list__item">
                    <div class="c-list__grid">
                      <div class="u-text--left u-text--small u-text--medium">Rang</div>
                      <div class="u-text--left u-text--small u-text--medium">Membres
                        <?php
                        if ($result = mysqli_query($bdd, $sql) and $result2 = mysqli_query($bdd, $sql2)) {
                          if (mysqli_num_rows($result) > 0) {
                            $rang = 1;
                        ?>
                            <?php while ($row = mysqli_fetch_array($result)) { ?>
                              <div class="c-list__grid" style="margin-left:-110px">
                                <div class="c-flag c-place u-bg--transparent"><?php echo "</br>". $rang++ ?></div>
                                <div class="c-media">
                                  <?php echo '<img class="c-avatar c-media__img" src="data:image/jpeg;base64,' . base64_encode($row['photo_profil']) . '"/>'; ?>
                                  <div class="c-media__content">
                                    <div class="c-media__title"></br><?php echo $row['prenom'] ?> <?php echo $row['nom'] ?></div>
                                    <a class="c-media__link u-text--small" href="user.php?id=<?php echo $row['id'] ?>"><?php echo $row['pseudo'] ?></a>
                                    </br></br>
                                  </div>
                                </div>
                                <div class="u-text--right c-kudos">
                                  <div class="u-mt--8" style="margin-right:-100px"></br>
                                    <strong><?php echo $row['nbrAchats'] ?></strong>
                                  </div>
                                </div>
                              </div>
                            <?php } ?>

                      <?php
                          }
                        }
                      }
                      ?>
                      </div>
                      <div class="u-text--right u-text--small u-text--medium">achats</br></br>
                        <?php
                        if ($result = mysqli_query($bdd, $sql) and $result2 = mysqli_query($bdd, $sql2)) {
                          if (mysqli_num_rows($result) > 0) {
                        ?>
                        <?php
                          }
                        }
                        ?> </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

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