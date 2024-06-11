<?php 

class CommentairesModel{

    private $dbConnection ;

    public function __construct(){
        $this->dbConnection = DbConnection::dbCon() ;
    }

    // get the comments rating 
    public function getCommentRating($workerId , $commentType){
        if($this->dbConnection != null){
            $query = "SELECT
                            SUM(CASE WHEN $commentType = 'Bien' THEN 1 ELSE 0 END) AS count_Bien,
                            SUM(CASE WHEN $commentType = 'Tres Bien' THEN 1 ELSE 0 END) AS count_Tres_Bien,
                            SUM(CASE WHEN $commentType = 'Excellent' THEN 1 ELSE 0 END) AS count_Excellent
                            FROM commentaires WHERE idOuvrier = '$workerId';" ;

            $result = $this->dbConnection->query($query);

            $returnedResult = $result->fetch_all(MYSQLI_ASSOC);

            if($result->num_rows > 0){
                return $returnedResult ;
            }

            return ["status" => "not found"];
        }

        return ["status" => "connection_error"];
            
       
    }

    public function insertCommentaire(array $commentaireData){

        $acceptedCommentValues = ["Excellent" , "Tres Bien" , "Bien"] ;

        if($this->dbConnection != null){
            if(isset($commentaireData["idOuvrier"]) && isset($commentaireData["respect_delais"]) 
               && isset($commentaireData["quality_travail"]) && isset($commentaireData["prix_qualite"]) 
                && isset($commentaireData["moyenneEtoiles"]))
            {
                $idOuvrier = $commentaireData["idOuvrier"] ;
                $respect_delais = $commentaireData["respect_delais"] ;
                $quality_travail = $commentaireData["quality_travail"] ;
                $prix_qualite = $commentaireData["prix_qualite"] ;
                $moyenneEtoiles = $commentaireData["moyenneEtoiles"] ;

                if(in_array($respect_delais ,$acceptedCommentValues) 
                    && in_array($quality_travail ,$acceptedCommentValues) 
                    && in_array($prix_qualite ,$acceptedCommentValues))
                {

                    $query = "INSERT INTO commentaires( idOuvrier, respect_delais, quality_travail, prix_qualite, moyenneEtoiles) 
                    VALUES ('$idOuvrier','$respect_delais','$quality_travail','$prix_qualite','$moyenneEtoiles')";

                    if($this->dbConnection->query($query)){
                        return true ;
                    }
                    return false ;
                }
                return "wrong_values";

            }
            
            return "uncompleted_data";

        }
        
        return null ;
    }
}

?>
