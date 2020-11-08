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
        /*The status about return aplication*/
        public $status = [
            'status_code' => null,
            'status_description' => ''
        ];

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
    $message->__set('message', utf8_encode($_POST['message']));

    /*----------------------------------*/
    if (!$message->validMessage()) {
        echo "Mensagem inválida";
        header('Location: index.php');
    }
    /*----------------------------------*/

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = false; //SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'your@email.com';                     // SMTP username
        $mail->Password   = 'secret';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port       = 587;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('your@email', 'name');
        $mail->addAddress($message->__get('email'));     // Add a recipient
        //$mail->addAddress('ellen@example.com');               // Name is optional
        //  $mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        // Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $message->__get('subject');
        $mail->Body    = $message->__get('message');
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();

        //The status about return aplication
        $message->status['status_code'] = 1;
        $message->status['status_description'] = 'Email enviado com sucesso !';

    } catch (Exception $e) {
        $message->status['status_code'] = 2;
        $message->status['status_description'] = 'Erro no envio. Por favor tente novamente mais tarde !.
        Detalhes do erro: '.$mail->ErrorInfo;
    }
?>

<html>
    <head>
        <meta charset="utf-8" />
    	<title>App Mail Send</title>

    	<link rel="stylesheet" 
    		href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
    		integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
            crossorigin="anonymous"
        >
    </head>
    <body>
        <!-- container -->
        <div class="container">
            <!-- page body title -->
            <div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>
            <!-- / page body title -->

            <!-- row content -->
            <div class="row">
                <div class="col-md-12">
                    <!-- Back end return -->
                    <?php if($message->status['status_code'] == 1): ?>
                        <div class="container">
                            <h1 class="display-4 text-success">Sucesso</h1>
                            <p><?= $message->status['status_description'] ?></p>
                            <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">
                                Voltar
                            </a>
                        </div>
                    <?php endif ?>

                    <?php if($message->status['status_code'] == 2): ?>
                        <div class="container">
                            <h1 class="display-4 text-danger">Ops!</h1>
                            <p><?= $message->status['status_description'] ?></p>
                            <a href="index.php" class="btn btn-danger btn-lg mt-5 text-white">
                                Voltar
                            </a>
                        </div>
                    <?php endif ?>
                </div>
            </div>
            <!-- / row content -->
        </div>
        <!-- / container -->
    </body>
</html>