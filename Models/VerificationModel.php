<?php 

class VerificationModel{

    private $dbCon ;

    function __construct(){
        $this->dbCon = DbConnection::dbCon() ;
    }

    public function complatedFields($workerID){
        if($this->dbCon != null){
            $query = "SELECT imgProfile, description_ouvrier, experience
                    FROM ouvriers 
                    WHERE idOuvrier = '$workerID'";
            return $this->dbCon->query($query);   
        }
    }

}

?>