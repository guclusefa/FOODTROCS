<?php
session_start();

$bdd = new PDO('mysql:host=localhost;dbname=foodtrocs','root','root');

if(isset($_POST['formconnexion'])) {
   $mailconnect = htmlspecialchars($_POST['mailconnect']);
   $mdpconnect = sha1($_POST['mdpconnect']);
   if(!empty($mailconnect) AND !empty($mdpconnect)) {
      $requser = $bdd->prepare("SELECT * FROM membres WHERE mail = ? AND motedepasse = ?");
      $requser->execute(array($mailconnect, $mdpconnect));
      $userexist = $requser->rowCount();
      if($userexist == 1) {
         $userinfo = $requser->fetch();
         $_SESSION['id'] = $userinfo['id'];
         $_SESSION['pseudo'] = $userinfo['pseudo'];
         $_SESSION['mail'] = $userinfo['mail'];
         header("Location: profil.php?id=".$_SESSION['id']);
      } else {
         $erreur = "Mauvais mail ou mot de passe !";
      }
   } else {
      $erreur = "Tous les champs doivent être complétés !";
   }
}
?>

<html>
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="login.css" />
		<link rel="stylesheet" href="style1.css" />
		<link rel="stylesheet" href="style2.css" />
		<link rel="icon" type="image/png" href="images/foodtrocs.1.png" />
		<link href="logo/css/all.css" rel="stylesheet">
		<title>CONNEXION</title>
	</head>
   <body>
	    <div>
		   <nav class="test">
				<nav id="menu">
					<form action="action_page.php">
					<input type="text" placeholder="Recherchez.." name="search"><button type="submit">RECHERCHEZ</button>
					<a id="logo" href="page1.php"><img src="images/foodtrocs0.2.png" /><img src="images/foodtrocs1.2.png"/></a>	
					<ul>			
						<li><a href="profil.php"><i class="fa fa-fw fa-user"></i> COMPTE</a>
							<ul>
								<li><a href='connexion.php'>CONNEXION</a></li>
								<li><a href='inscription.php'>INSCRIPTION</a></li>
							</ul></li>
						</li>	
					</ul>
					</form>
				</nav>
			</nav>
		</div>
</br>
		<div id="login">
      		<form method="POST" action="" name='form-login'>
        	<span class="fontawesome-user"></span>
         		<input type="email" name="mailconnect" id="user" placeholder="Adresse e-mail">
        		<span class="fontawesome-lock"></span>
          		<input type="password" name="mdpconnect" id="pass" placeholder="Mot de passe">
				<input type="submit" name="formconnexion" value="Me connecter">
				<p>Vous avez pas de compte ? <a href="inscription.php">S'incrire</a></p>
			<?php
			if(isset($erreur)) {
   				echo '<font color="red">'.$erreur."</font>";
			}
			?>
			</form>
		</div>
</br>
		<footer>
			<ul>
			<img class="logofooter" src="images/foodtrocs3.png" />
			<ul>Réseaux Social
				<li><a class="facebook" href="https://fr-fr.facebook.com/"><i class="fab fa-facebook"> Facebook</i></a></li>
				<li><a class="twitter" href="https://twitter.com/?lang=fr"><i class="fab fa-twitter"> Twitter</i></a></li>
				<li><a class="instagram" href="https://www.instagram.com/?hl=fr"><i class="fab fa-instagram"> Instagram</i></a></li>
			</ul>
			<ul>Mentions légales
				<li><a href="#">A propos</a></li>
				<li><a href="#">Confidentialité</a></li>
				<li><a href="#">Cookies</a></li>
			</ul>
			<ul>Aider
				<li><a href="#">Nous contacter</a></li>
				<li><a href="#">FAQ</a></li>
				<li><a href="#">Type de cuisine</a></li>
			</ul>
			<ul>Gardez FoodTrocs dans votre poche
				<li><a href="https://play.google.com/store"><img class="store" src="images/gstore.jpg"/></a></li>
				<li><a href="https://www.apple.com/fr/ios/app-store/"><img cl ass="store" src="images/astore.png"/></a></li>
			</ul>
			</br></br></br></br>
			<ul>
				<li>©2020 FoodTrocs Technologies Inc.</li>
			</ul>
		</footer>
  	</body>
</html>
