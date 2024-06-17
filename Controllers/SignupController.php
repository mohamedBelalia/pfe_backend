<?php 

    class SignupController{
        private $authenticationModel ;

        function __construct($authKeyPassed){
            $this->authenticationModel = new AuhtenticationModel($authKeyPassed);
        }

        public function request($method , $paramiterKeyValue){
            $this->$method($paramiterKeyValue);
        }

        public function post($unsedParams){
            $passedData = file_get_contents("php://input");
            $data = json_decode($passedData, true);

            $resultSignup = $this->authenticationModel->signupUser($data) ;

            if($resultSignup == "uncomplate_data"){
                echo '{"status" : "uncomplate_data"}';
            }
            else if($resultSignup == ""){
                echo '{"status" : "already_exist"}';
            }
            else if($resultSignup == "something_wrong"){
                echo '{"status" : "something_wrong"}';
            }
            else{
                $signUpSuccessfully = [
                    "token" => $resultSignup ,
                ];

                echo json_encode($signUpSuccessfully);
            }
        }

        public function get($pp){
            $jwt = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MjQsInBob25lTnVtYmVyIjoiMDc3Nzc4Nzc3NyIsImZpcnN0TmFtZSI6IktoYWxpZCAxIiwicHdkIjoiMTMyQUVSIzQxIn0.V-Nv-oEK-SU1ESJbB2soCSotVRAewF_xBIkFZekLyMY";
            $secretKey = "work_hard_not_smart";

            echo json_encode(AuhtenticationModel::verifingJWT($jwt , $secretKey));

        }
    
    }
?>
