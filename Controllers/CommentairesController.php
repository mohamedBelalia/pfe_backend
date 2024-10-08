<?php 

require "Models/CommentairesModel.php";
   
class CommentairesController {
    private $commentaireModel ;

    function __construct(){
        $this->commentaireModel = new CommentairesModel();
    }

    public function request($method , $paramiterKeyValue){
        $this->$method($paramiterKeyValue);
    }

    public function get($passedParamiter){
        if(isset($passedParamiter["rateOf"]) && isset($passedParamiter["workerId"])){
            $result = $this->commentaireModel->getCommentRating($passedParamiter["workerId"] , $passedParamiter["rateOf"]);

            echo json_encode($result);
        }
    }

    public function post(){
        $passedData = file_get_contents("php://input");
        $data = json_decode($passedData, true);

        $insertionResult = $this->commentaireModel->insertCommentaire($data);

        if($insertionResult === true){
            echo json_encode(["status" => "insertion_done"]);
        }
        else if($insertionResult === null){
            echo json_encode(["status" => "connection_error"]);
            header("HTTP/1.1 500");
        }
        else if($insertionResult == "uncompleted_data"){
            echo json_encode(["status" => "uncompleted_data"]);
        }
        else if($insertionResult == "wrong_values"){
            echo json_encode(["status" => "wrong_values"]);
        }
        else if($insertionResult === false){
            echo json_encode(["status" => "insertion_failed"]);
        }
    }
}


?>

