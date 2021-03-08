<html>

<head>
  <title>PAGE ADMIN / FOOD TROCS</title>
  <meta charset="UTF-8">
  <link rel="icon" type="image/png" href="images/foodtrocs.1.png" />
  <link href="logo/css/all.css" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Amatic+SC">
  <link rel="stylesheet" href="accueil/accueil.css">
</head>
<style>
  table {
    border-collapse: collapse;
    width: 100%;
  }

  th,
  td {
    padding: 0.75rem;
    text-align: left;
    border: 1px solid #eee;
    font-size: 1.5em;
  }

  tbody tr:nth-child(odd) {
    background: #ccc;
  }
</style>
<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=jajaydck_foodtrocs', 'jajaydck_root', '$1E3q_TkSYj6u9');
$id = $_SESSION['id'];
$admin = $bdd->prepare("SELECT * FROM `admin` WHERE idAdmin = $id");
$admin->execute(array($_SESSION['id']));
$useradmin = $admin->fetch();
$idAdmin = $useradmin['idAdmin'];

$requser = $bdd->prepare("SELECT * FROM membres WHERE id = ?");
$requser->execute(array($_SESSION['id']));
$userinfo = $requser->fetch();

$rep1 = $bdd->prepare("SELECT COUNT(*) as total FROM `faq` WHERE date_r IS NULL");
$rep1->execute(array($_SESSION['id']));
$drep1 = $rep1->fetch();

$rep2 = $bdd->prepare("SELECT COUNT(*) as total FROM `faq` WHERE date_r IS NOT NULL");
$rep2->execute(array($_SESSION['id']));
$drep2 = $rep2->fetch();

$rep3 = $bdd->prepare("SELECT COUNT(*) as total FROM `membres`");
$rep3->execute(array($_SESSION['id']));
$drep3 = $rep3->fetch();

$rep4 = $bdd->prepare("SELECT COUNT(*) as total FROM `produits`");
$rep4->execute(array($_SESSION['id']));
$drep4 = $rep4->fetch();

$rep5 = $bdd->prepare("SELECT COUNT(*) as total FROM `code_promo`");
$rep5->execute(array($_SESSION['id']));
$drep5 = $rep5->fetch();

