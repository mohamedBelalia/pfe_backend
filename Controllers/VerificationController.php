<?php 

require "./Models/VerificationModel.php";

class VerificationController {
    private $verificationModel ;

    function __construct(){
        $this->verificationModel = new VerificationModel();
    }

    public function request($method , $paramiterKeyValue){
        $this->$method($paramiterKeyValue);
    }

    public function get($paramiterKeyValue){
        if($paramiterKeyValue != null){
            if(isset($paramiterKeyValue["workerId"])){

                $verfiedInfo = '{' ;

                $returnedResult = $this->verificationModel->complatedFields($paramiterKeyValue["workerId"]);
                while($info = $returnedResult->fetch_assoc()){
                    if($info["imgProfile"] == "defaultUserImage.png"){
                        $verfiedInfo .= '"imgProfile" : "default",';
                    }
                    if($info["description_ouvrier"] == null){
                        $verfiedInfo .= '"description_ouvrier" : "null",';
                    }
                    if($info["experience"] == null){
                        $verfiedInfo .= '"experience" : "null"}';
                    }
                    echo $verfiedInfo;
                }
            }
        }
    }
}

?>