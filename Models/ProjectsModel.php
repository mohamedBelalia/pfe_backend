<?php 

class ProjectsModel{
    private $dbConnection ;

    function __construct(){
        $this->dbConnection = DbConnection::dbCon() ;
    }

    public function getByWorkerId($id){
        if($this->dbConnection != null){

            $query = "SELECT P.* FROM ouvriers O 
                        INNER JOIN projects_ouvriers P ON P.idOuvrier = O.idOuvrier
                        WHERE O.idOuvrier = '$id';";
                    
            $result = $this->dbConnection->query($query);

            if($result->num_rows > 0){

                return $result->fetch_all(MYSQLI_ASSOC);
            }

            return 0 ;
        }
        return null ;
    }

    public function getById($id){
        if($this->dbConnection != null){

            $query = "SELECT * FROM projects_ouvriers WHERE idProjet = '$id';";
                    
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