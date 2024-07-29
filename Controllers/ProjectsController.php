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
                echo '{"status" : "not_found"}';
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


    public function post($passedParameter){

        $title = isset($_POST['title']) ? $_POST['title'] : '';
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $token = isset($_POST['token']) ? $_POST['token'] : '';
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
        }

        // if the project is updating
        if(isset($passedParameter["updateProject"])){
            if($this->projectModel->updateProject($passedParameter["updateProject"] , $title , $description , $images)){
                echo '{"status" : "done"}';
            }
            else{
                echo '{"status" : "something_wrong"}';
            }
        }
        // if the project is adding
        else{
            $insertingResult = $this->projectModel->addProject($token , $title , $description , $images) ;
            if($insertingResult == "inserted"){
                echo '{"status" : "done"}';
            }
            else if($insertingResult == "inserting_failed"){
                echo '{"status" : "inserting_failed"}';
            }
            else if($insertingResult == "not_valid"){
                echo '{"status" : "token_not_valid"}';
            }
            else if($insertingResult == "exception_error"){
                echo '{"status" : "exception_error"}';
            }
            else{
                echo '{"status" : "something_wrong"}';
            }
        }
        
    }

    // public function put($passedParameter){

    //     $title = isset($_POST['title']) ? $_POST['title'] : 'empty_title';
    //     $description = isset($_POST['description']) ? $_POST['description'] : 'empty_description';
    //     $images = array();

    //     $newPassedData = file_get_contents("php://input");
    //     $data = json_decode($newPassedData, true);

    //     if (isset($_FILES['images'])) {
    //         $fileCount = count($_FILES['images']['name']);
    //         for ($i = 0; $i < $fileCount; $i++) {

    //             $uniqueFilename = uniqid() . '_' . $_FILES["images"]["name"][$i];

    //             $target_dir = "uploads/Projects/";
    //             $target_file = $target_dir . basename($uniqueFilename);

    //             if (move_uploaded_file($_FILES["images"]["tmp_name"][$i], $target_file)) {
    //                 $imgPath = CompressImg::convertToWebP($target_file);
    //                 array_push($images , $imgPath);
    //             } else {
    //                 echo "Sorry, there was an error uploading your file";
    //             }
    //         }
    //     }

    //     if($this->projectModel->updateProject($passedParameter["id"] , $title , $description , $images)){
    //       $test = [$title , $description ];
    //         var_dump($newPassedData);
    //     }
    //     else{
    //         echo '{"status" : "something_wrong"}';
    //     }
    // }

    public function delete($paramiterKeyValue){
        $result = $this->projectModel->deleteProject($paramiterKeyValue["idProject"]);
        if($result == "successful"){
            echo '{"status" : "done"}';
        }
        else{
            echo '{"status" : "failed"}';
        }
    }

}

?>
