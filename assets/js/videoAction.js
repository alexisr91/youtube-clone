function likeVideo(button,videoId){
    console.log(videoId);
    // Nous permet d'envoyer les données via la méthode post
    $.post("ajax/likeVideo.php",{videoId:videoId})

    // quand l'appel à Ajax est terminé, on fait...
    .done(function(data){

        var likeBtn = $(button);
        var dislikeBtn = $(button).siblings(".dislikeBtn"); // Methode JQuery qui permet d'avoir les freres/soeurs dans les elements correspondants

        likeBtn.addClass("active");
        dislikeBtn.removeClass("active");

        var result = JSON.parse(data);
        updateLikesValue(likeBtn.find(".text"),result.likes); // wtf ? 
        updateLikesValue(dislikeBtn.find(".text"),result.dislikes);

        // modification du bouton en foncton du résultat

        if(result.likes < 0){

            // on enleve la classe active

            likeBtn/removeClass("active");
            // on change l'image
            likeBtn.find("img:first").attr("src","assets/images/icons/thumb_up.png");

        }else{
            likeBtn.find("img:first").attr("src","assets/images/icons/thumb-up-active.png");
        }
        
        dislikeBtn.find("img:first").attr("src","assets/images/icons/thumb_down.png");
    });
}

function dislikeVideo(button,videoId){

    // Nous permet d'envoyer les données via la méthode post
    $.post("ajax/dislikeVideo.php",{videoId:videoId})

    // quand l'appel à Ajax est terminé, on fait...
    .done(function(data){

        var dislikeBtn = $(button);
        var likeBtn = $(button).siblings(".likeBtn"); // Methode JQuery qui permet d'avoir les freres/soeurs dans les elements correspondants

        dislikeBtn.addClass("active");
        likeBtn.removeClass("active");

        var result = JSON.parse(data);
        updateLikesValue(likeBtn.find(".text"),result.likes); // wtf ? 
        updateLikesValue(dislikeBtn.find(".text"),result.dislikes);

        // modification du bouton en foncton du résultat

        if(result.dislikes < 0){

            // on enleve la classe active

            dislikeBtn.removeClass("active");
            // on change l'image
            dislikeBtn.find("img:first").attr("src","assets/images/icons/thumb_down.png");

        }else{
            dislikeBtn.find("img:first").attr("src","assets/images/icons/thumb-down-active.png");
        }

        likeBtn.find("img:first").attr("src","assets/images/icons/thumb_up.png");
    });
}

function updateLikesValue(element,number){

    var likesCountValue = element.text() || 0;
    element.text(parseInt(likesCountValue) + parseInt(number));
}
