<?php 



class VideoDetailsControls{


    private $video,$user; // variable de classe

    public function __construct($video,$user){
        $this->video = $video; // la valeur de ce que j'ai en param de mon constructeur va etre affecté à l'attribut. L
        // l'affectation ci-dessus/dessous sont les parametres du constructeur et non des variables de classe.
        // video L11 = L32 ce sont les memes
        $this->user = $user;

    }

    public function create(){

        // création de 2 boutons ( Like/Dislike )

        $likeBtn = $this->createLikeBtn();
        $dislikeBtn = $this->createDislikeBtn();
        return "<div class='controls'>
                $likeBtn
                $dislikeBtn
                </div>";
       }
    
    private function createLikeBtn(){
        $videoId = $this->video->getId(); // $this-> permet de se mettre au niveau de l'objet, il permet d'appeler soit des méthodes ou des parametres ou des attributs. Tous les elements au niveau de l'objet 
        $text= $this->video->getLikes();
        $imgSrc= "assets/images/icons/thumb_up.png";
        $action="likeVideo(this,$videoId)";
        $class="likeBtn";

        if($this->video->alreadyLiked()){

            $imgSrc = "assets/images/icons/thumb-up-active.png";
        }
        return BtnProvider::createBtn($text,$imgSrc,$action,$class);
    }

    private function createDislikeBtn(){

        $videoId = $this->video->getId();
        $text= $this->video->getDislikes();
        $imgSrc= "assets/images/icons/thumb_down.png";
        $action="dislikeVideo(this,$videoId)";
        $class="dislikeBtn";

        if($this->video->alreadyDisliked()){

            $imgSrc = "assets/images/icons/thumb-down-active.png";
        }
        return BtnProvider::createBtn($text,$imgSrc,$action,$class);
    }

    //LORS de l'instanciation, ça va d'abord créer les attributs de l'objet et cree les methodes puis executer la fonction construct, il n'execute pas les autres méthodes 
}

?>