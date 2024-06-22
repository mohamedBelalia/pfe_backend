<?php 

class ProfileController{

    public function request($method , $paramiterKeyValue){
        $this->$method($paramiterKeyValue);
    }

    public function get($params){
        if(isset($params["token"])){
            $resultAuth = AuhtenticationModel::verifingJWT($params["token"] , $_ENV["AUTH_SECRET_KEY"]);
            if($resultAuth == "not_valid"){
                echo json_encode(["status" => "not_valid"]);
            }   
            else{
                echo json_encode($resultAuth);
            }
        }
    }
}

?>