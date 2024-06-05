<?php 


require_once('Constants.php');

class Account{

    private $con;
    private $errorArray = array();

    public function __construct($con){

        $this->con = $con;
    }

    // S'inscrire

    public function register($firstname,$lastname,$username,$email, $confirmEmail,$password,$confirmPassword){
        
        $this->validateFirstname($firstname);
        $this->validateLastname($lastname);
        $this->validateUsername($username);
        $this->validateEmail($email,$confirmEmail);
        $this->validatePassword($password,$confirmPassword);

        // SI il n'y a pas d'erreur

        if(empty($this->errorArray)){

            return $this->insertDetails($firstname,$lastname,$username,$email,$password);
        }else{
            return false;
        }
    }
    // se connecter 

    public function login($username,$password){

        $password = hash("sha512",$password);

        $query = $this->con->prepare("SELECT * FROM users WHERE username=:username AND password=:password");

        $query->bindParam(":username",$username);
        $query->bindParam(":password",$password);

        $query->execute();

        // on vérifie le nombre de ligne retournée

        if($query->rowCount() ==1){
            return true;
        }else{

            array_push($this->errorArray,Constants::$loginFailed);
            return false;
        }

    }

    // Inserer les détails dans la BDD

    public function insertDetails($firstname,$lastname,$username,$email,$password){

        //crypter le mdp

        $password = hash("sha512",$password);
        $avatar = "assets/images/avatars/icons8-utilisateur-40.png"; // Ne trouve pas le chemin dans la barre de recherche

        $query = $this->con->prepare("INSERT INTO users(firstname,lastname,username,email,password,avatar)
                                    VALUES(:firstname,:lastname,:username,:email,:password,:avatar)");
        $query->bindParam(":firstname",$firstname);
        $query->bindParam(":lastname",$lastname);
        $query->bindParam(":username",$username);
        $query->bindParam(":email",$email);
        $query->bindParam(":password",$password);
        $query->bindParam(":avatar",$avatar);


        // la methode doit retourner vrai ou faux

        return $query->execute();
    }

    //valider tous les champs 

    private function validateFirstname($firstname){

        // Avoir un minimum de carateres 2  

        if(strlen($firstname) > 25 || strlen($firstname) < 2){ // || : Operateur qui signifie OR 

            // non valide 
            array_push($this->errorArray,Constants::$firstnameMsg);

        }
    }


    private function validateLastname($lastname){

        // Avoir un minimum de carateres 2  

        if(strlen($lastname) > 25 || strlen($lastname) < 2){ // || : Operateur qui signifie OR 

            // non valide 
            array_push($this->errorArray,Constants::$lastnameMsg);

        }
    }

    private function validateUsername($username){

        // Avoir un minimum de carateres 5  

        if(strlen($username) > 25 || strlen($username) < 5){ // || : Operateur qui signifie OR 

            // non valide 
            array_push($this->errorArray,Constants::$usernameMsg);
            return;
        }

        //requete pour vérifier si le pseudo existe déjà

        $query = $this->con->prepare("SELECT username FROM users WHERE username=:username");
        
        $query->bindParam("username",$username);
        $query->execute();

        // Si le serveur nous retourne un enregistrement ( une ligne ), on affiche l'erreur

            if($query->rowCount() !=0){

                array_push($this->errorArray,Constants::$usernameExistsMsg); // Pas possible d'afficher le message d'erreur pour le pseudo enregistrée en BDD
            }
    }

    private function validateEmail($email,$confirmEmail){

        // 1- les emails ne sont pas identiques

        if($email !=$confirmEmail){

            array_push($this->errorArray,Constants::$emailDifferentMsg);
            return;
        }

        // 2- L'email n'a pas le bon format

        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            array_push($this->errorArray,Constants::$emailInvalidMsg);
            return;
        }

        // 3- l'email existe déjà dans la BDD

        $query = $this->con->prepare("SELECT email FROM users WHERE email =:email");

        $query->bindParam(":email",$email);
        $query->execute();

        // si le serveur retourne un enregistrement (une ligne), on affiche un message d'erreur

        if($query->rowCount() !=0){

            array_push($this->errorArray,Constants::$emailTakenMsg);
        }
    }

    private function validatePassword($password,$confirmPassword){
        
        // 1- comparaison des mdp

        if($password != $confirmPassword){

            array_push($this->errorArray,Constants::$passwordDifferentMsg);
            return;
        }

        // 2- Valider le format ( regex )

        if(preg_match("/[^A-Za-z0-9]/",$password)){ // ce regex veut dire qu'on peut avoir des chaines de caracteres maj ou min, faire des / entre guillements pour commencer le regex 
            
            array_push($this->errorArray,Constants::$passwordInvalidMsg);
            return;
        }
        // 3- valider la taille du mot de passe

        if(strlen($password) > 25 || strlen($password) < 8){
            array_push($this->errorArray,Constants::$passwordLenghtMsg);
        }
    }

    // erreurs

    public function getError($error){
        
        if(in_array($error,$this->errorArray)){

            return "<small class='error'>$error</small>";

        }
    }
    // MAJ des détails

    public function updateDetails($firstname,$lastname,$email,$username){

        $this->validateFirstname($firstname);
        $this->validateLastname($lastname);
        $this->validateNewEmail($email,$username);

        if(empty($this->errorArray)){

            // Ok pour la MAJ dans la BDD

            $query = $this->con->prepare("UPDATE users SET firstname=:firstname, lastname=:lastname,email=:email WHERE username=:username");
            $query->bindParam(":firstname",$firstname);
            $query->bindParam(":lastname",$lastname);
            $query->bindParam(":email",$email);
            $query->bindParam(":username",$username);

            return $query->execute();
        }else{
            return false;
        }
    }

    private function validateNewEmail($email,$username){

        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            
            array_push($this->errorArray,Constants::$emailInvalidMsg);
            return;
        }

        $query = $this->con->prepare("SELECT email FROM users WHERE email=:email AND username !=:username");
        $query->bindParam(":email",$email);
        $query->bindParam(":username",$username);
        $query->execute();

        if($query->rowCount() !=0){
            array_push($this->errorArray,Constants::$emailTakenMsg);
        }
    }

    public function getFirstError(){

        if(!empty($this->errorArray)){
            return $this->errorArray[0];
        }else{
            return "";
        }
    }

    // maj du mdp

    public function updatePassword($oldPassword,$password,$confirmPassword,$username){
        
        $this->validateOldPassword($oldPassword,$username);
        $this->validatePassword($password,$confirmPassword);
        
        
        if(empty($this->errorArray)){
            
            // Ok pour la MAJ dans la BDD
           
            $query = $this->con->prepare("UPDATE users SET password=:password WHERE username=:username");
            $password =  hash("sha512", $password);
            $query->bindParam(":password",$password);
            $query->bindParam(":username",$username);


            return $query->execute();
        }else{
            return false;
        }
    }

    private function validateOldPassword($oldPassword,$username){

        $password = hash("sha512",$oldPassword);
        $query = $this->con->prepare("SELECT * FROM users WHERE username=:username AND password=:password");
        $query->bindParam(":username",$username);
        $query->bindParam(":password",$password);
        $query->execute();

        if($query->rowCount() ==0){
            array_push($this->errorArray,Constants::$passwordIncorrect);
        }
    }
}
?>