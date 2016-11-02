<?php
    

    class Support_page extends MY_Controller {
        function __construct()
        {
            parent::__construct();
            $this->modulename = 'Support Page';
            $this->moduleid = 6;

            //$this->load->helper('PHPMailerAutoload');
        }
        public function index()
        {
            //$this->load->view('support_page/email_form');
            $this->render('support_page/email_form');

        }
        public function send_mail()
        {
            date_default_timezone_set('Asia/Manila');
            //$this->load->helper('PHPMailerAutoload');
            require '/var/www/html/nmp-emt/assets/PHPMailerAutoload.php';  
            //Create a new PHPMailer instance
            $mail = new PHPMailer;
            
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
            $mail->SMTPSecure = 'tls'; 
            //Set the SMTP port number - likely to be 25, 465 or 587
            $mail->Port = 587;
            //Whether to use SMTP authentication
            $mail->SMTPAuth = true;
            //Username to use for SMTP authentication
            $mail->Username = "co.tolentino@gmail.com";
            //Password to use for SMTPAuth authentication
            $mail->Password = "coat00326";
            //Set who the message is to be sent from
            $mail->setFrom('co.tolentino@gmail.com', 'pogi');
            //Set an alternative reply-to address
            //mail->addReplyTo('replyto@example.com', 'First Last');
            //Set who the message is to be sent to
            $to_email = $this->input->post('email');
            $sender_name = $this->input->post('name');
            $mail->addAddress($to_email, $sender_name);
            //Set the subject line
            $email_subject = $this->input->post('email_subject');
            $mail->Subject =$email_subject; 
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
            //Replace the plain text body with one created manually
            //$mail->AltBody = 'This is a plain-text message body';

            $email_body = $this->input->post('email_body');
            $mail->Body = $email_body;
            //Attach an image file
            //$mail->addAttachment('images/phpmailer_mini.png');
            //send the message, check for errors
            if (!$mail->send()) {
                show_error($mail->ErrorInfo);
            } else {
                $this->session->set_flashdata("email_sent","Email sent successfully. We'll get back to you shortly. ");
            }

            $this->render('support_page/email_form');
        }
   }
?>

