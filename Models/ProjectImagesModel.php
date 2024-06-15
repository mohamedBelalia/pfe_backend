<?php 



class ProjectImagesModel{
    private $dbConnection ;

    function __construct(){
        $this->dbConnection = DbConnection::dbCon() ;
    }

    public function deleteImags($projectId ,$imagesPath){
        $countDeleted = 0 ;
        if($this->dbConnection != null){
            foreach($imagesPath as $img){
                if($this->deleteSingleImage($projectId , $img)){
                    $countDeleted ++ ;
                }
            }
            if($countDeleted == count($imagesPath)){
                return true;
            }
            return false ;
        }
        return "connection_error" ;
    }


    // delete single image
    private function deleteSingleImage($projectId , $imgPath){
        $query = "DELETE FROM project_images WHERE imgPath = '$imgPath' AND idProject  = '$projectId';";
        if($this->dbConnection->query($query) && @unlink($imgPath)){
            return true ;
        }

        return false ;
    }
}

?>