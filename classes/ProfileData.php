<?php

    class ProfileData{

        private $con,$profileUser;
        public function __construct($con,$profileUser){

            $this->con = $con;
            $this->profileUser = new User($con,$profileUser);
        }

        public function getProfileUsername(){

            // Retourner l'username de la classe User via getUsername()
            return $this->profileUser->getUsername();
        }

        public function userExists(){

            $query = $this->con->prepare("SELECT * FROM users WHERE username =:username");
            $query->bindParam(":username",$profileUsername);
            $profileUsername = $this->getProfileUsername();
            $query->execute();


            // retourne vrai
            return $query->rowCount() !=0; 
        }

        // photo de couverture

        public function getCoverPhoto(){

            return "assets/images/banner4.jpg";
        }


        // Fullname

        public function getProfileFullname(){
            return $this->profileUser->getName();
        }
        // Avatar

        public function getProfileAvatar(){
            return $this->profileUser->getAvatar();
        }

        // Nombre d'abonnées 

        public function getProfileSubscriptionsCount(){

            return $this->profileUser->getSubscriberCount();
        }
        // vidéos

        public function getUserVideo(){

            $query = $this->con->prepare("SELECT * FROM videos WHERE uploadedBy=:uploadedBy ORDER BY uploadDate DESC");
            $query->bindParam(":uploadedBy",$username);
            $username = $this->getProfileUsername();
            $query->execute();

            // On a les vidéos

            $videos = array();

            while($row = $query->fetch(PDO::FETCH_ASSOC)){

                /* $videos= new Video($this->con,$row,$this->getProfileUsername());
                array_push($videos,$video); */

                $videos[] = new Video($this->con,$row,$this->getProfileUsername());
            }

            return $videos;
        
        }
        // Détails utilisateur 

        public function getProfileUser(){

            return $this->profileUser;
        }

        public function getAllUserDetails(){

            return array(
                        "Nom"=>$this->getProfileFullname(),
                        "Pseudo"=>$this->getProfileUsername(),
                        "Abonné(s)"=>$this->getProfileSubscriptionsCount(),
                        "Nombre de vues"=>$this->getTotalViews(),
                        "Date d'inscription"=>$this->getSignUpdate()
            );
        }


        private function getTotalViews(){

            $query = $this->con->prepare("SELECT sum(views) FROM videos WHERE uploadedBy=:uploadedBy");
            $query->bindParam(":uploadedBy",$username);
            $username = $this->getProfileUsername();
            $query->execute();

            return $query->fetchColumn();
        }

        private function getSignUpdate(){

            $date = $this->profileUser->getCreatedAt();

            return date("j/m/Y",strtotime($date));

        }
    }
?>