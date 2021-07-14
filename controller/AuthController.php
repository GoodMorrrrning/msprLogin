<?php
class AuthController{
    public function verify(){

        if(isset($_POST['email']))
        {
        echo $_POST['email'];
        }
        if(isset($_POST['pass']))
        {
        echo $_POST['pass'];
        }
    
    $jsp = $_POST['email'];
    $jsp =  $_POST['pass'];

        require_once("view/auth/verify.php");
    }

    public function index(){
        require_once("view/auth/index.php");
    }



}

?>