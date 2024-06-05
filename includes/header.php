<?php 
    require_once("includes/config.php"); 
    require_once("classes/User.php"); 
    require_once("classes/Video.php");
    require_once("classes/VideoGrid.php"); 
    require_once("classes/VideoGridItem.php");  
    require_once("classes/SubscriptionProvider.php");
    require_once("classes/NavProvider.php");
    require_once("classes/BtnProvider.php");     

    
    //$usernameLoggedIn = isset($_SESSION["userLoggedIn"]) ? $_SESSION["userLoggedIn"] : "";
    $usernameLoggedIn = User::isLoggedIn() ? $_SESSION["userLoggedIn"] : "";
    $user = new User($con,$usernameLoggedIn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Youtube</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
   <link rel="stylesheet" href="assets/css/style.css">
   <link rel="stylesheet" href="assets/css/responsive.css">
   <script src="assets/js/subscribe.js"></script>

</head>
<body>
       
    <div id="pageContainer">
        <header> 
            <button class="navShowHide">
                <img src="assets/images/icons/menu.png" alt="menu">           
            </button>

            <a href="index.php" class="logoContainer">
                <img src="assets/images/logo-youtube.png" alt="Youtube">
            </a>

            <div class="searchbarContainer">

                <form action="search.php">
                    <input type="text" name="term" placeholder="Search" class="searchbar">
                    <button class="searchBtn">
                        <img src="assets/images/icons/search.png" alt="Recherche">
                    </button>
                </form>
            </div>

            <div class="rightIcons">
                <a href="upload.php">
                    <img src="assets/images/icons/upload.png" alt="">
                </a>

                <a href="profile.php">
                    <img src="assets/images/default.png" alt="">
                </a>
            </div>
        </header>
        
        <aside id="sidenavContainer">
            
            <?php 
                $navProvider = new NavProvider($con,$user);
                echo $navProvider->create();

            ?>


        </aside>

        <section id="mainSectionContainer" class="leftPadding">

            <div id="mainContent" class="container-fluid">

    
