<?php 

    class LikedProvider{

        private $con,$currentUser;

        public function __construct($con,$currentUser){

            $this->con = $con;
            $this->currentUser = $currentUser;
        }

        public function getVideos(){

            $videos = array();

                $query = $this->con->prepare("SELECT videoId FROM likes WHERE username=:username ORDER BY id DESC"); 
                
                
                $query->bindParam(":username",$username);
                $username = $this->currentUser->getUsername();
                $query->execute();

                while($row = $query->fetch(PDO::FETCH_ASSOC)){

                    $video = new Video($this->con,$row["videoId"],$this->currentUser);
                    array_push($videos,$video);
                }
            return $videos;
        }
    }


?>