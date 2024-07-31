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
                $isOneEmpty = false ;
                $addedTheLastOne = false ;
                $verfiedInfo = '[' ;

                $returnedResult = $this->verificationModel->complatedFields($paramiterKeyValue["workerId"]);
                while($info = $returnedResult->fetch_assoc()){
                    if($info["imgProfile"] == "defaultUserImage.png"){
                        $verfiedInfo .= '"imgProfile",';
                        $isOneEmpty = true ;
                    }
                    if($info["description_ouvrier"] == null){
                        $verfiedInfo .= '"description_ouvrier",';
                        $isOneEmpty = true ;
                    }
                    if($info["experience"] == null){
                        $verfiedInfo .= '"experience"]';
                        $isOneEmpty = true ;
                        $addedTheLastOne = true ;
                    }

                    if(!$addedTheLastOne){
                        $verfiedInfo .= "]";
                    }

                    if($isOneEmpty){
                        echo $verfiedInfo;
                    }
                    else{
                        echo "[]";
                    }
                }
            }
        }
    }
}

?>
