<?php
session_start();
$mail_v = $_SESSION['mail_v'];
?>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MDP OUBLIÉ / FOOD TROCS</title>
  <meta charset="UTF-8">
  <link rel="icon" type="image/png" href="images/foodtrocs.1.png" />
</head>
<?php
if (isset($_POST['formvalid']) and $_POST['valid'] != $_SESSION['code_v']){
    $msg = "mauvais code !";
}
if (isset($_POST['formvalid']) and $_POST['valid'] == $_SESSION['code_v']){
    $_SESSION['test'] = 'test';
    header("location: mdp_oublie_new.php");
}
if (isset($msg)){
    echo "<font color='red'>".$msg ."</font></br>";
}
?>
<p>un code de validation vous a été envoyé à l'email : <b><?php echo $_SESSION['mail_v']?></b></p>
<form method="POST" action="" enctype="multipart/form-data">
<label>Code de validation : </label>
<input type="text" placeholder="code de validation" required name="valid"/>
<input type="submit" name="formvalid"/>
</form>

<?php
if (empty($_SESSION['mail_v'])) {
  header("Location: accueil.php");
}
if (isset($_SESSION['id'])) {
  header("Location: accueil.php");
}
?>