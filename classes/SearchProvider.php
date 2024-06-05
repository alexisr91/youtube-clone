<?php 


    class SearchProvider{

        private $con, $currentUser;

        public function __construct($con, $currentUser){
            $this->con = $con;
            $this->currentUser = $currentUser;
        
        }

        public function getVideos($term,$orderBy){

            // exemple -> exemples

            $query = $this->con->prepare("SELECT * FROM videos WHERE title 
                                        LIKE CONCAT('%', :term,'%')
                                        OR uploadedBy LIKE CONCAT('%',:term,'%')
                                        OR description LIKE CONCAT('%',:term,'%')
                                        ORDER BY $orderBy DESC");
            $query->bindParam(":term",$term); 

            $query->execute();

            $videos = array();

            while ($row = $query->fetch(PDO::FETCH_ASSOC)){

                $video = new Video($this->con,$row,$this->currentUser);
                array_push($videos,$video); // Ou on peut faire comme tel $videos[] = $video;
            }

            return $videos;

        }
    }


?>