<?php 
    require "Models/VillesModel.php";

    class VillesController{
        private $villesModel;

        function __construct(){
            $this->villesModel = new VillesModel() ;
        }

        public function request($method , $paramiterKeyValue){
            $this->$method($paramiterKeyValue);
        }

        public function get($usedParamiter){
            if($usedParamiter == null){
                $result = $this->villesModel->getAll();
                if($result != null){
                    echo json_encode($result);
                }
                else{
                    // header("HTTP/1.1 404");
                }
            }
            else if(isset($usedParamiter["id"])){
                $result = $this->villesModel->getById($usedParamiter["id"]) ;
                if($result != null){
                    echo json_encode($result);
                }
                else{
                    header("HTTP/1.1 500");
                }
            }
        }

    }

?>

