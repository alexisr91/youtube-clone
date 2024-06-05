<?php

require_once("includes/header.php");
require_once("classes/LikedProvider.php");

$likedProvider = new likedProvider($con,$user);
$videos =  $likedProvider-> getVideos();

$videoGrid = new VideoGrid($con,$user);

?>

<div class="largeGridContainer">
<?php 
    if(sizeof($videos) > 0){

        echo $videoGrid->createLarge($videos,"Vidéo J'aime",false);
    }else{
        echo "Je n'aime aucune vidéo pour le moment !";
    }

?>

</div>

<?php require_once("includes/footer.php"); ?>
