<?php 

require "Models/ProjectsModel.php";

class ProjectsController{

    private $projectModel ;

    function __construct(){
        $this->projectModel = new ProjectsModel();
    }

    public function request($method , $paramiterKeyValue){
        $this->$method($paramiterKeyValue);
    }

    public function get($paramiterKeyValue){

        if($paramiterKeyValue[0] == "workerId" && isset($paramiterKeyValue[1])){
            $result = $this->projectModel->getByWorkerId($paramiterKeyValue[1]);

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
        else if($paramiterKeyValue[0] == "id" && isset($paramiterKeyValue[1])){
            $result = $this->projectModel->getById($paramiterKeyValue[1]);

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
        else if($paramiterKeyValue[0] == "images" && isset($paramiterKeyValue[1])){
            echo json_encode($paramiterKeyValue);
        }
        else{
            echo '{"status" : "wrong URI Format"}';   
        }

    }

    public function delete($paramiterKeyValue){
        
    }

}

?>