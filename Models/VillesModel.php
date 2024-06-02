<?php 

class VillesModel{

    private $dbConnection ;

    function __construct(){
        $this->dbConnection = DbConnection::dbCon() ;
    }

    public function getAll(){
        if($this->dbConnection != null){
            $query = "SELECT * FROM villes;" ;

            $result = $this->dbConnection->query($query);

            if($result->num_rows != 0){
                return $result->fetch_all(MYSQLI_ASSOC);
            }
            return ["status" => "not found"];
        }

        return null ;
    }


    public function getById($id){
        if($this->dbConnection != null){
            $query = "SELECT * FROM villes WHERE idVille = '$id'";

            $result = $this->dbConnection->query($query);

            if($result->num_rows != 0){
                return $result->fetch_assoc(); // get only one record
            }
            return ["status" => "not found"] ;
        }
    }

}

?>