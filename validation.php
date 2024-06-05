<?php 
    require_once("includes/header.php");
    require_once("classes/videoUploadData.php");
    require_once("classes/videoProcessor.php");

    if(!isset($_POST["uploadBtn"])){
        echo "Aucune information n'a été envoyée";
        exit();
    }

    // 1 - Création d'une classe pour le chargement des données avec une requête SQL
        $videoUploadData = new videoUploadData(
            $_FILES["fileInput"],
            $_POST["titleInput"],
            $_POST["descriptionInput"],
            $_POST["privacyInput"],
            $_POST["categoriesInput"],
            $user->getUsername()
        );
        

        

    // 2 - Charger et vérifier les données

        $videoProcessor = new VideoProcessor($con);

        $isSuccessfull= $videoProcessor->upload($videoUploadData);

    // 3 - vérification que le chargement s'est bien passé 

    if($isSuccessfull){
        echo "<div class='alert alert-success'>Vidéo chargée avec succès</div>";
    }

?>