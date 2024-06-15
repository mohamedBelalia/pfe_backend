<?php 

include "./Models/ProjectImagesModel.php";

class ProjectImagesController{
    private $projecImagestModel ;

    function __construct(){
        $this->projecImagestModel = new ProjectImagesModel();
    }

    public function request($method , $paramiterKeyValue){
        $this->$method($paramiterKeyValue);
    }

    public function put($passedParam){

        if(isset($passedParam["idProject"])){
            $rawData = file_get_contents("php://input");
            $images = json_decode($rawData, true);
            
            $result = $this->projecImagestModel->deleteImags($passedParam["idProject"] , $images) ;
            if($result != "connection_error"){
                if($result){
                    echo '{"status" : "deleted_successfully"}';
                }
                else{
                    echo '{"status" : "deleting_failed"}';
                }
            }
            else{
                echo '{"status" : "connection_error"}';
            }

        }

    }

}

?>
