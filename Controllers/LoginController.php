<?php 
    require "./Models/AuhtenticationModel.php";

    class LoginController{
        private $authenticationModel ;

        function __construct(){
            $this->authenticationModel = new AuhtenticationModel();
        }
    
    }
?>