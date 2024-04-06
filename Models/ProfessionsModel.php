<?php 


class ProfessionsModel{

    private $dbConnection ;

    function __construct(){
        $this->dbConnection = DbConnection::dbCon() ;
    }

    public function getAll(){
        if($this->dbConnection != null){

            $query = "SELECT * FROM professions";
            $result = $this->dbConnection->query($query);

            if($result->num_rows > 0){
                return $result->fetch_all(MYSQLI_ASSOC);
            }

            return ["status" => "not found"];
        }

        return null ;
    }

    public function getById($id){
        if($this->dbConnection != null){

            $query = "SELECT * FROM professions WHERE idProfession = '$id'";
            $result = $this->dbConnection->query($query);

            if($result->num_rows > 0){

                return $result->fetch_all(MYSQLI_ASSOC);
            }

            return 0 ;
        }
        return null ;
    }

    public function getByWorkerId($id){
        if($this->dbConnection != null){

            $query = "SELECT P.* FROM ouvriers_maitrisent_professions OMP
                    INNER JOIN ouvriers O ON O.idOuvrier = OMP.idOuvrier
                    INNER JOIN professions P ON P.idProfession = OMP.idProfession
                    WHERE OMP.idOuvrier = '$id';";
                    
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