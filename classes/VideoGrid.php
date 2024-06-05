<?php

class VideoGrid{

    private $con,$currentUser;
    private $gridClass= "videoGrid";
    private $largeMode = false;

    public function __construct($con,$currentUser){

        $this->con = $con;
        $this->currentUser=$currentUser;
    }

    public function create($videos,$title,$filter){
        // vérifier si il y a des vidéos sinon affichage aléatoire de vidéos
        
        if($videos == null){
        // affichage de vidéo aléatoire
        $gridItems = $this->generateItems();    


        }else{

         $gridItems = $this->generateItemsFromVideos($videos);   
        }
        // header spécifique
        
        $header = "";

        if($title != null){
            $header = $this->createGridHeader($title,$filter);
        }
        return "$header
                <div class='$this->gridClass'>$gridItems</div>";
    }
    
    private function generateItems(){
        $query = $this->con->prepare("SELECT * FROM videos ORDER BY RAND() LIMIT 15");
        $query->execute();

        $html = '';

        while($row = $query->fetch(PDO::FETCH_ASSOC)){

            $video = new Video($this->con,$row,$this->currentUser);
            $item = new VideoGridItem($video,$this->largeMode);
            $html .= $item->create();
        }

        return $html;
    }    
    
    private function generateItemsFromVideos($videos){
        $html = "";

        foreach($videos as $video){
            $item = new VideoGridItem($video,$this->largeMode);
            $html.= $item->create();
        }
        return $html;
    }

    private function createGridHeader($title,$filter){ 
        
        $showFilter = "";
        if($filter){

            $link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            
            $urlArray = parse_url($link);
            $query = $urlArray["query"];
            parse_str($query,$params);

            // supprimer un élèment du tableau concerné (orderBy)
            unset($params["orderBy"]);

            $newQuery = http_build_query($params);
            $newUrl = basename($_SERVER["PHP_SELF"])."?" . $newQuery; //PHP SELF = Nom du fichier en cours d'execution par rapport à la racine web
            
            $showFilter = "<div class='right'>
                                <span>Trier par</span>             
                                <a href='$newUrl&orderBy=uploadDate'>Date d'ajout</a>
                                <a href='$newUrl&orderBy=views'>Les plus populaires</a>
                            </div>";

        }

        // Création du filtre
        return "<div class='gridHeader'>
                    <div class='left'>$title</div>
                    $showFilter
                </div>";
    }

    public function createLarge($videos,$title,$filter){

        // On ajoute la classe large 

        $this->gridClass .="large";
        $this->largeMode = true;

        return $this->create($videos,$title,$filter);
    }

}


?>