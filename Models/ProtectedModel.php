<?php 

class ProtectedModel{
    private $dbCon ;

    function __construct(){
        $this->dbCon = DbConnection::dbCon() ;
    }

    public function isAuthorized($token){
        $authResult = AuhtenticationModel::verifingJWT($token , $_ENV["AUTH_SECRET_KEY"]);
        if($authResult == "not_valid"){
            return "not_valid";
        }
        else{
            return "valid";
        }
    }

}

?>