<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require "vendor/autoload.php";

class Mailing{
    
    public function sendEmail($name,$email,$session_id,$token)
    {
        $hostApiRest="http://localhost:8000/api/";
        $mail = new PHPMailer(true);
        /** Configurar SMTP **/
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '0192bca8895658';
        $mail->Password = 'df31cc0e9aeb07';
        
        /** Configurar cabeceras del mensaje **/
        $mail->From = 'mike@epayco.com';                   // Correo del remitente
        $mail->FromName = 'Epayco Payments';                        // Nombre del remitente
        $mail->Subject = 'Confirmacion de pago';              // Asunto
    
        /** Incluir destinatarios. El nombre es opcional **/
        $mail->addAddress($email, $name);
        
        /** Enviarlo en formato HTML **/
        $mail->isHTML(true);                                  
        
        /** Configurar cuerpo del mensaje **/
        $mail->Body    = '<span><strong>Hola!, '.$name.'</strong> Confirma tu pago ingresando al siguiente link</span>
        <br>
        <a href="'.$hostApiRest.'confirmPayment?session_id='.$session_id.'&token='.$token.'">Autorizar pago</a>
        <br>
        <br>
        <br>
        <p>Saludos!</p>
        ';
        $mail->AltBody = 'Ingresa al siguiente link para autorizar el pago '.$hostApiRest.'confirmPayment?session_id='.$session_id.'&token='.$token;
        
        /** Para que use el lenguaje español **/
        $mail->setLanguage('es');
        
        /** Enviar mensaje... **/
        if(!$mail->send()) {
            return false;
        } else {
            return true;
        }
        
    }
}


?>