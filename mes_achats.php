<?php
session_start();
$bdd = mysqli_connect("localhost", "jajaydck_root", "$1E3q_TkSYj6u9", "jajaydck_foodtrocs");
$bdd2 = new PDO('mysql:host=localhost;dbname=jajaydck_foodtrocs', 'jajaydck_root', '$1E3q_TkSYj6u9');
$idm = $_SESSION['id'];
    $sql = "SELECT * FROM commandes, produits, membres WHERE id_client = $idm AND id_produit = produits.id AND id_vendeur = membres.id ORDER BY date_commande";
    $sqlbis = "SELECT id_produit FROM commandes WHERE id_client = $idm AND id_produit = 'SUPPR' ";

    $sql2 = $bdd2->prepare("SELECT COUNT(*) as total FROM commandes, produits, membres WHERE id_client = $idm AND id_produit = produits.id AND id_vendeur = membres.id ORDER BY                  date_commande");
    $sql2->execute(array($_SESSION['id']));
    $r_sql2 = $sql2->fetch();

    $sql3 = $bdd2->prepare("SELECT `id_vendeur` FROM `commandes` WHERE `id_client` = $idm GROUP BY `id_vendeur` ORDER BY COUNT(*) DESC LIMIT 1");
    $sql3->execute(array($_SESSION['id']));
    $r_sql3 = $sql3->fetch();
    $pref = $r_sql3['id_vendeur'];

    $sql3bis = $bdd2->prepare("SELECT COUNT(id_vendeur) as total FROM commandes WHERE id_client = '$idm' AND id_vendeur = '$pref'");
    $sql3bis->execute(array($_SESSION['id']));
    $r_sql3bis = $sql3bis->fetch();
    $prefbis = $r_sql3bis['total'];

    $sql4 = $bdd2->prepare("SELECT * FROM membres WHERE id = $pref");
    $sql4->execute(array($_SESSION['id']));
    $r_sql4 = $sql4->fetch();

    $sql6 = $bdd2->prepare("SELECT sum(prix_commande) as qte, pseudo from commandes, membres where id_client = '$idm' and id_vendeur = membres.id group by pseudo order by sum(prix_commande) desc");
    $sql6->execute(array($_SESSION['id']));
    $r_sql6 = $sql6->fetch();

    $sql5 = $bdd2->prepare("SELECT intitule, id_produit, SUM(qte_commande) as total
FROM commandes, produits
where id_client = '$idm'
and id_produit = produits.id
GROUP BY id_produit
order by sum(qte_commande) desc");
    $sql5->execute(array($_SESSION['id']));
    $r_sql5 = $sql5->fetch();
$i = 1
?>

<head>
    <title>MES ACHATS / FOOD TROCS</title>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="images/foodtrocs.1.png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
<p><a href="accueil.php">accueil</a></br>
sieste sur le css car c'est : ennuaynt</p>
<?php
    if ($result = mysqli_query($bdd, $sql) and $resultbis = mysqli_query($bdd, $sqlbis)){
        if (mysqli_num_rows($result) > 0) {
?>
<p><b><?php echo $r_sql2['total'];?></b> achats</p>
<p>passé le plus de commandes chez : <b><?php echo $r_sql4['pseudo'];?> (<?php echo $prefbis;?>)</b></p>
<p>dépenser le plus chez : <b><?php echo $r_sql6['pseudo'];?> (<?php echo $r_sql6['qte'];?>€ d'achats)</b></p>
<p>plat préf: <b><?php echo $r_sql5['intitule'];?> (<?php echo $r_sql5['total'];?>)</b></p>
<?php
            while ($row = mysqli_fetch_array($result)) {
?>
<?php
                echo ' achat n°';
                echo $i++;
                echo ' <b> ';
?>
<a href="acheter.php?id=<?php echo $row['id_produit'] ?>">
<?php
                echo '<img style="height:50px; width:50px;" src="data:image/jpeg;base64,' . base64_encode($row['photo_produit']) . '"/>';
                echo '</a> ';
?>
<?php
                echo $row['qte_commande'];
                echo ' ';
?>
<a href="acheter.php?id=<?php echo $row['id_produit'] ?>">
<?php
                echo $row['intitule'];
                echo '</a></b> acheté de <b> ';
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
        } else {?><h1>Aucun achat</h1>
<?php } 
    }
?>