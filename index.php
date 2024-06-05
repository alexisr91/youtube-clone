<?php  require_once("includes/header.php"); ?>

<div class="home">

<?php
 
 
 
 $videoGrid = new videoGrid($con,$user);
 
 if(User::isLoggedIn()){ // Si tu as souscris à une vidéo ou plus tu affiches le bloc d'instruction ou sinon tu l'affiches

   // sizeof = on teste si le tableau est vide ou non, tester la longueur de ton tableau 
      $subscriptionProvider = new SubscriptionProvider($con,$user);
      $subscriptionVideos =  $subscriptionProvider-> getVideos();
  if(sizeof($subscriptionVideos) > 0 ){ 

      echo $videoGrid->create($subscriptionVideos,"Vos abonnements",false);
      }
   }

   echo $videoGrid->create(null,"Vos recommandations",false);

   ?>

</div>

<?php  require_once("includes/footer.php")?>