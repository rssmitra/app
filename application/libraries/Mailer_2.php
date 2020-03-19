<?php 
//use PHPMailer\POP3;
require_once('PHPMailer/PHPMailerAutoload.php');

class Mailer_2 extends PHPMailer{

    //$pop = POP3::popBeforeSmtp('pop3.example.com', 110, 30, 'username', 'password', 1);

	function sendemail($params){

      date_default_timezone_set('Etc/UTC');
      $link="<a href='http://27.123.223.31:88/rssm/registrasi-online/ws/global_ws?key=".$params."'>Click To Reset password</a>";
     //Create a new PHPMailer instance
     $mail = new PHPMailer;
     //Tell PHPMailer to use SMTP
     $mail->isSMTP();
     //Enable SMTP debugging
     // 0 = off (for production use)
     // 1 = client messages
     // 2 = client and server messages
     $mail->SMTPDebug = 0;
     //Ask for HTML-friendly debug output
     $mail->Debugoutput = 'html';
     //Set the hostname of the mail server
     $mail->Host = "rssetiamitra.co.id";
     //Set the SMTP port number - likely to be 25, 465 or 587
     $mail->Port = 465;
     //Whether to use SMTP authentication
     $mail->SMTPAuth = true;
     $mail->SMTPSecure = 'ssl';

     //Username to use for SMTP authentication
     $mail->Username = "cs@rssetiamitra.co.id";
     //Password to use for SMTP authentication
     $mail->Password = "RssM12312312!";
     //Set who the message is to be sent from
     $mail->setFrom('cs@rssetiamitra.co.id','Rumah Sakit Setia Mitra');
     //Set an alternative reply-to address
     $mail->addReplyTo(''.$params.'');
     //Set who the message is to be sent to
     $mail->addAddress(''.$params.'');
     //Set the subject line
     $mail->Subject = 'Reset Password';
     //Read an HTML message body from an external file, convert referenced images to embedded,
     //convert HTML into a basic plain-text alternative body
     $body = 'Just'.$link;
     $mail->msgHTML($link);
     //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
     //Replace the plain text body with one created manually
     $mail->AltBody = 'This is a plain-text message body';
     //Attach an image file
     //$mail->addAttachment('images/phpmailer_mini.png');

     //send the message, check for errors
     if (!$mail->send()) {
         echo "Mailer Error: " . $mail->ErrorInfo;
         return false;
     } else {
        echo json_encode(array('status' => 200, 'message' => 'Silahkan buka dan periksa inbox email anda beberapa saat lagi (Atau periksa di spam jika memungkinkan)'));
         return true;
     }

	}
}


?>