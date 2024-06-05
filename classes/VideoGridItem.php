<?php 


    class VideoGridItem{

        private $video,$largeMode;

        public function __construct($video,$largeMode){

            $this->video = $video;
            $this->largeMode = $largeMode;
        }

        public function create(){

            //vignette /details / URL 

            $thumbnail = $this->createThumbnail();
            $details =  $this->createDetails();
            $url = "watch.php?id=".$this->video->getId();

            return "<a href='$url'>
                        <div class='gridItem'>
                            $thumbnail
                            $details
                        </div>
                    </a>";
        }

        private function createThumbnail(){

            $thumbnail = $this->video->getThumbnail();
            $duration = $this->video->getDuration();

            return "<div class='thumbnail'>
                        <img src='$thumbnail'>
                        <div class='duration'>
                        <span>$duration</span>
                        </div>
                    </div>";
        }

        private function createDetails(){

            $title = $this->video->getTitle();
            $username = $this->video->getUploadedBy();
            $views = $this->video->getViews();
            $description = $this->createDescription();
            $time = $this->video->getUploadedDate();

            return "<div class='Détails'>
                        <h3 class='title'>$title</h3>
                        <span class='pseudo'>$username</span>
                        <div class='stats'>
                            <span class='viewCount'>$views vue(s)-</span>
                            <span class='time'>$time</span>
                        </div>
                        $description
                    </div>";
        }


        private function createDescription(){ // bloc de CHAR dans ma section aside 
           
            if(!$this->largeMode){

                return "";
            }else{
                $description = $this->video->getDescription();
                $description = (strlen($description) > 300) ? substr($description,0,298) . "..." : $description; // Si la longueur de $description est supérieur à 300 on applique la méthode subtr qui démarre à 0 et va jusqu'à 298 sinon on met $description complète
                
                return "<p class='description'>$description</p>";
            }
        }
    }

?>