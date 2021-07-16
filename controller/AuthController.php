<?php
class AuthController{

   //envoie OTP par mail
    public function sendOTP(){
        if(isset($_POST['email']))
        {
            ini_set('memory_limit', '-1');

            //récupération mail
            $jsp = $_POST['email'];
            
            //génération code OTP
            $otp = rand(100000,999999);

            // envoie mail
            require_once("mail_function.php");
            $mail_status = sendOTP($_POST["email"],$otp);

            //si mail envoyé
            if($mail_status == 1) {
                
                //insertion pour vérification ultérieur de l'OTP
                $q = "INSERT INTO otp_expiry(id, otp, is_expired, create_at) VALUES ('" . $otp . "', '" . $otp . "', 0,  CAST(N'".date("Y-m-d H:i:s")."' AS DateTime))";
                
                $result = $conn->query($q);		

                //si l'insert à marché, succés
                if(!empty($result)) {
                    //$success=1;
                    return true;
                }
            }
        }   
        return false;     
    }

    //vérificateur du code OTP
    public function checkOTP(){
        //Si on trouve un code
        if(!empty($_POST["submit_otp"])) {
            //requêtes -> vérification du code en BDD
            $result = mysqli_query($conn,"SELECT * FROM otp_expiry WHERE otp='" . $_POST["otp"] . "' AND is_expired!=1 AND NOW() <= DATE_ADD(create_at, INTERVAL 24 HOUR)");
            $count  = mysqli_num_rows($result);
            //si la requêtes retourne quelque chose
            if(!empty($count)) {
                //succés
                $result = mysqli_query($conn,"UPDATE otp_expiry SET is_expired = 1 WHERE otp = '" . $_POST["otp"] . "'");
                //$success = 2;
                return true;	
            } else {
                //échec
                //$success =1;
                $error_message = "Invalid OTP!";
                return false;
            }	
        }
    }

    //Vérification Principale
    public function verify(){
        //
        $ismdpok = "";
        $iscursed = false;
        if(isset($_POST['email']) && isset($_POST['pass']))
        {
            ini_set('memory_limit', '-1');
            $jsp = $_POST['email'];
            $pass = $_POST['pass'];
            
            //pour check si le mot de pass est assez protéger, on le passe dans un dictionnaire.

            //fichier dictionnaire
            $file = "C:\laragon\www\msprLogin\\rockyou.txt";

            //récupération du contenu
            $contents = file_get_contents($file);
            $lines = explode("\n", $contents); 
            
            //pour chaque mot dans le dictionnaire on check si le mdp est le même
            foreach(preg_split("/((\r?\n)|(\r\n?))/", $contents) as $line){
            
            if($line == $pass){
                $iscursed = true;
            }

            } 
            //si oui
            if(!$iscursed){
                $ismdpok = "mot de passe ok";
            }
            //si non
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
      
       
        //récuration du browser par défaut

        //lecture des données utilisateurs
        $file = file_get_contents('brow.json');
        
        //si les données ne sont pas vide
        if($file != ""){

            //on lit
            $data = json_decode($file, true);         
            $class = new Foo();

            foreach ($data as $key => $value) $class->{$key} = $value;

            //construction et envoie du mail de browser
            if($class->NomNavigateur != $browser){
                echo "vous n'etes pas sur votre navigateur habituel...";
                $link = "localhost/logine/index.php?controller=AuthController&action=bypass";
                $title = "Cliquez-ici pour changer votre navigateur par défaut";
                $message = "<a href='". $link. "'>" .$title. "</a>\n\n";
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                mail($jsp, 'Changement de navigateur', "Bonjour, c'est vraiment bizarre vous avez changer de navigateur". "<br><br>" .$message, $headers);
                exit();
            }       
        } 
        
        

        //
        require_once("view/auth/verify.php");
    }
}

    public function index(){
        require_once("view/auth/index.php");
    }

   
     public function bypass(){

        //récupération du navigateur en cours
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

        //remplacement du navigateur utilisateur
        $b = new Foo;
        $b->NomNavigateur = $browser;
      
        file_put_contents('brow.json', json_encode($b));  

        //demande de reconnexion
         $jsp = "";
         require_once("view/auth/index.php");
     }

}

class Foo {

    public $NomNavigateur="";
   

}

?>