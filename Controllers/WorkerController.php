<?php

require "./Models/WorkerModel.php";

class WorkerController{

    private $userModel ;

    function __construct(){
        $this->userModel = new WorkerModel();
    }

    public function request($method , $paramiterKeyValue){
            $this->$method($paramiterKeyValue);
    }

    public function get($paramiterKeyValue){
        if($paramiterKeyValue != null){
            if(isset($paramiterKeyValue["id"])){

                $result = $this->userModel->getById($paramiterKeyValue["id"]);

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
            else if(isset($paramiterKeyValue["filter"]) && $paramiterKeyValue["filter"] != null){
                $result = $this->userModel->getByFilter($paramiterKeyValue["filter"]);

                if($result != null){
                    echo json_encode($result);
                }
                else{
                    echo '{"status" : "connection error"}';
                    header("HTTP/1.1 500");
                }
            }
            else if(isset($paramiterKeyValue["top"])){

                if((int)$paramiterKeyValue["top"]){
                    $result = $this->userModel->getTop($paramiterKeyValue["top"]);

                    if($result == null){
                        echo '{"status" : "connection_error"}';
                    }
                    else if($result == 0){
                        echo '{"status" : "not_found"}';
                        // header("HTTP/1.1 404");
                    }
                    else{
                        echo json_encode($result);
                    }
                }
                else{
                    echo '{"status" : "wrong_URI_format"}';
                }
            }
        }
        else{
            $result = $this->userModel->getAll();

            if($result != null){
                echo json_encode($result);
            }
            else{
                echo '{"status" : "connection error"}';
                header("HTTP/1.1 500");
            }
        }
    }

    public function post($unUsedValue){

        if($unUsedValue == null){
            $passedData = file_get_contents("php://input");
            $data = json_decode($passedData, true);
            // we should check if the data is complate

            if(is_array($data)){
                $insertionState = $this->userModel->insertUser($data);

                if($insertionState){
                    echo '{"status" : "inserted successfully"}';
                }
                else{
                    echo '{"status" : "insertion failed"}';
                }
            }
            else {
                echo '{"status" : "wrong data format"}';
            }



        }
        else{
            echo '{"status" : "wrong uri"}';
        }

    }

    public function delete($paramiterKeyValue){
        if(isset($paramiterKeyValue["id"])){

            $deleteState = $this->userModel->deleteUser($paramiterKeyValue["id"]);

            if($deleteState == null){
                echo '{"status" : "connection error"}';
                header("HTTP/1.1 500");
            }
            else{
                echo '{"status" : "deleted successfully"}';
            }
        }
        else{
            echo '{"status" : "wrong uri"}';
        }
    }

    public function put($paramiterKeyValue){
        if(isset($paramiterKeyValue["id"])){
            $newPassedData = file_get_contents("php://input");
            $data = json_decode($newPassedData, true);

            if(is_array($data)){
                $updateState = $this->userModel->updateUserInfo($paramiterKeyValue["id"] , $data);

                if($updateState == null ){
                    echo '{"status" : "connection error"}';
                    header("HTTP/1.1 500");
                }
                else if($updateState){
                    echo '{"status" : "updated successfully"}';
                }
                else{
                    echo '{"status" : "not found"}';
                    // header("HTTP/1.1 404");
                }
            }
            else {
                echo '{"status" : "wrong data format"}';
            }
        }
        else{
            echo '{"status" : "wrong uri"}';
        }
    }

}

?>
