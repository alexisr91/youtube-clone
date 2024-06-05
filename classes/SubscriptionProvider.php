<?php 

class SubscriptionProvider{

    private $con,$currentUser;

    public function __construct($con,$currentUser){
            $this->con =$con;
            $this->currentUser = $currentUser;
    }

    public function getVideos(){
        $videos = array();
        $subscriptions = $this->currentUser->getSubscriptions();

        if(sizeof($subscriptions) > 0){
            // User 1, user 2, user 3..

            // SELECT * FROM videos WHERE uploadedBy = user1 OR uploadedBy= user 2 OR uploadedBy= user3
            $condition = "";

            $i = 0;

            while($i < sizeof($subscriptions)){ // sizeof = une fonction qui renvoie la taille du tableau

                if($i == 0){
                    $condition .="WHERE uploadedBy=?";
                }else{
                    $condition .=" OR uploadedBy=?";
                }
                $i++;
            }

            $videoSql = "SELECT * FROM videos $condition ORDER BY uploadDate DESC";
            $videoQuery = $this->con->prepare($videoSql);

            $i = 1;

            foreach($subscriptions as $sub){

                $subUsername = $sub->getUsername();
                $videoQuery->bindValue($i,$subUsername); // bindValue = méthode qui permet d'associer une valeur à un parametre 

                $i++; // Incrémentation +1
            }

            $videoQuery->execute();

            while($row = $videoQuery->fetch(PDO::FETCH_ASSOC)){ // FETCH_ASSOC = c'est une option que l'on peut rajouter qu'on je fais un fetch avec PDO 
                $video = new Video($this->con,$row,$this->currentUser); // Instanciation d'un objet 
                array_push($videos,$video);  // L'objet instancié se retrouve dans un tableau                 
            }
        }

        return $videos;
    }
}

// FOR = On connait généralement le nombre de passage de la boucle 
// WHILE =  Alors que while on ne sait pas combien de fois ça va s'executer 

?>