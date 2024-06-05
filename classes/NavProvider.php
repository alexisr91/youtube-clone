<?php 

    class NavProvider{


        private $con, $currentUser;

        public function __construct($con,$currentUser){
            $this->con = $con;
            $this->currentUser = $currentUser;
        }

        // Création des liens : texte - image - lien
        // home - 

        public function create(){

            // Lien sur le side Nav Container 

            $html = $this->createNavItem("Accueil","assets/images/icons/home.png","index.php");
            $html .= $this->createNavItem("Tendances","assets/images/icons/trending.png","trending.php");
            $html .= $this->createNavItem("Parametres","assets/images/icons/settings.png","settings.php");

            if(User::isLoggedIn()){
                $html .= $this->createNavItem("Abonnement","assets/images/icons/subscriptions.png","subscriptions.php"); // condition déplacé de la fonction CREATE  à la condition pour afficher les liens si on est pas co
                $html .= $this->createNavItem("Vidéos J'aime","assets/images/icons/thumb_up.png","likeVideos.php"); // condition déplacé de la fonction CREATE  à la condition pour afficher les liens si on est pas co
                $html .= $this->createNavItem("Déconnexion","assets/images/icons/logout.png","logout.php");
                
                $html .= $this->createSubscriptionSection();
            }
            
            return "<div class='navItems'>
                        $html
                    </div>";

        }

        private function createNavItem($text,$icon,$link){

            return "<div class='navigation'>
                        <a href='$link'>
                            <img src='$icon'>
                            <span>$text</span>
                        </a>
                    </div>";
        }


        private function createSubscriptionSection(){

            $subscriptions = $this->currentUser->getSubscriptions();

            $title = "<span class='heading'>Abonnements</span>";


            foreach($subscriptions as $sub){
               
                $subUsername = $sub->getUsername();
                $subAvatar = $sub->getAvatar();

                $title .=$this->createNavitem($subUsername,$subAvatar,"profile.php?username=$subUsername");
            }

            return $title;
        }

    }


?>