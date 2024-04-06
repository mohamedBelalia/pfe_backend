<?php 

require "Models/RatesModel.php";

class RatesController{

    private $rateModel ;

    function __construct(){
        $this->rateModel = new RatesModel();
    }

    public function request($method , $paramiterKeyValue){
        $this->$method($paramiterKeyValue);
    }

    public function get($paramiterKeyValue){
        if(isset($paramiterKeyValue["workerId"])){
            $result = $this->rateModel->getByIdWorker($paramiterKeyValue["workerId"]) ;

            if($result != null){
                echo json_encode($result);
            }
            else if($result == 0){
                echo '{"status" : "no_comments"}';
            }
            else{
                echo '{"status" : "connection error"}';
            }
        }
        else{
            echo '{"status" : "wrong URI Format"}';   
        }
    }

}

?>