<?php 

    require "./Models/ProtectedModel.php";

class ProtectedController{
    private $protectedModel ;

    function __construct(){
        $this->protectedModel = new ProtectedModel();
    }

    public function request($method , $paramiterKeyValue){
        $this->$method($paramiterKeyValue);
    }

    public function get($param){
        if(isset($param["token"])){
            $result = $this->protectedModel->isAuthorized($param["token"]);
            echo json_encode(["status" => $result]);
        }
        else{
            echo json_encode(["status" => "provide_token"]);
        }
    }

}

?>