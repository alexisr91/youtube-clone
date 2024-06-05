<?php 

    require_once("classes/ProfileData.php");

    class ProfileProvider{

        private $con,$currentUser,$profileData;


        public function __construct($con,$currentUser,$profileUsername){

            $this->con= $con;
            $this->currentUser = $currentUser;
            $this->profileData = new ProfileData($con,$profileUsername);
        }

        public function create(){

            $profileUsername = $this->profileData->getProfileUsername();

            if(!$this->profileData->userExists()){

                return "L'utilisateur n'existe pas dans notre base de données";
            }

            // 4 sections

            $coverPhotoSection = $this->createCoverPhotoSection();
            $headerSection = $this->createHeaderSection();
            $tabsSection = $this->createTabsSection();
            $contentSection = $this->createContentSection();

            return "<div class='profileContainer'>

                        $coverPhotoSection
                        $headerSection
                        $tabsSection
                        $contentSection
                    </div>";
        }

        public function createCoverPhotoSection(){

            $coverImg = $this->profileData->getCoverPhoto();
            $name = $this->profileData->getProfileFullname();

            return "<div class='coverImgContainer' style='background:url($coverImg) #fff no-repeat scroll 0 0; background-size:cover'>

                        <span class='profileName'>$name</span>
                    </div>";
        }

        public function createHeaderSection(){
            
            // avatar - nom - nombre d'abonnés
            $profileAvatar = $this->profileData->getProfileAvatar();
            $name = $this->profileData->getprofileFullname();
            $subscriptionCount = $this->profileData->getProfileSubscriptionsCount();
            $btn = $this->createHeaderBtn();
            
            return "<div class='profileHeader'>
                        <div class='userInfosContainer'>
                            <img src='$profileAvatar' class='profileImg'>
                            <div class='userInfos'>
                            <span class='title'>$name</span>
                            <span class='subscriberCount'>$subscriptionCount abonné(s)</span>
                            </div>
                        </div>

                        <div class='btnContainer'>
                            <div class='btnItem'>$btn</div>
                        </div>
                    </div>";
        }
        public function createTabsSection(){
           /*  Problème Bootstrap tabs = id='nav-about' (ligne 89 ) sera relié à data-bs-target='#nav-about' ( ligne 80) et également les liens sont obsolètes sur Bootstrap, il faut utiliser des boutons  */
            
                return "<div class='profileTabs'>
                            <div class='nav nav-tabs' id='nav-tab' role='tablist'>
                                <button class='nav-link active' id='nav-videos-tab' data-bs-toggle='tab' data-bs-target='#nav-videos' type='button' role='tab' aria-controls='nav-videos' aria-selected='true'>Vidéos</button>
                                <button class='nav-link' id='nav-about-tab' data-bs-toggle='tab' data-bs-target='#nav-about' type='button' role='tab' aria-controls='nav-about' aria-selected='false'>À propos</button>
                            </div>
                        </div>";
        }


        public function createContentSection(){

                $videos = $this->profileData->getUserVideo();

                if(sizeof($videos) > 0){
                    
                    $videoGrid = new VideoGrid($this->con,$this->currentUser);
                    $videoGridHtml = $videoGrid->create($videos,null,false);

                }else{

                    $videoGridHtml = "<span class='alert alert-warning'> Cet utilisateur n'a encore aucune vidéo publiée</span>";
                }

                $aboutSection = $this->createAboutSection();
                return "<div class='tab-content' id='nav-tabContent'>
                            <div class='tab-pane fade active show' id='nav-videos' role='tabpanel' aria-labelledby='nav-videos-tab'>$videoGridHtml</div>
                            <div class='tab-pane fade' id='nav-about' role='tabpanel' aria-labelledby='nav-about-tab'>$aboutSection</div>
                        </div>";
            }

        private function createHeaderBtn(){

            if($this->currentUser->getUsername() == $this->profileData->getProfileUsername()){
                
                return "";

            }else{
                
                return BtnProvider::createSubscribeBtn($this->con,$this->profileData->getProfileUser(),$this->currentUser);
            }
        }


        private function createAboutSection(){

            $html ="<div class='aboutContainer'>
                        <div class='title'><span>Détails</span></div>
                        <div class='values'>";

            $details = $this->profileData->getAllUserDetails();
            
            // boucle
            foreach($details as $key=>$value){

                $html .="<span>$key: $value</span>";
            }
            $html .="</div></div>";  // Heu... Pourquoi ?       

            return $html;
        }
    }

?>