<?php

namespace Core\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Sender
{
    private bool $phpMail = true;
    private $mail = null;
    private string $mailHost = 'smtp.ukr.net';
    private string $mailUsername = 'teacher_assistant@ukr.net';
    private string $mailPassword = '1VzjbTlwo0ftccXF';

    private int $mailPort = 465;

    public function sendEmail($email, $subject, $message,$message_txt=null)
    {
        if ($this->phpMail) {
            $this->phpMailer($email,$subject,$message,$message_txt=null);
            return;
        }else{
            mail($email, $subject, $message);
        }
    }

    /**
     * @param string $email
     * @param $subject
     * @param $message
     * @param null $message_txt
     */
    private function phpMailer(string $email, $subject, $message, $message_txt=null)
    {
        try {

            $this->mail = new PHPMailer();
//            $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $this->mail->From = $this->mailUsername;
            $this->mail->FromName = "Teacher Assistant";
            $this->mail->isSMTP();                                            //Send using SMTP
            $this->mail->Host       = $this->mailHost;                     //Set the SMTP server to send through
            $this->mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $this->mail->Username   = $this->mailUsername;                     //SMTP username
            $this->mail->Password   = $this->mailPassword;                               //SMTP password
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $this->mail->Port       = $this->mailPort;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            $this->mail->CharSet    = "utf-8";

            //Recipients
            $this->mail->addAddress($email);
            $this->mail->addReplyTo('filekeeper85@gmail.com', 'TeacherAssistant feedback');

            //Content
            $this->mail->isHTML(true);                                  //Set email format to HTML
            $this->mail->Subject = $subject;
            $this->mail->Body = $message;
            $this->mail->AltBody = $message_txt;

            $this->mail->send();

//            return ['success'=>true];

        }catch(Exception $e){
            echo $this->mail->ErrorInfo;
//            return ['error'=>$this->mail->ErrorInfo];
        }
    }


}