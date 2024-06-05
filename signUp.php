<?php 
    require_once('includes/config.php');
    require_once('classes/FormSanitizer.php');
    require_once('classes/Account.php');

    $account = new Account($con);

    if(isset($_POST["registerBtn"])){

        $firstname = FormSanitizer::sanitizeFormString($_POST["firstname"]);
        $lastname = FormSanitizer::sanitizeFormString($_POST["lastname"]);
        $username = FormSanitizer::sanitizeFormUsername($_POST["username"]);
        $email = FormSanitizer::sanitizeFormEmail($_POST["email"]);
        $confirmEmail = FormSanitizer::sanitizeFormEmail($_POST["confirmEmail"]);
        $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);
        $confirmPassword = FormSanitizer::sanitizeFormPassword($_POST["confirmPassword"]);
        

        $isSuccessful = $account->register($firstname,$lastname,$username,$email,$confirmEmail,$password,$confirmPassword);

        if($isSuccessful){
            // success

            $_SESSION["userLoggedin"] = $username;

            // redirection vers la page d'accueil

            header("Location: index.php");
            
        }else{
            echo "<div class='d-flex justify-content-center text-danger'>Erreur dans la validation du formulaire</div>";
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
    <title>Inscription Youtube like</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    
    <main id="registerContainer">
        <section class="column">
            <div class="header">
                <img src="assets/images/logo-youtube.png" alt="">
                <h3>Inscrivez-vous</h3>
                <small>Pour poster des vidéos.</small>
            </div>

            <div id="registerForm" class="mt-3">
                <form action="signUp.php" method="POST">
                    <div class="form-group">
                        <input type="text" class="form-control mt-3" name="firstname" placeholder="Votre nom" autocomplete="off" required value="<?php getInputValue('firstname'); ?>"> 
                    </div>
                    <?php echo $account->getError(Constants::$firstnameMsg); ?>


                    <div class="form-group">
                        <input type="text" class="form-control mt-3" name="lastname" placeholder="Votre prénom" autocomplete="off" required value="<?php getInputValue('lastname'); ?>"> 
                    </div>
                    <?php echo $account->getError(Constants::$lastnameMsg); ?>

                    <div class="form-group">
                        <input type="text" class="form-control mt-3" name="username" placeholder="Votre pseudo" autocomplete="off" required value="<?php getInputValue('username'); ?>"> 
                        <?php echo $account->getError(Constants::$usernameExistsMsg); ?>
                    </div>

                    <div class="form-group">
                        <input type="email" class="form-control mt-3" name="email" placeholder="Confirmez votre email" autocomplete="off" required value="<?php getInputValue('email'); ?>"> 
                    </div>

                    <div class="form-group">
                        <input type="email" class="form-control mt-3" name="confirmEmail" placeholder="Confirmez votre email" autocomplete="off" required value="<?php getInputValue('confirmEmail'); ?>">
                        <?php echo $account->getError(Constants::$emailDifferentMsg); ?>
                        <?php echo $account->getError(Constants::$emailInvalidMsg); ?>
                        <?php echo $account->getError(Constants::$emailTakenMsg); ?> 
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control mt-3" name="password" placeholder="Votre mot de passe" autocomplete="off" required>
                        <?php echo $account->getError(Constants::$passwordDifferentMsg); ?> 
                        <?php echo $account->getError(Constants::$passwordInvalidMsg); ?> 
                        <?php echo $account->getError(Constants::$passwordLenghtMsg); ?>  
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control mt-3" name="confirmPassword" placeholder="Confirmez votre mot de passe" autocomplete="off" required> 
                    </div>

                    <div class="form-group">
                        <input type="submit" class="btn btn-primary form-control mt-3 mb-3" name="registerBtn" value="S'inscrire"> 
                    </div>


                </form>
            </div>
            <a href="signIn.php"><small>Vous avez déjà un compte ? Connectez vous en cliquant ici</small></a>
        </section>

    </main>

</body>
</html>