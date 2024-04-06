<?php 

require "./Models/ProfessionsModel.php";

class ProfessionsController{

    private $professionsModel ;

    function __construct(){
        $this->professionsModel = new ProfessionsModel();
    }

    public function request($method , $paramiterKeyValue){
        $this->$method($paramiterKeyValue);
    }

    public function get($paramiterKeyValue){

        if(empty($paramiterKeyValue)){
            $result = $this->professionsModel->getAll();

            if($result != null){
                echo json_encode($result);
            }
            else{
                echo '{"status" : "connection error"}';
            }
        }
        else if(isset($paramiterKeyValue["id"])){
            
            $result = $this->professionsModel->getById($paramiterKeyValue["id"]);

                if($result != null){
                    echo json_encode($result);
                }
                else if($result == 0){
                    echo '{"status" : "not found"}';
                }
                else{
                    echo '{"status" : "connection error"}';
                }
        }
        else if($paramiterKeyValue["workerId"]){
            $result = $this->professionsModel->getByWorkerId($paramiterKeyValue["workerId"]);

            if($result != null){
                echo json_encode($result);
            }
            else if($result == 0){
                echo '{"status" : "not found"}';
            }
            else{
                echo '{"status" : "connection error"}';
            }
        }

    }

}

// SELECT COUNT(*) AS nbr_com , AVG(nbrEtoile) AS moyStars FROM commentaires_ouvriers WHERE idOuvrier = 1;

?>