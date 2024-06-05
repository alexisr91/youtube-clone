<?php 

    require_once('classes/VideoDetailsControls.php');

class VideoDetailsSection{

    private $con,$video,$user;


    public function __construct($con,$video,$user){

        $this->con = $con;
        $this->video = $video;
        $this->user = $user;
    }

    // methode create = 1er + 2eme zone

    public function create(){
       
        return $this->createFirstZone() . $this->createSecondZone();
    }
    
    // 1er zone
    
    private function createFirstZone(){
        //titre + nombre de vues
        
        $title = $this->video->getTitle(); 
        $views = ($this->video->getViews() >1) ? $this->video->getViews(). " vues" : $this->video->getViews(). " vue";
        $videoDetailsControls = new VideoDetailsControls($this->video,$this->user);
        $controls = $videoDetailsControls->create();


        return "<div class='videoInfos'>

                <h1>$title</h1>
                <div class='bottomSection'>
                    <span class='viewCount'>$views</span>
                        $controls
                </div>
                </div>
                ";
    }

    // 2eme zone

    private function createSecondZone(){
        
        // description, date, pseudo, lien vers profil

        $description = $this->video->getDescription();
        $uploadDate = $this->video->getUploadedDate();
        $uploadedBy = $this->video->getUploadedBy();
        $profileBtn = BtnProvider::createProfileBtn($this->con,$uploadedBy);

        // 1er cas : l'utilisateur = auteur de la video => btn éditer
        if($uploadedBy == $this->user->getUsername()){

            $actionBtn = BtnProvider::createEditBtn($this->video->getId());
        }else{
        // 2eme cas : l'utilisation est différent de l'auteur => Btn d'abonner
        
        $userTo = new User($this->con,$uploadedBy);

        $actionBtn= BtnProvider::createSubscribeBtn($this->con,$userTo,$this->user);

        }
        return "<div class='secondarySection'>

                    <div class='topContent'>
                        $profileBtn

                        <div class='uploadInfos'>
                            <span class='author'>
                                <a href='profile.php?username=$uploadedBy'>$uploadedBy</a>
                            </span>
                            <span class='date'>Publiée le $uploadDate</span>
                        </div>

                        $actionBtn
                    </div>
                    <div class='description'>$description</div>
                </div>";

                
    }
}

?>