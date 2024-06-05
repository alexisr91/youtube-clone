<?php 
    require_once("includes/header.php");
    require_once("classes/TrendingProvider.php");

    $trendingProvider = new TrendingProvider($con,$user);
    $videos =  $trendingProvider-> getVideos();

    $videoGrid = new VideoGrid($con,$user);

?>


    <div class="largeGridContainer">
    <?php 
        if(sizeof($videos) > 0){

            echo $videoGrid->createLarge($videos,"Vidéos tendances de la semaine dernière",false);
            
        }else{
            echo "Aucune tendance pour le moment !";
        }

    ?>


<?php require_once("includes/footer.php"); ?>