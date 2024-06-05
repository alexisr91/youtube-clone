<?php

    class VideoProcessor{

        private $con;
        private $sizeLimit = 5000000;  // 0.5 GB = 500MB = 5000000 bytes
        private $allowedTypes = array("mp4","flv","mkv","vob","ogv","ogg","avi","mov","mpeg","mpg");

        

        public function __construct($con){
            $this->con = $con;
        }

        private $ffmpegPath = "ffmpeglibrary/ffmpeg"; // Macbook
        private $ffprobePath ="ffmpeglibrary/ffprobe";
        // chemin relative = lien à l'intérieur du projet alt+shift+cmd+C
        // chemin absolu = lien à l'exterieur du projet   alt+cmd+C

        
        // Uploader les infos concernant la vidéo 

        public function upload($videoUploadData){

            $targetDir= "uploads/videos/";
            $videoData = $videoUploadData->videoDataArray;
            $tempFilePath = $targetDir . uniqid() . basename($videoData["name"]); // chemin de la vidéo uploadé
            $tempFilePath = str_replace(" ","_", $tempFilePath); // remplace les espaces par un _ dans le CHAR

            
            //echo $tempFilePath;
            
            $isValid = $this->processData($videoData,$tempFilePath);

            if(!$isValid){
                return false;
            }
            //is valid is true, on déplace la vidéo dans le repertoire uploads/videos

            if(move_uploaded_file($videoData["tmp_name"],$tempFilePath)){

                // On définit un chemin final qui sera inséré dans la BDD

                $finalPath = $targetDir.uniqid().".mp4";
                

                if(!$this->insertVideoData($videoUploadData,$finalPath)){
                    
                    echo "L'insertion de la vidéo dans la bdd a échoué";
                    return false;
                }
                
                if(!$this->convertVideoToMp4($tempFilePath,$finalPath)){
                echo "l'upload a échoué";
                return false;
                }

                 // supprimera le fichier temporaire

                  if(!$this->deleteFile($tempFilePath)){
                     echo "La suppression du fichier a échoué ";
                     return false;
                  }

                //  // générer des vignettes 
                 
                  if(!$this->generateThumbnails($finalPath)){
                     echo "Les vignettes n'ont pas pû etre générées";
                     return false;
                  }
            // calcul de la durée de la vidéo
            }

            return true;
        }

        private function processData($videoData,$filePath){

            $videoType = pathinfo($filePath,PATHINFO_EXTENSION);
            // valider la taille
            if(!$this->isValidSize($videoData)){
                echo " <div class='alert alert-danger'>Fichier trop lourd. La taille maximum est de ". 
                $this->sizeLimit . "bytes</div>";
                return false;

            }else if(!$this->isValidType($videoType)){
                echo "<div class='alert alert-danger'>Ce type de fichier n'est pas accepté .</div>";
                return false;

            }else if($this->hasError($videoData)){
                echo " Code erreur : ". $videoData["error"];
                return false;
            }

            return true;
        }    

        private function isValidSize($data){
            return $data["size"] <= $this->sizeLimit;
        }
        
        private function isValidType($type){

            // valider le type
            $lowercase = strtolower($type);
            return in_array($lowercase,$this->allowedTypes);
        }

 
        private function hasError($data){

        // stockage les erreurs dans une méthode
            return $data["error"] != 0;
        }


            
            
           

            // insertion des données dans la bse de donnée

            private function insertVideoData($uploadData,$filePath){
                
                $query = $this->con->prepare("INSERT INTO videos(title,uploadedBy,description,privacy,category,filePath) 
                                            VALUES(:title,:uploadedBy,:description,:privacy,:category,:filePath)");


                // on passe par le param uploadData ( qu'on lancera comme methode ) pour appeller les différents attributs

                $query->bindParam(":title",$uploadData->title);
                $query->bindParam(":uploadedBy",$uploadData->uploadedBy);  
                $query->bindParam(":description",$uploadData->description);  
                $query->bindParam(":privacy",$uploadData->privacy);  
                $query->bindParam(":category",$uploadData->category);  
                $query->bindParam(":filePath",$filePath); 
                return $query->execute();
            }

            // conversion vidéo en mp4

            public function convertVideoToMp4($tempFilePath,$finalPath){
                
                $cmd="$this->ffmpegPath -i $tempFilePath $finalPath 2>&1"; // 2>&1 affiche les erreurs à l'ecran 
                
                $outputLog = array();

                exec($cmd,$outputLog,$returnCode);

                if($returnCode !=0){
                    // commande a échouée
                    foreach($outputLog as $line){
                        echo $line ."<br>";
                    }
                    return false;
                }
                return true;
            }

            private function deleteFile($filePath){

                // supprimer un fichier en php unlink()
                if(!unlink($filePath)){
                    
                    echo "Le fichier n'a pas pu être effacé \n";
                    return false;
                }
                return true;
            }

            private function generateThumbnails($filePath){

                $thumbnailSize = "200x120"; 
                $numThumbnails = 3;
                $pathToThumbnail = "uploads/videos/thumbnails";

                $duration = $this->getVideoDuration($filePath);

                $videoId = $this->con->lastInsertID();
                $this-> updateDuration($duration, $videoId);

                //générer les vignettes
                for($num = 1;$num<=$numThumbnails;$num++){
                //configurer le nom et le chemin des vignettes 

                $imageName=uniqid().".jpg";
                $interval =($duration * 0.8)/$numThumbnails * $num;
                $fullThumbnailPath = "$pathToThumbnail/$videoId-$imageName";

                //generer les vignettes 

                $cmd = "$this->ffmpegPath -i $filePath -ss $interval -s $thumbnailSize -vframes 1 $fullThumbnailPath 2>&1";
                $outputLog=array();
                exec($cmd,$outputLog,$returnCode);

                if($returnCode !=0){

                    //echec
                    foreach($outputLog as $line){
                        echo $line ."<br>";
                    }
                 }

                 $query = $this->con->prepare("INSERT INTO thumbnails(videoId,filePath,selected)
                                               VALUES(:videoId,:filePath,:selected)");
                 $query->bindParam(":videoId",$videoId);
                 $query->bindParam(":filePath",$fullThumbnailPath);
                 $query->bindParam(":selected",$selected);

                 $selected = $num == 1 ? 1 : 0; // Tant qu'on est au dessus de la méthode execute, cela ne générera pas d'erruer
                 $success = $query->execute();

                 if(!$success){
                    echo "Erreur lors de l'insertion de la vignette lors de la BDD \n";
                    return false;
                 }
                }

                return true;
            }
           
            private function getVideoDuration($filePath){

                //executer une commande sans retourner d'erreur ( uniquement l'output)

                return (int) shell_exec("$this->ffprobePath -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 $filePath"); 
            }


            private function updateDuration($duration,$videoId){

                $hours = floor($duration / 36000);

                $mins = floor(($duration - ($hours * 3600)) / 60 );

                // % = modulo permet d'obtenir le reste d'une division
                $secs= floor($duration % 60);

               //  conditions ternaires => () ? :
                $hours = ($hours<1 ) ? "": $hours.":";
                $mins = ($mins<10) ? "0". $mins . ":" : $mins.":";
                $secs= ($secs<10) ? "0".$secs : secs;

                $duration = $hours.$mins.$secs;

                $query = $this->con->prepare("UPDATE videos SET duration=:duration WHERE id=:videoId");
                $query ->bindParam(":duration",$duration);
                $query ->bindParam(":videoId",$videoId);
                $query ->execute();

            }
    }