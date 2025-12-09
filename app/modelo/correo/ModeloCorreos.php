<?php
require_once LIB_PATH . 'PHPMailer/PHPMailerAutoload.php';
require_once MODELO_PATH . 'configMail.php';

class Correo extends PHPMailer
{

    public static function enviarCorreoModel($datos)
    {

        $mail = new Correo();

        $mail->IsSMTP();
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = SMTP;
        $mail->Host       = SERVER;
        $mail->Port       = PORT;

        $mail->Username = USER;
        $mail->Password = PASS;

        $mail->From     = USER;
        $mail->FromName = NOMBRE;

        $mail->Subject = $datos['asunto'];

        $mail->MsgHTML($datos['mensaje']);

        foreach ($datos['correo'] as $email) {
            $mail->AddAddress($email);
        }

        foreach ($datos['archivo'] as $archivos) {
            if ($archivos != '') {
                $url = PUBLIC_PATH . 'upload' . DS . $archivos;

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                $fichero = curl_exec($ch);
                curl_close($ch);

                $mail->addStringAttachment($fichero, $archivos);
            }
        }

        if (!$mail->send()) {
            return false;
        } else {
            return true;
        }
    }
}
