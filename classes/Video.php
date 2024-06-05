<?php

class Video{

    private $con,$sqlData,$currentUser;

    public function __construct($con,$input,$currentUser){

        $this->con = $con;
        $this->currentUser = $currentUser;
        $this->sqlData = $input; 

        if(is_array($input)){

            // ce n'est pas un ID
            $this->sqlData=$input;
        }else{

            $query = $this->con->prepare("SELECT * FROM videos WHERE id=:id"); // Ne pas mettre d'espace en SQL
            $query->bindParam(":id",$input); // C'est la même chose que Bind
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function getUsername(){

        return $this->sqlData["username"];
    }

    public function getId(){

        return $this->sqlData["id"];
    }

    public function getUploadedBy(){

        return $this->sqlData["uploadedBy"];
    }

    public function getTitle(){

        return $this->sqlData["title"];
    }

    public function getDescription(){

        return $this->sqlData["description"];
    }

    public function getPrivacy(){

        return $this->sqlData["privacy"];
    }

    public function getFilePath(){

        return $this->sqlData["filePath"];
    }

    public function getCategory(){

        return $this->sqlData["category"];
    }

    public function getUploadedDate(){

        $date = $this->sqlData["uploadDate"];

        return date("j M Y",strtotime($date)); // j à la place de d enleve le 0 devant la date
    }

    public function getViews(){
        

        return $this->sqlData["views"];
    }

    public function getDuration(){

        return $this->sqlData["duration"];
    }

    public function getThumbnail(){

        $query = $this->con->prepare("SELECT filePath FROM thumbnails WHERE videoId=:videoId AND selected=1");
        $query->bindParam(":videoId",$videoId);
        $videoId = $this->getId();
        $query->execute();

        return $query->fetchColumn();
    }

    public function incrementViews(){

        $query = $this->con->prepare("UPDATE videos SET views=views+1 WHERE id=:id");

        $query->bindParam(":id",$videoId);
        $videoId = $this->getId();
        $query->execute();

        // on met à jour getViews()

        $this->sqlData["views"] = $this->sqlData["views"] + 1;
    }

    public function getLikes(){

        $query = $this->con->prepare("SELECT count(*) as 'count' FROM likes WHERE videoId=:videoId");
        $query->bindParam(":videoId",$videoId);
        $videoId = $this->getID();
        $query->execute();

        $data = $query->fetch(PDO::FETCH_ASSOC); // Fetch est sous forme de tableau associatif

        return $data["count"];
        return ;
    }

    public function getDislikes(){

        $query = $this->con->prepare("SELECT count(*) as 'count' FROM dislikes WHERE videoId=:videoId");
        $query->bindParam(":videoId",$videoId);
        $videoId = $this->getID();
        $query->execute();

        $data = $query->fetch(PDO::FETCH_ASSOC); // Fetch est sous forme de tableau associatif

        return $data["count"];
        return ;
    }

    public function like(){ // Query des likes et des dislikes
        
        $id = $this->getId();
        $username = $this->currentUser->getUsername();
        
        
        
        if($this->alreadyLiked()){
            
            // 1- Déjà liké 
            $query = $this->con->prepare("DELETE FROM likes WHERE username=:username AND videoId=:videoId");
            $query->bindParam(":username",$username);
            $query->bindParam(":videoId",$id);
            $query->execute();

            $result = array(
                "likes" =>-1,
                "dislikes" => 0
            );

            return json_encode($result);

        }else{

            // 3- suppression du dislike

            $query = $this->con->prepare("DELETE FROM dislikes WHERE username=:username AND videoId=:videoId");
            $query->bindParam(":username",$username);
            $query->bindParam(":videoId",$id);
            $query->execute();
            $count = $query->rowCount();

            // 2- pas encore liké, on insert dans la bdd

            $query = $this->con->prepare("INSERT INTO likes(username,videoId) VALUES(:username,:videoId)");

            $query->bindParam(":username",$username);
            $query->bindParam(":videoId",$id);
            $query->execute();

            $result = array(
                "likes" =>1,
                "dislikes" => 0- $count
            );

            return json_encode($result);
        }
    }

    public function dislike(){ // Query des likes et des dislikes
        
        $id = $this->getId();
        $username = $this->currentUser->getUsername();
        
        
        
        if($this->alreadydisliked()){
            
            // 1- Déjà disliké 
            $query = $this->con->prepare("DELETE FROM dislikes WHERE username=:username AND videoId=:videoId");
            $query->bindParam(":username",$username);
            $query->bindParam(":videoId",$id);
            $query->execute();

            $result = array(
                "likes" =>0,
                "dislikes" => -1
            );

            return json_encode($result);

        }else{

            // 3- suppression du like

            $query = $this->con->prepare("DELETE FROM likes WHERE username=:username AND videoId=:videoId");
            $query->bindParam(":username",$username);
            $query->bindParam(":videoId",$id);
            $query->execute();
            $count = $query->rowCount();

            // 2- pas encore liké, on insert dans la bdd

            $query = $this->con->prepare("INSERT INTO dislikes(username,videoId) VALUES(:username,:videoId)");

            $query->bindParam(":username",$username);
            $query->bindParam(":videoId",$id);
            $query->execute();

            $result = array(
                "likes" =>0 - $count,
                "dislikes" => 1
            );

            return json_encode($result);
        }
    }



    public function alreadyLiked(){
        $query = $this->con->prepare("SELECT * FROM likes WHERE username=:username AND videoId=:videoId");
        
        $query->bindParam(":username",$username);
        $query->bindParam(":videoId",$id);
        
        $id = $this->getId();
        $username = $this->currentUser->getUsername();
        
        $query->execute();
        return $query->rowCount() > 0;
    }

    public function alreadyDisliked(){
        $query = $this->con->prepare("SELECT * FROM dislikes WHERE username=:username AND videoId=:videoId");
        
        $query->bindParam(":username",$username);
        $query->bindParam(":videoId",$id);
        
        $id = $this->getId();
        $username = $this->currentUser->getUsername();
        
        $query->execute();
        return $query->rowCount() > 0;
    }

}


?>