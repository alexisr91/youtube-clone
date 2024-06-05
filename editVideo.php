<?php 
    require_once("includes/header.php");
    require_once("classes/VideoPlayer.php");
    require_once("classes/VideoFormProvider.php");
    require_once("classes/VideoUploadData.php");
    require_once("classes/SelectThumbnail.php");

    if(!User::isLoggedIn()){

        header("Location:signIn.php");
    }

    if(!isset($_GET["videoId"])){

        echo "Vous devez sélectionner une vidéo";
        exit();
    }

    $video = new Video($con,$_GET["videoId"],$user);
    if($video->getUploadedby() != $user->getUsername()){ // Condition interdisant à un autre utilisateur de modifier une vidéo qu'il n'a pas uploadé lui-même

        echo "Vous ne pouvez pas modifier une vidéo qui ne vous appartient pas !";
        exit();
    }
    
    $detailsMsg="";
    // soumission du formulaire 
    
    if(isset($_POST["saveDetailsBtn"])){

        $videoData = new VideoUploadData(
                    null,
                    $_POST["titleInput"],
                    $_POST["descriptionInput"],
                    $_POST["privacyInput"],
                    $_POST["categoriesInput"],
                    $user->getUsername()

        );

    if($videoData->updateDetails($con,$video->getId())){

        //true
        $detailsMsg="<div class='alert alert-success'>
                        <strong>Les détails de la vidéo ont été mis à jour avec succès</strong>
                    </div>";
        $video = new Video($con,$_GET["videoId"],$user);

    }else{
        //false

        $detailsMsg="<div class='alert alert-warning'>
                        <strong>Erreur !</strong>
                    </div>";
    }    
}
    ?>
    <script src="assets/js/editVideoAction.js"></script>
    <div class="editVideoContainer column">

        <div class="topSection">
            <?php 
                // Affichage de la vidéo 
                    $videoPlayer = new VideoPlayer($video);
                    echo $videoPlayer->create(false);

                // Affichage des vignettes

                    $selectedThumbnail = new SelectThumbnail($con,$video);

                    echo $selectedThumbnail->create();

            ?>

        </div>

        <div class="bottomSection">
            <?php 
                $FormProvider = new VideoFormProvider($con);
                echo $FormProvider->createEditDetailsForm($video);

            ?>

        </div>
    </div>


<?php require_once("includes/footer.php");?>