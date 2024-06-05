<?php 

    require_once("includes/header.php");
    require_once("classes/ProfileProvider.php");

    if(isset($_GET["username"])){
        $profileUsername = $_GET["username"];
    }else{
        echo "Impossible à afficher";
        exit();
    }


    $profileProvider = new ProfileProvider($con,$user,$profileUsername);

    echo $profileProvider->create();

 ?>
 
 <?php require_once("includes/footer.php"); ?>