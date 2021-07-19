<?php
class AuthController{

    private $jsp = "";
    private $pass = "";

    public function auth(){
        if(isset($_POST['email']) || isset($_POST['pass'])) {        
            $jsp = $_POST['email'];
            $pass = $_POST['pass'];

            $this->jsp = $jsp;
            $this->pass = $pass;

            echo $this->jsp;
            echo $this->pass;
            //$conn = mysqli_connect("localhost","root","root","otp");
            //Vérif User exist LDAB;

            if($this->sendOTP($this->jsp)){
                require_once("view/auth/otp.php");
            }else verify();
        }
    }

   //envoie OTP par mail
    public function sendOTP($jsp){
        ini_set('memory_limit', '-1');
        
        //génération code OTP
        $otp = rand(100000,999999);

        $message_body = "One Time Password for PHP login authentication is:<br><br>" . $otp;
		$subject = "OTP to Login";

        // envoie mail
        try {	
            mail($jsp, $subject, $message_body);    
            $mail_status = 1;
        } catch (Exception $e) {
            $mail_status = 0;
        }

        //si mail envoyé
        if($mail_status == 1) {
            
            //insertion pour vérification ultérieur de l'OTP
            $q = "INSERT INTO otp_expiry(id, otp, is_expired, create_at) VALUES ('" . $otp . "', '" . $otp . "', 0,  CAST(N'".date("Y-m-d H:i:s")."' AS DateTime))";
            $conn = mysqli_connect("localhost","root","root","otp");            
            $result = $conn->query($q);		

            //si l'insert à marché, succés
            if(!empty($result)) {                
                return true;
            } 
        }
        return false;     
    }

    //vérificateur du code OTP
    public function checkOTP(){           
        $jsp = $_POST['email'];
        $pass = $_POST['pass'];
        //echo "checkOtp : ".$jsp;
        //Si on trouve un code
        if(!empty($_POST["submit_otp"])) {
            //requêtes -> vérification du code en BDD
            $q = "SELECT * FROM otp_expiry WHERE otp='" . $_POST["otp"] . "' AND is_expired!=1 AND NOW() <= DATE_ADD(create_at, INTERVAL 24 HOUR)";   
            $conn = mysqli_connect("localhost","root","root","otp");
            $result = mysqli_query($conn, $q);
            $count  = mysqli_num_rows($result);
            //si la requêtes retourne quelque chose
            if(!empty($count)) {
                //succés
                $result = mysqli_query($conn,"UPDATE otp_expiry SET is_expired = 1 WHERE otp = '" . $_POST["otp"] . "'");
                
                $this->verify();
            } else {
                //échec
                //$success =1;
                $error_message = "Invalid OTP!";               
                require_once("view/auth/otp.php");
                return false;
            }	
        }
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

    //Vérification Principale
    public function verify(){        
        $jsp = $_POST['email'];
        $pass = $_POST['pass'];        
        //echo "username : ". $jsp;
        //echo "pass : ".$pass;  
        //echo "username : ". $this->jsp;
        //echo "pass : ".$this->pass;   
        //if($this->jsp != "" && $this->pass != "") {        
            //$jsp = $this->jsp;
            //$pass = $this->pass;


            //Vérif User exist LDAB;
            $ismdpok = "";
            $iscursed = false;
            ini_set('memory_limit', '-1');
                
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
                require_once("view/auth/verify.php");
            }
        //}
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