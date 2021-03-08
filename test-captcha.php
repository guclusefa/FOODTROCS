<?php
require 'recaptchalib.php';
$siteKey = '6LeWA9wZAAAAAHt3UnMxpZnhZYnypzgTdlRKvsyl'; // votre clé publique
$secret = '6LeWA9wZAAAAAD-d6-QY1r4fuVuHgV2liPFvR7pP'; // votre clé privée
?>
<html>

 <head>
  <title>Ma page</title>
  <script src="https://www.google.com/recaptcha/api.js"></script>
 </head>

 <body>
<?php
$reCaptcha = new ReCaptcha($secret);
if(isset($_POST["g-recaptcha-response"])) {
    $resp = $reCaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $_POST["g-recaptcha-response"]
        );
    if ($resp != null && $resp->success) {echo "OK";}
    else {echo "CAPTCHA incorrect";}
    }
?>     
  <form action="test-captcha.php" method="POST">
   <div class="g-recaptcha" data-sitekey="<?php echo $siteKey; ?>"></div>
   <input type="submit" value="Envoyer">
  </form>
 </body>

</html>