if (isset($idAdmin)) {
  $bdd2 = mysqli_connect("localhost", "jajaydck_root", "$1E3q_TkSYj6u9", "jajaydck_foodtrocs");
  $sql = "SELECT * FROM membres WHERE membres.id <> $id";
  $sql2 = "SELECT * FROM membres , produits WHERE membres.id = produits.idMembre";
  $sql3 = "SELECT * FROM membres, produits WHERE membres.id = produits.idMembre AND `type`= 'dessert' AND produits.idMembre <> $id";
  $sql4 = "SELECT * FROM membres, produits WHERE membres.id = produits.idMembre AND provenance = 'Turquie' AND produits.idMembre <> $id";
  $sql5 = "SELECT * FROM faq WHERE reponse = 'AUCUNE REPONSE'";
  $sql6 = "SELECT * FROM faq WHERE reponse <> 'AUCUNE REPONSE'";
  $sql7 = "SELECT * FROM code_promo";
?>

  <body>
  <?php
          if(isset($_POST['rech'])){
    header("location: recherche.php?search=".$_POST['rech']);
}
?>
    <div class="w3-top w3-hide-small">
      <div class="w3-bar w3-xlarge w3-black w3-opacity w3-hover-opacity-off" id="myNavbar">
        <a href="deconnexion.php" style="font-size:1.52em; text-decoration: none; margin-top:9.8px;" class="w3-bar-item w3-button, couleurhover" title="Déconnexion !"><i class="fas fa-door-open"></i></a>
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
          <button href="recherche.php" style="margin-top:9.8px;font-size:1.52em; background-color:black; color:white; cursor :pointer;float:right;" class="w3-bar-item couleurhover" title="Recherche !"><i class="fas fa-search couleurhover cursor :pointer;"></i></button>
          </form>
      </div>
    </div>
    </br></br></br>


    <?php
    if ($result = mysqli_query($bdd2, $sql) and $result2 = mysqli_query($bdd2, $sql2) and $result3 = mysqli_query($bdd2, $sql3)  and $result4 = mysqli_query($bdd2, $sql4) and $result5 = mysqli_query($bdd2, $sql5) and $result6 = mysqli_query($bdd2, $sql6) and $result7 = mysqli_query($bdd2, $sql7)) {
    ?>
<a href="newsletter.php"><h1>newsletter</h1></a>
<table>
        <caption>
          <h1>Les codes promo (<?php echo $drep5['total'];?>) <a href="ajout_code.php">ajouter</a></h1>
        </caption>
        <thead>
          <tr>
            <th>nom</th>
            <th>réduction</th>
            <th>prixmin</th>
            <th>nombre de codes</th>
            <th>modifier</th>
            <th>répondre</th>
          </tr>
        </thead>

        <tbody>
          <?php while ($row7 = mysqli_fetch_array($result7)) { ?>
            <tr>
              <td><?php echo $row7['nom_code']; ?></a></td>
              <td><?php echo $row7['taux_code']; ?>€</td>
              <td><?php echo $row7['prixmin_code']; ?>€</td>
              <td><?php echo $row7['nmbre_code']; ?></td>
              <td><a style="color:green;" href="modifier_code.php?id=<?php echo $row7['id_code'] ?>">Modfifier !</a></td>
              <td><a style="color:red;" href="delete_code.php?id=<?php echo $row7['id_code'] ?>">Supprimer !</a></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>

      <table>
        <caption>
          <h1>Les questions (<?php echo $drep1['total'];?>)</h1>
        </caption>
        <thead>
          <tr>
            <th>pseudo</th>
            <th>mail</th>
            <th>question</th>
            <th>date</th>
            <th>répondre</th>
          </tr>
        </thead>

        <tbody>
          <?php while ($row = mysqli_fetch_array($result5)) { ?>
            <tr>
              <td><a href="user.php?id=<?php echo $row['idMembre'] ?>"><?php echo $row['pseudo']; ?></a></td>
              <td><?php echo $row['mail']; ?></td>
              <td><?php echo nl2br($row['question']); ?></td>
              <td><?php echo $row['date_q']; ?></td>
              <td><a style="color:green;" href="repondre.php?id=<?php echo $row['id'] ?>">Répondre !</a></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>

      <table>
        <caption>
          <h1>Les question traités (<?php echo $drep2['total'];?>)</h1>
        </caption>
        <thead>
          <tr>
            <th>pseudo</th>
            <th>mail</th>
            <th>question</th>
            <th>date question</th>
            <th>reponse</th>
            <th>date reponse</th>
            <th>répondeur</th>
          </tr>
        </thead>

        <tbody>
          <?php while ($row = mysqli_fetch_array($result6)) { ?>
            <tr>
              <td><a href="user.php?id=<?php echo $row['idMembre'] ?>"><?php echo $row['pseudo']; ?></a></td>
              <td><?php echo $row['mail']; ?></td>
              <td><?php echo nl2br($row['question']); ?></td>
              <td><?php echo $row['date_q']; ?></td>
              <td><?php echo nl2br($row['reponse']); ?></td>
              <td><?php echo $row['date_r'] ?></td>
              <td><a href="user.php?id=<?php echo $row['id_a'] ?>"><?php echo $row['pseudo_a'] ?></a></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>

      <table>
        <caption>
          <h1>Les membres (<?php echo $drep3['total'];?>)</h1>
        </caption>
        <thead>
          <tr>
            <th>id</th>
            <th>photo</th>
            <th>pseudo</th>
            <th>prenom</th>
            <th>nom</th>
            <th>supprimer</th>
          </tr>
        </thead>

        <tbody>
          <?php while ($row = mysqli_fetch_array($result)) { ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
              <td><a href="user.php?id=<?php echo $row['id'] ?>"><?php echo '<img style="height:50px; width:50px;" src="data:image/jpeg;base64,' . base64_encode($row['photo_profil']) . '"/>'; ?></a></td>
              <td><a href="user.php?id=<?php echo $row['id'] ?>"><?php echo $row['pseudo']; ?></a></td>
              <td><?php echo $row['prenom']; ?></td>
              <td><?php echo $row['nom']; ?></td>
              <td><a style="color:red;" href="delete_user.php?id=<?php echo $row['id'] ?>">Supprimer!</a></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>

      <table>
        <caption>
          <h1>Les plats (<?php echo $drep4['total'];?>)</h1>
        </caption>
        <thead>
          <tr>
            <th>id</th>
            <th>photo</th>
            <th>intitule</th>
            <th>type</th>
            <th>pseudo</th>
            <th>supprimer</th>
          </tr>
        </thead>

        <tbody>
          <?php while ($row = mysqli_fetch_array($result2)) { ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
              <td><a href="acheter.php?id=<?php echo $row['id'] ?>"><?php echo '<img style="height:50px; width:50px;" src="data:image/jpeg;base64,' . base64_encode($row['photo_produit']) . '"/>'; ?></a></td>
              <td><a href="acheter.php?id=<?php echo $row['id'] ?>"><?php echo $row['intitule']; ?></a></td>
              <td><?php echo $row['type']; ?></td>
              <td><?php echo $row['pseudo']; ?></td>
              <td><a style="color:red;" href="delete_plat.php?id=<?php echo $row['id'] ?>">Supprimer!</a></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>


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
    }
  } else {
    header("Location: accueil.php");
  }
?>