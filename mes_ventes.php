<?php
session_start();
$bdd = mysqli_connect("localhost", "jajaydck_root", "$1E3q_TkSYj6u9", "jajaydck_foodtrocs");
$bdd2 = new PDO('mysql:host=localhost;dbname=jajaydck_foodtrocs', 'jajaydck_root', '$1E3q_TkSYj6u9');
$idm = $_SESSION['id'];
    $sql = "SELECT * FROM commandes, produits, membres WHERE id_vendeur = $idm AND id_produit = produits.id AND id_client = membres.id ORDER BY date_commande";
    $sql2 = $bdd2->prepare("SELECT COUNT(*) as total FROM commandes, produits, membres WHERE id_vendeur = $idm AND id_produit = produits.id AND id_client = membres.id ORDER BY date_commande");
    $sql2->execute(array($_SESSION['id']));
    $r_sql2 = $sql2->fetch();

    $sql3 = $bdd2->prepare("SELECT `id_client` FROM `commandes` WHERE `id_vendeur` = $idm GROUP BY `id_client` ORDER BY COUNT(*) DESC LIMIT 1");
    $sql3->execute(array($_SESSION['id']));
    $r_sql3 = $sql3->fetch();
    $pref = $r_sql3['id_client'];

    $sql3bis = $bdd2->prepare("SELECT COUNT(id_client) as total FROM commandes WHERE id_vendeur = '$idm' AND id_client = '$pref'");
    $sql3bis->execute(array($_SESSION['id']));
    $r_sql3bis = $sql3bis->fetch();
    $prefbis = $r_sql3bis['total'];

    $sql4 = $bdd2->prepare("SELECT * FROM membres WHERE id = $pref");
    $sql4->execute(array($_SESSION['id']));
    $r_sql4 = $sql4->fetch();

    $sql5 = $bdd2->prepare("SELECT * FROM produits WHERE idMembre = $idm GROUP BY nbVentes DESC");
    $sql5->execute(array($_SESSION['id']));
    $r_sql5 = $sql5->fetch();
    $prefjsp = $r_sql5['id'];

    $sql6 = $bdd2->prepare("SELECT * FROM produits WHERE id = $pref");
    $sql6->execute(array($_SESSION['id']));
    $r_sql6 = $sql6->fetch();

    $sql7 = $bdd2->prepare("SELECT sum(prix_commande) as qte, pseudo from commandes, membres where id_vendeur = '$idm' and id_client = membres.id group by pseudo order by sum(prix_commande) desc");
    $sql7->execute(array($_SESSION['id']));
    $r_sql7 = $sql7->fetch();

$i = 1
?>

<head>
    <title>MES VENTES / FOOD TROCS</title>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/foodtrocs.1.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
<p><a href="accueil.php">accueil</a></br>
sieste sur le css car c'est : ennuaynt</p>
<?php
    if ($result = mysqli_query($bdd, $sql)){
        if (mysqli_num_rows($result) > 0) {
?>
<p><b><?php echo $r_sql2['total'];?></b> ventes</p>
<p>client ayant passé le plus de commande: <b><?php echo $r_sql4['pseudo'];?> (<?php echo $prefbis;?>)</b></p>
<p>client le plus depencier: <b><?php echo $r_sql7['pseudo'];?> (<?php echo $r_sql7['qte'];?>€ d'achats)</b></p>
<p>produit le plus vendue : <b><?php echo $r_sql5['intitule'];?> (<?php echo $r_sql5['nbVentes'];?>)</b></p>
<?php
            while ($row = mysqli_fetch_array($result)) {
?>
<?php
                echo ' vente n°';
                echo $i++;
                echo ' <b> ';
                if (empty($row['photo_produit'])){
                    echo 'plat supprimé';
                } else { 
                echo '<img style="height:50px; width:50px;" src="data:image/jpeg;base64,' . base64_encode($row['photo_produit']) . '"/>'; }
                echo ' ';
                if (empty($row['intitule'])){
                    echo 'plat supprimé';
                } else {
                echo $row['qte_commande'];
                echo ' ';
                echo $row['intitule'];}
                echo ' </b> acheté par <b> ';
?>
<a href="user.php?id=<?php echo $row['id'] ?>">
<?php
                echo '<img style="height:50px; width:50px;" src="data:image/jpeg;base64,' . base64_encode($row['photo_profil']) . '"/>';
                echo '</a> ';
?>
<a href="user.php?id=<?php echo $row['id'] ?>">
<?php
                echo $row['pseudo'];
                echo '</a></b> pour un total de <b>';
                echo $row['prix_commande']."€";
                echo ' </b> le <b> ';
                echo $row['date_commande']. "</b></br></br>";
?>
<?php
            }  
        } else {?><h1>Aucune vente</h1>
<?php } 
    }
?>