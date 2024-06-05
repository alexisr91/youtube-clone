<?php 

    class formSanitizer{

        public static function sanitizeFormString($inputText){

            $inputText = strip_tags($inputText); // Methode qui Supprime les balises HTML
            $inputText = str_replace("","",$inputText); // Methode qui  Remplace les espaces 
            $inputText = strtolower($inputText); // Met en minuscule
            $inputText = ucfirst($inputText);
            return $inputText;
        }

        
        public static function sanitizeFormUsername($inputText){

            $inputText = strip_tags($inputText); // Methode qui Supprime les balises HTML
            $inputText = str_replace("","",$inputText); // Methode qui  Remplace les espaces 
            return $inputText;
        }

        public static function sanitizeFormPassword($inputText){

            $inputText = strip_tags($inputText); // Methode qui Supprime les balises HTML
            return $inputText;
        }


        public static function sanitizeFormEmail($inputText){

            $inputText = strip_tags($inputText); // Methode qui Supprime les balises HTML
            $inputText = str_replace("","",$inputText); // Methode qui  Remplace les espaces 
            return $inputText;
        }
    }

?>