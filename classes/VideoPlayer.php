
<?php 

class VideoPlayer{

    private $video;
    public function __construct($video){

        $this->video = $video;
    }

    public function create($autoplay){


        if($autoplay){
            $autoplay = "autoplay";

        }else{
            $autoplay = "";

        }

        $filePath = $this->video->getFilePath();

        return "<video controls $autoplay class='w-100'>
                    <source src='$filePath' type='video/mp4' />
                    Votre navigateur ne prend pas en charge ce format.
                </video>";
    }
}