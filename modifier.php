<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=jajaydck_foodtrocs', 'jajaydck_root', '$1E3q_TkSYj6u9');
$bdd2 = mysqli_connect("localhost", "jajaydck_root", "$1E3q_TkSYj6u9", "jajaydck_foodtrocs");
$id = $_GET['id'];
$idsession = $_SESSION['id'];
if (isset($_SESSION['id'])) {
    $admin = $bdd->prepare("SELECT * FROM `admin` WHERE idAdmin = $idsession");
    $admin->execute(array($_SESSION['id']));
    $useradmin = $admin->fetch();
    $idAdmin = $useradmin['idAdmin'];

    $requser = $bdd->prepare("SELECT * FROM membres WHERE id = ?");
    $requser->execute(array($_SESSION['id']));
    $user = $requser->fetch();

    $requser2 = $bdd->prepare("SELECT * FROM produits WHERE id = $id");
    $requser2->execute(array($_SESSION['id']));
    $produits = $requser2->fetch();
    $img = $produits['type'];
    $idMembre = $produits['idMembre'];
    $photo = base64_encode($produits['photo_produit']);
    if ($idsession ==  $idMembre) { /*on verifie si l'id du membre est = a l'id de la session */
        if (isset($_POST['formoui'])) {
            if (isset($_POST['type'])) {
                $type = htmlspecialchars($_POST['type']);
                $inserttype = $bdd->prepare("UPDATE produits SET `type` = ? WHERE id = ?");
                $inserttype->execute(array($type, $_GET['id']));
                header('Location: mes_plats.php?id=' . $_SESSION['id']);
            }
            if (isset($_POST['intitule'])) {
                $intitule = htmlspecialchars($_POST['intitule']);
                $insertint = $bdd->prepare("UPDATE produits SET intitule = ? WHERE id = ?");
                $insertint->execute(array($intitule, $_GET['id']));
                header('Location: mes_plats.php?id=' . $_SESSION['id']);
            }
            if (isset($_POST['description'])) {
                $description = htmlspecialchars($_POST['description']);
                $insertdesc = $bdd->prepare("UPDATE produits SET `description` = ? WHERE id = ?");
                $insertdesc->execute(array($description, $_GET['id']));
                header('Location:mes_plats.php?id=' . $_SESSION['id']);
            }
            if (isset($_POST['provenance'])) {
                $provenance = htmlspecialchars(strtolower($_POST['provenance']));
                $insertpro = $bdd->prepare("UPDATE produits SET `provenance` = ? WHERE id = ?");
                $insertpro->execute(array($provenance, $_GET['id']));
                header('Location: mes_plats.php?id=' . $_SESSION['id']);
            }
            if (isset($_POST['prix'])) {
                $prix = htmlspecialchars($_POST['prix']);
                $insertprix = $bdd->prepare("UPDATE produits SET `prix` = ? WHERE id = ?");
                $insertprix->execute(array($prix, $_GET['id']));
                header('Location: mes_plats.php?id=' . $_SESSION['id']);
            }
            if (isset($_FILES['myfile']['tmp_name']) and !empty($_FILES['myfile']['tmp_name'])) {
                $data = file_get_contents($_FILES['myfile']['tmp_name']);
                $image_info = @getimagesize($_FILES['myfile']['tmp_name']);
                if($image_info == false) {
                $erreur = "Photo invalide !";
                }else {
                $stmt = $bdd->prepare("UPDATE produits SET photo_produit = ? WHERE id= $id");
                $stmt->bindParam(1, $data);
                $stmt->execute();}
            }
        }
        if (isset($_POST['formnon'])) {
            header("location: mes_plats.php");
        }
?>
        <html>

        <head>
            <title>MODIFIER MON PLAT / FOODTROCS</title>
            <meta charset="UTF-8">
            <link rel="icon" type="image/png" href="images/foodtrocs.1.png" />
            <link href="logo/css/all.css" rel="stylesheet">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Amatic+SC">
            <link rel="stylesheet" href="accueil/accueil.css">
            <link rel="stylesheet" href="mes_plats/mes_plats.css">
            <meta charset="utf-8">
        </head>
        <style>
            .container {
                background-image: url("data:image/png;base64,<?php echo $photo ?>");
                background-size: cover;
            }
        </style>

        <body>
            <!-- Navbar (sit on top) -->
            <?php
          if(isset($_POST['rech'])){
    header("location: recherche.php?search=".$_POST['rech']);
}
?>
            <div class="w3-top w3-hide-small">
                <div class="w3-bar w3-xlarge w3-black w3-opacity w3-hover-opacity-off" id="myNavbar">
                    <a href="deconnexion.php" style="font-size:1.52em; text-decoration: none; margin-top:9.8px;" class="w3-bar-item w3-button, couleurhover" title="Déconnexion !"><i class="fas fa-door-open"></i></a>
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
          <button href="recherche.php" style="margin-top:9.8px;font-size:1.52em; background-color:black; color:white; cursor :pointer;float:right;" class="w3-bar-item couleurhover" title="Recherche !"><i class="fas fa-search couleurhover cursor :pointer;"></i></button>
          </form>
                </div>
            </div>

            <form class="container" method="POST" action="" enctype="multipart/form-data"></br></br>
                <div class="introduction">
                    <h1 class="title title-1" id="title">Modifier votre plat</h1>
                    <p class="text">
                        Merci de renseigner les informations demandées ci-dessous afin de valider la modification de votre plat.
                    </p>
                </div>

                <div class="form-container">
                    <div class="infoClient">
                        <div class="form__group">
                            <input type="text" class="form__input" id="newint" name="intitule" placeholder="Intitule" value="<?php echo $produits['intitule']; ?>" required>
                            <label for="name" class="form__label" id="name-label">Intitule</label>
                        </div>

                        <div class="form__group">
                            <input type="text" class="form__input" id="newpro" name="provenance" placeholder="Provenace" value="<?php echo $produits['provenance']; ?>" required>
                            <label for="email" class="form__label" id="email-label">Provenace</label>
                        </div>

                        <div class="form__group">
                            <input type="number" class="form__input" id="newprix" name="prix" placeholder="Prix" required step="1" min="1" max="99" value="<?php echo $produits['prix']; ?>" required>
                            <label for="number" class="form__label" id="number-label">Prix en €</label>
                        </div>

                        <div class="form__group">
                            <textarea type="text" class="form__input" name="description" placeholder="Description" cols="50" rows="6" required><?php echo $produits['description']; ?></textarea>
                            <label for="name" class="form__label" id="name-label">Desctiption</label>
                        </div>

                    </div>

                    <div class="reservation">
                        <div class="form__group">
                            <input type="file" name="myfile" class="form__input"  accept="image/*" >
                            <label for="name" class="form__label" id="name-label"> Photo du plat</label>
                        </div>
                        <div class="form__group">
                            <p class="text bold">Type de plat :</p>
                            <div class="radio-btn">
                                <?php if ($img == "Entree") {
                                ?>
                                    <select class="form__input" id="newtype" name="type">
                                        <option><?php echo $produits['type']; ?></option>
                                        <option>Plat Chaud</option>
                                        <option>Dessert</option>
                                    </select></br>
                                <?php
                                }
                                ?>
                                <?php if ($img == "Plat Chaud") {
                                ?>
                                    <select class="form__input" id="newtype" name="type">
                                        <option><?php echo $produits['type']; ?></option>
                                        <option>Entree</option>>
                                        <option>Dessert</option>
                                    </select></br>
                                <?php
                                }
                                ?>
                                <?php if ($img == "Dessert") {
                                ?>
                                    <select class="form__input" id="newtype" name="type">
                                        <option><?php echo $produits['type']; ?></option>
                                        <option>Entree</option>
                                        <option>Plat Chaud</option>
                                    </select></br>
                                <?php
                                }
                                ?>
                                </select>

                            </div>
                        </div>

                        <div class="submit">
                            <input type="submit" class="submitBtn2 bold" name="formoui" value="Modifier !">
            </form>
            <input type="submit" class="submitBtn bold" name="formnon" value="Annuler !"></input>
            <?php
        if (isset($erreur)) {
          echo '<h1><font color="red"><b>' . $erreur . "</b></font><h1>";
        }
        ?>
            </div>
            </div>
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
<?php
    } else{
        echo "lol";
        header("Refresh: 2;url=accueil.php");
    }
}
?>