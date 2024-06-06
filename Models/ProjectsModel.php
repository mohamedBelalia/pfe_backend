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


    public function getProjectImages($idProject , $imgParam){
        if($this->dbConnection != null){
            if($imgParam == "all"){
                $query = "SELECT imgPath FROM project_images WHERE idProject = '$idProject';" ;

                $result = $this->dbConnection->query($query);

                if($result->num_rows > 0){
                    return $result->fetch_all(MYSQLI_ASSOC);
                }

                return 0 ;
            }
        }

        return null ;
    }


    public function addProject($idWorker, $title, $description, $images) {
        if ($this->dbConnection != null) {
        
            $this->dbConnection->begin_transaction();
    
            try {
                // Insertion dans la table "projects_ouvriers"
                $queryInsertInProjetOuvriers = "INSERT INTO projects_ouvriers(titre, description_projet, imageProjet, idOuvrier) 
                VALUES(?,?,?,?)";
    
                $stmtCommandProjetOuvriers = $this->dbConnection->prepare($queryInsertInProjetOuvriers);
                $stmtCommandProjetOuvriers->bind_param("sssi", $title, $description, $images[0], $idWorker);
                $stmtCommandProjetOuvriers->execute();
    
                $idProjet = $stmtCommandProjetOuvriers->insert_id;
    
                // Insertion dans la table "project_images"
                $queryInsertInProjetImages = "INSERT INTO project_images(idProject, imgPath) VALUES (?,?)";
                $stmtCommandProjetImages = $this->dbConnection->prepare($queryInsertInProjetImages);
    
                foreach ($images as $img) {
                    $stmtCommandProjetImages->bind_param("is", $idProjet, $img);
                    if (!$stmtCommandProjetImages->execute()) {
                        throw new Exception("Error executing query");
                    }
                }
    
                $this->dbConnection->commit();
                return true;
            } catch (Exception $e) {
                $this->dbConnection->rollback();
                return false;
            }
        }
    }
    

}

?>