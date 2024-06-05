<?php

    require_once("includes/header.php");
    require_once("classes/VideoFormProvider.php");


?>

<div class="column">

<?php
    $formProvider = new VideoFormProvider($con); // Methode construct qui est fait par défaut, que l'on ne voit pas, dispo sur pratiquemmenbt tous les langages
   
    echo $formProvider->createUploadForm();
    

   
?>
</div>

<div class="modal fade" id="loadingModal" role="dialog" tabindex="-1" aria-labelledby="loadingModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body d-flex flex-column align-items-center">
        <p>Merci de patienter pendant le traitement de la vidéo .</p>
        <img src="assets/images/icons/loading-spinner.gif" alt="chargement">
      </div>
      
    </div>
  </div>
</div>


<?php require_once("includes/footer.php"); ?>