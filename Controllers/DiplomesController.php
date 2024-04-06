<?php 

require "Models/DiplomesModel.php";

class DiplomesController{

    private $diplomModel ;

    function __construct(){
        $this->diplomModel = new DiplomesModel();
    }

    public function request($method , $paramiterKeyValue){
        $this->$method($paramiterKeyValue);
    }

    public function get($paramiterKeyValue){

        // var_dump($paramiterKeyValue);

    
        if($paramiterKeyValue == null){
            $result = $this->diplomModel->getAll();

            if($result != null){
                echo json_encode($result);
            }
            else{
                echo '{"status" : "connection error"}';
            }
        }
        else if(isset($paramiterKeyValue["workerId"])){
            $result = $this->diplomModel->getByWorkerId($paramiterKeyValue["workerId"]);

            if($result != null){
                echo json_encode($result);
            }
            else if($result == 0){
                echo '{"status" : "not found"}';
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