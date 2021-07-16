<?php
	function sendOTP($email,$otp) {
	
		$message_body = "One Time Password for PHP login authentication is:<br/><br/>" . $otp;
		$subject = "OTP to Login";
		$mail = new PHPMailer();
		

	try {	
		//$mail->Send();
		mail($email, $subject, $message_body);
		//echo $mail;

		$result = 1;
		//echo 'Message has been sent';
	} catch (Exception $e) {
		$result = 0;
		//echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}		
		return $result;
	}
?>