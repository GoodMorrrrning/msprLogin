<?php
//l'entrée de notre application

//toutes les requêtes passe par ici

//chargement de la bd
require_once("db.php");

//on charge le produit dans ces 2 variables
//on récup controller et action
if(isset($_GET["controller"])&&isset($_GET["action"])){

    $controller=$_GET["controller"];
    $action=$_GET["action"];

//par défaut
}else {
    
$controller="AuthController";
$action="index";

}

//on charge les routes
require_once("routes.php");
?>