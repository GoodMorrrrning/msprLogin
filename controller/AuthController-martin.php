<?php
class AuthController{

    public function isGoodPass(){
      
    }

    public function getIp(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
          $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
          $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
          $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
      }

public function ConnectToLdap(){
    if(isset($_POST['email']) && isset($_POST['pass']))
    {
      
        $pass =  $_POST["pass"];
        $ldap_dn = "uid=".$_POST["email"].",dc=example,dc=com";
        $ldap_password = $_POST["pass"];
        
        $ldap_con = ldap_connect("ldap.forumsys.com");
        ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
    }
    return @ldap_bind($ldap_con,$ldap_dn,$ldap_password);
}

public function isPassCursed($pass){
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
    return $ismdpok;
}


    public function verify(){
        $isCo = false;
      $res="";
    
      //RGPDDDDD
      $apc_key = "{$_SERVER['SERVER_NAME']}~login:{$_SERVER['REMOTE_ADDR']}";
    
      $tries = (int)apcu_fetch($apc_key);
      if ($tries >= 10) {
        header("HTTP/1.1 429 Too Many Requests");
        echo "You've exceeded the number of login attempts. We've blocked IP address" . $this->getIp(). "for a few minutes.";
       
      //  exit();
      }

        ini_set('memory_limit', '-1');
        $ismdpok = "";
        $iscursed = false;
       $ip = $this->getIp();
        if(isset($_POST['email']) && isset($_POST['pass']))
        {
            $jsp = $_POST['email'];
        
            if($this->ConnectToLdap()){
   //here
   $res = "ok";
   apcu_delete($apc_key);


   $ismdpok = $this->isPassCursed($_POST['pass']);
  
    $putain = "putain";



       
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
        
          $link = "localhost/logine/index.php?controller=AuthController&action=bypass";
          $title = "clic ici fdp";
          $message = "<a href='". $link. "'>" .$title. "</a>\n\n";
          $headers  = 'MIME-Version: 1.0' . "\r\n";
          $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
          mail("martingenereuxccl@gmail.com", 'Changement de navigateur', "Bonjour, c'est vraiment bizarre vous avez changer de navigateur  ". $message, $headers);
          exit();
      } 
        if($class->ip != $ip){
            echo "vous avez changer d'adresse ip XD";
        
            $link = "localhost/logine/index.php?controller=AuthController&action=bypass";
            $title = "clic ici fdp";
            $message = "<a href='". $link. "'>" .$title. "</a>\n\n";
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            mail("martingenereuxccl@gmail.com", 'Changement addr ip', "Bonjour, c'est vraiment bizarre vous avez changer d'ip ". $message, $headers);
            exit();
        }
    
   }
   $isCo = true;     
            } 
                   
           else{
            require_once("view/auth/index.php");
            $res= "zeubi";
            $triess = $tries+1;
            $time = 60;
            apcu_inc($apc_key, $triess, $time);  # store tries for 10 minutes
           }
          
             
            
          
        }
        if($isCo){
            require_once("view/auth/verify.php");
        }
        else{
            require_once("view/auth/index.php");
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
        $b->ip = $this->getIp();
       
        file_put_contents('brow.json', json_encode($b));  


         $jsp = "";
         require_once("view/auth/index.php");
     }

}

class Foo {

    public $NomNavigateur="";
    public  $ip = "";
   

}

?>