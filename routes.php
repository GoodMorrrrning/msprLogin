<?php

//la function principale
function call($controller,$action){

//Tout d'abord, nous chargeons le fichier php, avec le contrôleur et le modèle corrects.
require_once("controller/$controller.php");
require_once("model/$controller.php");

//Nous appelons la fonction d'action sur le contrôleur
$controller=new $controller;
$controller->{$action}();
}


//Un tableau, pour les contrôleurs autorisés et leurs actions respectives
$controllers = array('product' => ['all','showAll','add','delete'],
                        'AuthController' => ['verify', 'index', 'bypass', 'auth', 'checkOTP'],
                        'comment' => ['all','showAll','allFromUser','delete','add']);


  //Nous vérifions si l'action invoquée fait partie de notre code mvc.
  //sans cette vérification, un produit malveillant, pourrait exécuter un code arbitraire
  if (array_key_exists($controller, $controllers)) {
    if (in_array($action, $controllers[$controller])) {
      call($controller, $action);
    } else {
      call('errorController', 'error');
    }
  } else {
    call('errorController', 'error');
  }

?>