<?php 


    class SettingsFormprovider{

        public function createUserDetailsForm($firstname,$lastname,$email){

            $firstnameInput = $this->createFirstnameInput($firstname);
            $lastnameInput = $this->createLastnameInput($lastname);
            $emailInput = $this->createEmailInput($email);
            $saveBtn = $this->createSaveUserBtn(null);

            return "<form action='settings.php' method='POST'>
                    
                        $firstnameInput
                        $lastnameInput
                        $emailInput
                        $saveBtn
                    </form>";
        }

        public function createPasswordForm(){

            $oldPassword = $this->createPasswordInput("oldPassword","Ancien mot de passe");
            $newPassword = $this->createPasswordInput("newPassword","Nouveau mot de passe");
            $confirmPassword = $this->createPasswordInput("confirmPassword","Confirmez votre nouveau mot de passe");
            $savePasswordBtn = $this->createSavePasswordBtn();

            return "<form action='settings.php' method='POST'>
                        <span>Mise à jour du mot de passe</span>
                        $oldPassword
                        $newPassword
                        $confirmPassword
                        $savePasswordBtn
                    </form>";
        }

        private function createPasswordInput($name,$placeholder){

            return "<div class='form-group'>
                        <input type='password' class='form-control mb-4' placeholder='$placeholder' name='$name' required>
                        </div>";

        }

        private function createSavePasswordBtn(){

            return "<button type='submit' class='btn btn-primary mb-4' name='savePasswordBtn' id='updatePassword'>Sauvegarder</button>";
        }

        private function createFirstnameInput($value){

            if($value == null) $value="";
            return "<div class='form-group'>
                        <input text='text' class='form-control mb-4' placeholder='Nom' name='firstname' value='$value' required>
                    </div>";
        }

        private function createLastnameInput($value){

            if($value == null) $value="";
            return "<div class='form-group'>
                        <input text='text' class='form-control mb-4' placeholder='Prénom' name='lastname' value='$value' required>
                    </div>";
        }

        private function createEmailInput($value){

            if($value == null) $value="";
            return "<div class='form-group'>
                        <input text='email' class='form-control mb-4' placeholder='Email' name='email' value='$value' required>
                    </div>";
        }

        private function createSaveUserBtn(){

            return "<button type='submit' class='btn btn-primary mb-4' name='saveDetailsBtn' id='updateBtn'>Sauvegarder</button>";
        }
    }


?>