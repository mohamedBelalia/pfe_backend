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
            if($paramiterKeyValue[0] == "id" && isset($paramiterKeyValue[1])){

                $result = $this->userModel->getById($paramiterKeyValue[1]);

                if($result != null){
                    echo json_encode($result);
                }
                else if($result == 0){
                    echo '{"status" : "not_found"}';
                }
                else{
                    echo '{"status" : "connection error"}';
                }
            }
            else if($paramiterKeyValue[0] == "filter" && $paramiterKeyValue[1] != null){
                $result = $this->userModel->getByFilter($paramiterKeyValue[1]);

                if($result != null){
                    echo json_encode($result);
                }
                else{
                    echo '{"status" : "connection error"}';
                }
            } 
            else if($paramiterKeyValue[0] == "top" && isset($paramiterKeyValue[1])){

                if((int)$paramiterKeyValue[1]){
                    $result = $this->userModel->getTop($paramiterKeyValue[1]);

                    if($result == null){
                        echo '{"status" : "connection_error"}';
                    }
                    else if($result == 0){
                        echo '{"status" : "not_found"}';
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
            }
        }
    }

    public function post($unUsedValue){

        if($unUsedValue == null){
            $passedData = file_get_contents("php://input");
            $data = json_decode($passedData, true);
            // we should check if the data is complate

            if(is_array($data)){
                $insertionState = $this->userModel->insertUser($data) ;

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
        if($paramiterKeyValue[0] == "id" && $paramiterKeyValue[1] != null){
            
            $deleteState = $this->userModel->deleteUser($paramiterKeyValue[1]);

            if($deleteState == null){
                echo '{"status" : "connection error"}';
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
        if($paramiterKeyValue[0] == "id" && $paramiterKeyValue[1] != null){
            $newPassedData = file_get_contents("php://input");
            $data = json_decode($newPassedData, true);

            if(is_array($data)){
                $updateState = $this->userModel->updateUserInfo($paramiterKeyValue[1] , $data);

                if($updateState == null ){
                    echo '{"status" : "connection error"}';
                }
                else if($updateState){
                    echo '{"status" : "updated successfully"}';
                }
                else{
                    echo '{"status" : "not found"}';
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
