<?php
class AuthController{

    public function isGoodPass(){
      
    }




    public function verify(){
        $ismdpok = "";
        $iscursed = false;
        if(isset($_POST['email']) && isset($_POST['pass']))
        {
            ini_set('memory_limit', '-1');
            $jsp = $_POST['email'];
            $pass = $_POST['pass'];
            
            $file = "C:\Users\marti\Downloads\\rockyou (3).txt";
        $contents = file_get_contents($file);
        $lines = explode("\n", $contents); // this is your array of words
        
        foreach(preg_split("/((\r?\n)|(\r\n?))/", $contents) as $line){
           
         if($line == $pass){
            $iscursed = true;
         }

        } 
        if(!$iscursed){
            $ismdpok = "mot de passe ok";
        }
        else{
            $ismdpok = "mot de passe piratable :(";
        }
          
            
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)
        $browser = "Internet explorer";
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) //For Supporting IE 11
        $browser = "Internet explorer";
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE)
        $browser = "Mozilla Firefox";
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE)
        $browser = "Google Chrome";
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== FALSE)
        $browser = "Opera Mini";
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE)
        $browser = "Opera";
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE)
        $browser = "Safari";
        else
        $browser = "Something else";
      
       

        $file = file_get_contents('brow.json');
        
        
        if($file != ""){

            $data = json_decode($file, true);
         
            $class = new Foo();

            foreach ($data as $key => $value) $class->{$key} = $value;

           if($class->NomNavigateur != $browser){
               echo "vous n'etes pas sur votre navigateur habituel...";
               $link = "index.php?controller=AuthController&action=bypass";
               mail($jsp, 'Changement de navigateur', "Bonjour, c'est vraiment bizarre vous avez changer de navigateur clic ici localhost/logine/" .$link);
               exit();
           } 
        }
      



    

        require_once("view/auth/verify.php");
    }
}

    public function index(){
        require_once("view/auth/index.php");
    }

   
     public function bypass(){

        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)
        $browser = "Internet explorer";
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) //For Supporting IE 11
        $browser = "Internet explorer";
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE)
        $browser = "Mozilla Firefox";
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE)
        $browser = "Google Chrome";
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== FALSE)
        $browser = "Opera Mini";
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE)
        $browser = "Opera";
        elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE)
        $browser = "Safari";
        else
        $browser = "Something else";


        $b = new Foo;
        $b->NomNavigateur = $browser;
      
        file_put_contents('brow.json', json_encode($b));  


         $jsp = "";
         require_once("view/auth/index.php");
     }

}

class Foo {

    public $NomNavigateur="";
   

}

?>