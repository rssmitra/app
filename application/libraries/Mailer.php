<?php 
require_once('PHPMailer/PHPMailerAutoload.php');

class Mailer extends PHPMailer{

	function sendemail($params){

		date_default_timezone_set('Etc/UTC');
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
        $mail->Host = "smtp.gmail.com";
        //Set the SMTP port number - likely to be 25, 465 or 587
        $mail->Port = 587;
        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = false;

        //Username to use for SMTP authentication
        $mail->Username = "dinan.riqal@gmail.com";
        //Password to use for SMTP authentication
        $mail->Password = "hpbyjwhixxtcspkl";
        //Set who the message is to be sent from
        $mail->setFrom('dinan.riqal@gmail.com', 'Pasar Selon');
        //Set an alternative reply-to address
        $mail->addReplyTo(''.$params['mail'].'', ''.$params['name'].'');
        //Set who the message is to be sent to
        $mail->addAddress(''.$params['mail'].'', ''.$params['name'].'');
        //Set the subject line
        $mail->Subject = 'Thank You For Register as Merchant';
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        $body = '<html>
                    <head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
                      <title>Pasar Selon</title>
                    </head>
                    <body>
                    <center>
	                    <div style="width: 640px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
	                      <h1>Terima Kasih '.ucwords($params['name']).'</h1>
	                      <p>Anda telah melakukan registrasi sebagai merchant di Pasar Selon</p>
	                      <div align="center">
	                        <a href="'.base_url().'assets/img/logo_2.png"><img src="PHPMailer/logo_2.png" height="90" width="340" alt="Pasar Selon"></a>
	                      </div>
	                    </div>
                    </center>
                    </body>
                    </html>
                    ';
        $mail->msgHTML($body);
        //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
        //Replace the plain text body with one created manually
        $mail->AltBody = 'This is a plain-text message body';
        //Attach an image file
        //$mail->addAttachment('images/phpmailer_mini.png');

        //send the message, check for errors
        if (!$mail->send()) {
            //echo "Mailer Error: " . $mail->ErrorInfo;
            return false;
        } else {
            return true;
        }

	}
}


?>