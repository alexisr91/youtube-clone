function subscribe(userTo,userFrom,button){

    
    if(userTo == userFrom){ // == represente une égalité de valeur 
        alert("Vous ne pouvez pas vous abonner à vos propres vidéos");
        
        return;
        // userTo = C'est celui qui upload la video 
        // userFrom = C'est celui qui s'abonne
    }


// Appel Ajax
    $.post("ajax/subscribe.php",{userTo:userTo, userFrom:userFrom})
    .done(function(data){
        
        if(data != null){
            $(button).toggleClass("subscribe unsubscribe");
            var textBtn = $(button).hasClass("subscribe") ? "S'abonner" : " Abonné";

            $(button).text(textBtn + " " + data);
        }
    });
// LA FONCTION NE MARCHE PAS SUR FIREFOX
}