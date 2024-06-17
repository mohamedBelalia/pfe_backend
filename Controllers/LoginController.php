<?php 
    class LoginController{
        private $authenticationModel ;
        private $authKey ;

        function __construct($authKeyPassed){
            $this->authenticationModel = new AuhtenticationModel($authKeyPassed);
            $this->authKey = $authKeyPassed;
        }

        public function request($method , $paramiterKeyValue){
            $this->$method($paramiterKeyValue);
        }

        public function post($unsedParams){
            $passedData = file_get_contents("php://input");
            $data = json_decode($passedData, true);
            $response = $this->authenticationModel->login($data);

            if($response == "connection_error"){
                $result = ["status" => "connection_error"];
                echo json_encode($result);
            }
            else if($response == "uncomplate_data"){
                $result = ["status" => "uncomplate_data"];
                echo json_encode($result);
            }
            else if($response == "not_found"){
                $result = ["status" => "not_found"];
                echo json_encode($result);
            }
            else{
                $result = ["status" => "founded" , "token" => $response];
                echo json_encode($result);
            }
        }

        function testLoad(){
            return $this->authKey . " it's passed !!";
        }
    
    }
?>