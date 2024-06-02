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

        if(isset($paramiterKeyValue["workerId"])){
            $result = $this->projectModel->getByWorkerId($paramiterKeyValue["workerId"]);

            if($result != null){
                echo json_encode($result);
            }
            else if($result == 0){
                echo '{"status" : "not found"}';
                // header("HTTP/1.1 404");
            }
            else{
                echo '{"status" : "connection error"}';
                header("HTTP/1.1 500");
            }
        }
        else if(isset($paramiterKeyValue["id"]) && isset($paramiterKeyValue["images"])){
            $result = $this->projectModel->getProjectImages($paramiterKeyValue["id"] , $paramiterKeyValue["images"] );
            if($result != null){
                echo json_encode($result);
            }
            else if($result == 0){
                echo '{"status" : "not_found"}';
                // header("HTTP/1.1 404");
            }
            else{
                echo '{"status" : "connection error"}';
                header("HTTP/1.1 500");
            }

        }
        else if(isset($paramiterKeyValue["id"])){
            $result = $this->projectModel->getById($paramiterKeyValue["id"]);
                
            if($result != null){
                echo json_encode($result);
            }
            else if($result == 0){
                echo '{"status" : "not found"}';
                // header("HTTP/1.1 404");
            }
            else{
                echo '{"status" : "connection error"}';
                header("HTTP/1.1 500");
            }

        }
        else{
            echo '{"status" : "wrong URI Format"}';   
        }

    }

    public function delete($paramiterKeyValue){
        
    }

}

?>