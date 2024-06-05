<?php 
    
    require_once('includes/config.php');
    require_once('classes/FormSanitizer.php');
    require_once('classes/Account.php');

    $account = new Account($con);

    if(isset($_POST["loginBtn"])){

            $username = FormSanitizer::sanitizeFormUsername($_POST["username"]);
            $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);

            $isSuccessful = $account->login($username,$password);

            if($isSuccessful){
                // ok 

                $_SESSION["userLoggedIn"] = $username;

                // redirection vers l'index
                header("Location:index.php");
            }else{
                
                echo "erreur";
                // pas ok 
            }

    }
    
    function getInputValue($name){
    if(isset($_POST["$name"])){
        echo $_POST[$name];
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Connexion Youtube like</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    
    <main id="registerContainer">
        <section class="column">
            <div class="header">
                <img src="assets/images/logo-youtube.png" alt="">
                <h3>Connectez-vous</h3>
                <small>Pour poster des vidéos.</small>
            </div>

            <div id="registerForm" class="mt-3">
                <form action="signIn.php" method="POST">
                    <?php echo $account->getError(Constants::$loginFailed); ?>
                    <div class="form-group">
                        <input type="text" class="form-control mt-3" name="username" placeholder="Votre pseudo" autocomplete="off" required value="<?php getInputValue('username') ?>"> 
                    </div>
                   
                    <div class="form-group">
                        <input type="password" class="form-control mt-3" name="password" placeholder="Votre mot de passe" autocomplete="off" required> 
                    </div>

                    <div class="form-group">
                        <input type="submit" class="btn btn-primary form-control mt-3 mb-3" name="loginBtn" value="Se connecter"> 
                    </div>
                </form>
            </div>
            <a href="signUp.php"><small class="mt-3">Pas encore un compte ? Connectez vous en cliquant ici</small></a>
        </section>

    </main>

</body>
</html>