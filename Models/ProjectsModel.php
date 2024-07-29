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

                return $result->fetch_assoc();
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
                $images = [] ;

                while($img = $result->fetch_assoc()){
                    array_push($images , $img["imgPath"]);
                }

                if($result->num_rows > 0){
                    return $images;
                }

                return 0 ;
            }
        }

        return null ;
    }


    public function addProject($token, $title, $description, $images) {

        $tokenResult = AuhtenticationModel::verifingJWT($token , $_ENV["AUTH_SECRET_KEY"]);

        if($tokenResult == "not_valid"){
            return "not_valid";
        }

        if ($this->dbConnection != null) {
        
            $this->dbConnection->begin_transaction();
    
            try {
                // Insertion dans la table "projects_ouvriers"
                $queryInsertInProjetOuvriers = "INSERT INTO projects_ouvriers(titre, description_projet, imageProjet, idOuvrier) 
                VALUES(?,?,?,?)";
    
                $stmtCommandProjetOuvriers = $this->dbConnection->prepare($queryInsertInProjetOuvriers);
                $stmtCommandProjetOuvriers->bind_param("sssi", $title, $description, $images[0], $tokenResult["id"]);
                $stmtCommandProjetOuvriers->execute();
    
                $idProjet = $stmtCommandProjetOuvriers->insert_id;
    
                // Insertion dans la table "project_images"
                $queryInsertInProjetImages = "INSERT INTO project_images(idProject, imgPath) VALUES (?,?)";
                $stmtCommandProjetImages = $this->dbConnection->prepare($queryInsertInProjetImages);
    
                foreach ($images as $img) {
                    $stmtCommandProjetImages->bind_param("is", $idProjet, $img);
                    if (!$stmtCommandProjetImages->execute()) {
                        return "inserting_failed" ;
                    }
                }
    
                $this->dbConnection->commit();

                return "inserted";

            } catch (Exception $e) {
                $this->dbConnection->rollback();
                return "exception_error";
            }
        }
    }

    public function updateProject($projectId , $title , $description , $images){
        if ($this->dbConnection != null) {
        
            $this->dbConnection->begin_transaction();
    
            try {
                if(isset($images[0])){
                    $coverImg = $images[0] ;
                    $queryUpdate = "UPDATE projects_ouvriers SET titre = '$title', description_projet = '$description', imageProjet = '$coverImg'  WHERE idProjet = '$projectId'";
                }
                else{
                    $queryUpdate = "UPDATE projects_ouvriers SET titre = '$title', description_projet = '$description' WHERE idProjet = '$projectId'";
                }
                // updating
                $this->dbConnection->query($queryUpdate);
    
                // Insertion dans la table "project_images"
                $queryInsertInProjetImages = "INSERT INTO project_images(idProject, imgPath) VALUES (?,?)";
                $stmtCommandProjetImages = $this->dbConnection->prepare($queryInsertInProjetImages);
    
                foreach ($images as $img) {
                    $stmtCommandProjetImages->bind_param("is", $projectId, $img);
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
    

    public function deleteProject($idProject) {
        // Start the transaction
        // $this->dbConnection->begin_transaction();
    
        try {
            $querySelect = "SELECT idImg, imgPath FROM project_images WHERE idProject = '$idProject';";
            $result = $this->dbConnection->query($querySelect);
    

            $imagePaths = [];
            while ($image = $result->fetch_assoc()) {
                $imagePaths[] = $image["imgPath"];
                $idImg = $image["idImg"];
                $queryDeleteImg = "DELETE FROM `project_images` WHERE idImg = '$idImg'";
                $this->dbConnection->query($queryDeleteImg);
            }
    

            $queryDeletePost = "DELETE FROM `projects_ouvriers` WHERE idProjet = '$idProject'";
            $this->dbConnection->query($queryDeletePost);
    

            // $this->dbConnection->commit();
    

            foreach ($imagePaths as $imagePath) {
                $this->deleteImage($imagePath);
            }
    
            return "successful";
    
        } catch (Exception $e) {

            // $this->dbConnection->rollback();
            return "failed";
        }
    }
    
    // delete file
    public function deleteImage($imagePath) {
        if (file_exists($imagePath)) {
            unlink($imagePath);
        } else {
            return "no";
        }
    }
    


}

?>