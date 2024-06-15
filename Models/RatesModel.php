<?php 

class RatesModel{
    
    private $dbConnection ;

    function __construct(){
        $this->dbConnection = DbConnection::dbCon();
    }

    public function getByIdWorker($idWorker){
        if($this->dbConnection != null){
            $query = "SELECT * FROM commentaires WHERE idOuvrier = '$idWorker';";

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