<?php 

class WorkerModel{
    private $dbCon ;

    function __construct(){
        $this->dbCon = DbConnection::dbCon() ;
    }

    public function getById($id) : array | null | int {
        if($this->dbCon != null){

            $query = "SELECT O.idOuvrier , O.nomOuvrier , O.prenomOuvrier , O.phone , O.imgProfile , O.experience , O.description_ouvrier , O.ville , V.ville_AR , V.ville_FR
                        FROM ouvriers O
                        LEFT JOIN villes V ON V.idVille = O.ville
                        WHERE O.idOuvrier = '$id';";
            $result = $this->dbCon->query($query);

            if($result->num_rows > 0){
                return $result->fetch_all(MYSQLI_ASSOC);
            }

            return 0 ;
        }
        return null ;

    }


    public function getByFilter($filter){
        if(is_array($filter)){

            $condtionPart = "" ;
            foreach($filter as $key => $value){
                if($key == "profession" && strlen($value) > 0 ){
                    $condtionPart .= " (P.labelleProfession_FR = '$value' OR P.labelleProfession_AR = '$value') AND ";
                }

                if($key == "ville" && strlen($value) > 0){
                    $condtionPart .= " O.ville = '$value' AND " ;
                }

                if($key == "badge" && strlen($value) > 0){
                    if(str_contains($value , "|")){
                        $badgesList = explode("|" , $value) ;
                        $badge1 = $badgesList[0];
                        $badge2 = $badgesList[1];
                        $condtionPart .= " O.badgeId in ('$badge1' , '$badge2') AND " ;
                    }
                    else{
                        $condtionPart .= " O.badgeId = '$value' AND " ;
                    } 
                }
            }
            $condtionPart .= "1" ;
            
            
            if($this->dbCon != null){
                $query = "
                    SELECT O.idOuvrier,O.nomOuvrier, O.prenomOuvrier, O.phone, O.imgProfile , O.ville , 
                    B.* ,
                    COUNT(CO.commentaire_id) AS nbrCommentair,                              
                    CAST(AVG(CO.moyenneEtoiles) AS DECIMAL(10,1)) AS avgEtoile
                    FROM ouvriers_maitrisent_professions OMF
                    INNER JOIN ouvriers O ON O.idOuvrier = OMF.idOuvrier
                    INNER JOIN professions P ON P.idProfession = OMF.idProfession
                    LEFT JOIN badges B ON B.idBadge = O.badgeId 
                    LEFT JOIN commentaires CO ON CO.idOuvrier = O.idOuvrier
                    WHERE $condtionPart
                    GROUP BY O.idOuvrier, O.nomOuvrier, O.prenomOuvrier, O.phone, O.imgProfile;;
                " ;
                $result = $this->dbCon->query($query);

                $returnedResult = $result->fetch_all(MYSQLI_ASSOC);

                if($result->num_rows > 0){
                    return $returnedResult;
                }

             return ["status" => "not found"];
            }

            

            return ["status" => "connection error"] ;

        }
        return ["status"=>"wrong format"];

    }

    public function getTop($top){
        if($this->dbCon != null){

            $query = "SELECT O.*
                            FROM ouvriers O 
                            LIMIT $top;";
            $result = $this->dbCon->query($query);

            if($result->num_rows > 0){

                return $result->fetch_all(MYSQLI_ASSOC);
            }

            return 0 ;
        }
        return null ;
    }

    public function getUserInfoByToken($token){
        $tokenValidation = AuhtenticationModel::verifingJWT($token , $_ENV["AUTH_SECRET_KEY"]) ;
        if($tokenValidation == "not_valid"){
            return "not_valid";
        }

        $userId = $tokenValidation["id"];
        $query = "SELECT O.idOuvrier , O.nomOuvrier, O.prenomOuvrier, O.phone, O.imgProfile, O.description_ouvrier, O.experience , V.ville_AR , V.ville_FR
                    FROM ouvriers O
                    INNER JOIN villes V ON V.idVille = O.ville
                    WHERE idOuvrier = '$userId';";

        $result = $this->dbCon->query($query);

        return $result->fetch_assoc() ;
    }

    public function getAll(){
        if($this->dbCon != null){

            $query = "SELECT * FROM ouvriers ORDER BY idOuvrier";
            $result = $this->dbCon->query($query);

            if($result->num_rows > 0){
                return $result->fetch_all(MYSQLI_ASSOC);
            }

            return ["status" => "not found"];
        }
        return null ;
    }


    public function insertUser(array $userInfo) : bool | null {
        if($this->dbCon != null){
            $nomOuvrier = $userInfo["nomOuvrier"] ;
            $prenomOuvrier = $userInfo["prenomOuvrier"] ;
            $phone = $userInfo["phone"] ;
            $imgProfile = $userInfo["imgProfile"] ;
            $motDePasse = $userInfo["motDePasse"] ;
            $ville = $userInfo["ville"] ;
            $description_ouvrier = $userInfo["description_ouvrier"] ;

            $query = "INSERT INTO ouvriers(nomOuvrier , prenomOuvrier ,phone ,imgProfile ,  motDePasse,ville ,description_ouvrier ) 
                    VALUES('$nomOuvrier' , '$prenomOuvrier' , '$phone' , '$imgProfile' , '$motDePasse' , '$ville' , '$description_ouvrier');";

            if($this->dbCon->query($query)){
                return true ;
            }
            
            return false ;
        }
       
        return null ;
        
    }

    public function deleteUser(string $id): bool | null{
        if($this->dbCon != null){
            $query = "DELETE FROM ouvriers WHERE idOuvrier = '$id';";
            if($this->dbCon->query($query)){
                return true ;
            }

            return false ;
        }

        return null ;

    }

    public function updateUserInfo(string $id , array $newUserInfo): bool | null{
        if($this->dbCon != null){
            $nomOuvrier = $newUserInfo["nomOuvrier"] ;
            $prenomOuvrier = $newUserInfo["prenomOuvrier"] ;
            $phone = $newUserInfo["phone"] ;
            $imgProfile = $newUserInfo["imgProfile"] ?? "defaultUserImage.png" ;
            $villeId = $newUserInfo["villeId"] ;
            $description_ouvrier = $newUserInfo["description"] ;
            $experience = $newUserInfo["experience"] ;

            $query = "" ;
            if(strlen($villeId) > 0){
                $query = "UPDATE ouvriers
                      SET nomOuvrier = '$nomOuvrier', prenomOuvrier = '$prenomOuvrier', experience = '$experience' ,
                         phone = '$phone' , imgProfile = '$imgProfile' , ville = '$villeId' , description_ouvrier = '$description_ouvrier'
                      WHERE idOuvrier = '$id';";
            }
            else{
                $query = "UPDATE ouvriers
                      SET nomOuvrier = '$nomOuvrier', prenomOuvrier = '$prenomOuvrier', experience = '$experience' ,
                         phone = '$phone' , imgProfile = '$imgProfile' , description_ouvrier = '$description_ouvrier'
                      WHERE idOuvrier = '$id';";
            }
            
            if($this->dbCon->query($query)){
                return true ;
            }

            return false ;
        }

        return null ;
    }

}

/*
 SELECT P.* FROM ouvriers_maitrisent_professions OP
 INNER JOIN professions P ON P.idProfession = OP.idProfession
 WHERE OP.idOuvrier = 1
*/

?>

