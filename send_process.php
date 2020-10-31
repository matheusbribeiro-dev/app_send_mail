<?php

require_once './librarys/PHPMailer/Exeception.php';
require_once './librarys/PHPMailer/OAuth.php';
require_once './librarys/PHPMailer/PHPMailer.php';
require_once './librarys/PHPMailer/POP3.php';
require_once './librarys/PHPMailer/SMTP.php';

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


if ($message->validMessage()) {
	echo "Mensagem válida";
} else {
	echo "Mensagem inválida";
}