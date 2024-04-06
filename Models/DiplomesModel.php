<?php 

class DiplomesModel{
    private $dbConnection ;

    function __construct(){
        $this->dbConnection = DbConnection::dbCon() ;
    }

    public function getAll(){
        if($this->dbConnection != null){

            $query = "SELECT * FROM diplomes";
            $result = $this->dbConnection->query($query);

            if($result->num_rows > 0){
                return $result->fetch_all(MYSQLI_ASSOC);
            }

            return ["status" => "not found"];
        }

        return null ;
    }

    public function getByWorkerId($id){
        if($this->dbConnection != null){

            $query = "SELECT D.* FROM ouvriers_avoir_diplomes OAD
                    INNER JOIN diplomes D ON D.idDiplome = OAD.idDiplome
                    WHERE OAD.idOuvrier = '$id';";
                    
            $result = $this->dbConnection->query($query);

            if($result->num_rows > 0){

                return $result->fetch_all(MYSQLI_ASSOC);
            }

            return 0 ;
        }
        return null ;
    }
}

?>