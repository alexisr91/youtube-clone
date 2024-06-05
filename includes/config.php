<?php 


    ob_start(); 
    date_default_timezone_set("Europe/Paris");
    // on essaie de se connecter à la BDD

   session_start();
    
    try{
        $con = new PDO("mysql:dbname=youtube;host=localhost",
                        "root",
                        "root");

// Le Mot de passe par défaut de MAMP est root !!! 
// une Exception est un objet lancé par le code  et que l'on peux rattraper ( catch )
        

        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); 
        
    }

    // Si on arrive pas à se connecter on attrape les erreurs retournées
    catch(PDOException $e){
        echo "La connexion a échoué : " . $e->getMessage();
    }


?>