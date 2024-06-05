<?php 

require_once("includes/header.php");
require_once("classes/SearchProvider.php");

if(!isset($_GET["term"]) || $_GET["term"] == ""){
    echo "Vous devez entrer un terme de recherche";
    exit();
}

$term = $_GET["term"];

if(!isset($_GET["orderBy"]) || $_GET["orderBy"] == "views"){
    
    $orderBy = "views";
}else{
    $orderBy = "UploadDate";
}

$searchResults = new SearchProvider($con,$user);
$videos = $searchResults->getVideos($term,$orderBy);

$videoGrid = new VideoGrid($con,$user);
?>

<div class="largeContainer">
    <?php 
        
        if(sizeof($videos) > 0){

            echo $videoGrid->createLarge($videos,sizeof($videos),"vidéo(s) trouvée(s)", true);

        }else{
            echo "Aucun Résultat";
        }

    ?>
</div>


<?php require_once("includes/header.php") ?>