<?php

class User{
    
    private $con,$sqlData;
    public function __construct($con,$username){
        
    
        $this->con = $con;
        $query = $this->con->prepare("SELECT * FROM users WHERE username=:username");
        $query->bindParam(":username",$username);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC); // ça demande à récupérer les données de la requete et à les mettres dans un tableaux associatif
                                                          //fetch est utilisé quant tu attends une seule ligne de résultat
                                                       
    }

    public function getUsername(){
        
        return $this->sqlData["username"];
    }

    public function getName(){

        return $this->sqlData["firstname"]." ". $this->sqlData["lastname"];
    }

    public function getFirstname(){

        return $this->sqlData["firstname"];
    }

    public function getLastname(){

        return $this->sqlData["lastname"];
    }

    public function getEmail(){

        return $this->sqlData["email"];
    }

    public function getAvatar(){
        
        return $this->sqlData["avatar"];
        
        
    }

    public function getCreatedAt(){

        return $this->sqlData["createdAt"];
    } 
    
    public static function isLoggedIn(){

        return isset($_SESSION["userLoggedIn"]);
    }

    public function isSubscribedTo($userTo){

        $query = $this->con->prepare("SELECT * FROM subscribers WHERE userTo=:userTo AND userFrom=:userFrom");
        $query->bindParam(":userTo",$userTo);
        $query->bindParam(":userFrom",$username);
        $username = $this->getUsername();
        $query->execute();
        return $query->rowCount() > 0; // retourne vrai ou faux dans ce type de comparaison
    }

    public function getSubscriberCount(){

        // retourne un nombre de lignes donc d'abonné

        $query = $this->con->prepare("SELECT * FROM subscribers WHERE userTo=:userTo");
        $query->bindParam(":userTo",$username);
        $username = $this->getUsername();
        $query->execute();
        return $query->rowCount(); // retourne vrai ou faux dans ce type de comparaison

    }

    public function getSubscriptions(){

        $query = $this->con->prepare("SELECT userTo FROM subscribers WHERE userFrom=:userFrom");
        $username = $this->getUsername();
        $query->bindParam(":userFrom",$username);
        $query->execute();

        $subscriptions = array();
        while($row = $query->fetch(PDO::FETCH_ASSOC)){

            $user = new User($this->con,$row["userTo"]);
            array_push($subscriptions,$user);
        }
        return $subscriptions;
    }
}   
?>