<?php 

require "Models/ProjectsModel.php";
require "./config/CompressImg.php";

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

   
    public function post($unUsedValue){
     
        $title = isset($_POST['title']) ? $_POST['title'] : '';
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $images = array();
    
        if (isset($_FILES['images'])) {
            $fileCount = count($_FILES['images']['name']);
            for ($i = 0; $i < $fileCount; $i++) {
             
                $uniqueFilename = uniqid() . '_' . $_FILES["images"]["name"][$i];
                
                $target_dir = "uploads/Projects/";
                $target_file = $target_dir . basename($uniqueFilename);
    
                if (move_uploaded_file($_FILES["images"]["tmp_name"][$i], $target_file)) {
                    $imgPath = CompressImg::convertToWebP($target_file);
                    array_push($images , $imgPath);
                } else {
                    echo "Sorry, there was an error uploading your file.<br>";
                }
            }
        } else {
            echo "No images uploaded.";
        }

        if($this->projectModel->addProject("3" , $title , $description , $images)){
            echo '{"status" : "done"}';
        }
        else{
            echo '{"status" : "something_wrong"}';
        }
    }

    public function put($passedParameter){
        
        $title = isset($_POST['title']) ? $_POST['title'] : '';
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $images = array();
    
        if (isset($_FILES['images'])) {
            $fileCount = count($_FILES['images']['name']);
            for ($i = 0; $i < $fileCount; $i++) {
             
                $uniqueFilename = uniqid() . '_' . $_FILES["images"]["name"][$i];
                
                $target_dir = "uploads/Projects/";
                $target_file = $target_dir . basename($uniqueFilename);
    
                if (move_uploaded_file($_FILES["images"]["tmp_name"][$i], $target_file)) {
                    $imgPath = CompressImg::convertToWebP($target_file);
                    array_push($images , $imgPath);
                } else {
                    echo "Sorry, there was an error uploading your file.<br>";
                }
            }
        } else {
            echo "No images uploaded.";
        }

        if($this->projectModel->updateProject($passedParameter["id"] , $title , $description , $images)){
            echo '{"status" : "done"}';
        }
        else{
            echo '{"status" : "something_wrong"}';
        }
    }

    public function delete($paramiterKeyValue){
        
    }

}

?>