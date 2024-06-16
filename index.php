<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Authorization, Content-Type");

require "./config/DbConnection.php";
require "helpers/Format.php";
require "Controllers/WorkerController.php";
require "Controllers/ProfessionsController.php";
require "Controllers/DiplomesController.php";
require "Controllers/ProjectsController.php";
require "Controllers/RatesController.php";
require "Controllers/CommentairesController.php";
require "Controllers/VillesController.php";
require "Controllers/ProjectImagesController.php";
require "Controllers/SignupController.php";


$passedParamiterValue = null ;
$paramiterKeyValue = null ; 

$method = $_SERVER['REQUEST_METHOD'];

$url = urldecode($_SERVER['REQUEST_URI']);


$urlParts = explode("/", $url);
$startCount = array_search("api" , $urlParts);

$paramiters = $urlParts[$startCount + 1] ;

$paramitersArray = explode("?" , $paramiters);

$endpoint = $paramitersArray[0];

if(isset($paramitersArray[1])){
    $paramiterKeyValue = Format::formatPassedParamiters($paramitersArray[1]);
}


switch ($endpoint) {
    case "workers":
        $worker = new WorkerController();
        $worker->request($method , $paramiterKeyValue);
        break;
    case "professions" :
        $profession = new ProfessionsController();
        $profession->request($method , $paramiterKeyValue);
        break ;
    case "diplomes":
        $diplome = new DiplomesController();
        $diplome->request($method , $paramiterKeyValue);
        break ;
    case "projects":
        $project = new ProjectsController();
        $project->request($method , $paramiterKeyValue);
        break ;
    case "comments":
        $comment = new RatesController();
        $comment->request($method , $paramiterKeyValue);
        break ;
    case "commentaire" :
        $commentaire = new CommentairesController();
        $commentaire->request($method , $paramiterKeyValue);
        break ;
    case "villes" : 
        $villes = new VillesController();
        $villes->request($method , $paramiterKeyValue);
        break ;
    case "porject-images" :
        $projectImages = new ProjectImagesController() ;
        $projectImages->request($method , $paramiterKeyValue);
        break;
    case "Signup" :
        $signupWorker = new SignupController();
        $signupWorker->request($method , $paramiterKeyValue);
        break;

    default:
        echo 0;
}





?>