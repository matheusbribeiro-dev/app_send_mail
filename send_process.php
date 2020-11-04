<?php

require_once './librarys/PHPMailer/Exception.php';
require_once './librarys/PHPMailer/OAuth.php';
require_once './librarys/PHPMailer/PHPMailer.php';
require_once './librarys/PHPMailer/POP3.php';
require_once './librarys/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Message
{
	private $email = null;
	private $subject = null;
	private $message = null;

	public function __get($atribute) 
	{
		return $this->$atribute;
	}

	public function __set($atribute, $value) 
	{
		$this->$atribute = $value;
	}

	public function validMessage() 
	{
		if (empty($this->email) or empty($this->subject) or empty($this->message)) {
			return false;
		}

		return true;
	}
}

$message = new Message();
$message->__set('email', $_POST['emailAddress']);
$message->__set('subject', $_POST['subject']);
$message->__set('message', $_POST['message']);

/*----------------------------------*/
if (!$message->validMessage()) {
	echo "Mensagem inválida";
	die();
}
/*----------------------------------*/

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp1.example.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'user@example.com';                     // SMTP username
    $mail->Password   = 'secret';                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->Port       = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('from@example.com', 'Mailer');
    $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
    $mail->addAddress('ellen@example.com');               // Name is optional
    $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');

    // Attachments
    $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}