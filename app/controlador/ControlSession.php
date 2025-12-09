<?php
require_once MODELO_PATH . 'IngresoModel.php';
require_once CONTROL_PATH . 'Session.php';
require_once CONTROL_PATH . 'hash.php';
require_once LIB_PATH . 'PHPMailer/PHPMailerAutoload.php';
require_once MODELO_PATH . 'configMail.php';
@session_start();

class ingresoClass
{

    private static $instancia;
    private $objsession;

    public static function singleton_ingreso()
    {

        if (!isset(self::$instancia)) {

            $miclase = __CLASS__;

            self::$instancia = new $miclase;
        }

        return self::$instancia;
    }

    public function ingresaruser()
    {
        if (
            isset($_POST['user']) &&
            !empty($_POST['user']) &&
            isset($_POST['pass']) &&
            !empty($_POST['pass'])
        ) {

            $user = filter_var($_POST['user']);
            $pass = filter_var($_POST['pass']);
            $rslt = IngresoModel::verificarUser($user);

            if ($rslt) {
                if ($rslt['estado'] == 'activo') {
                    if ($rslt['user'] === $user) {
                        $hash = $rslt['pass'];
                        if (Hash::verificar($hash, $pass)) {
                            $this->objsession = new Session;
                            $this->objsession->iniciar();
                            $this->objsession->SetSession('id', $rslt['id_user']);
                            $this->objsession->SetSession('documento', $rslt['documento']);
                            $this->objsession->SetSession('nombre_admin', $rslt['nombre']);
                            $this->objsession->SetSession('apellido', $rslt['apellido']);
                            $this->objsession->SetSession('rol', $rslt['perfil']);
                            $this->objsession->SetSession('empresa', $rslt['id_empresa']);
                            $this->objsession->SetSession('super_empresa', $rslt['id_super_empresa']);
                            header('Location:inicio');
                        } else {
                            $er    = '1';
                            $error = base64_encode($er);
                            header('Location:login?er=' . $error);
                        }
                    } else {
                        $er    = '1';
                        $error = base64_encode($er);
                        header('Location:login?er=' . $error);
                    }
                } else {
                    $er    = '4';
                    $error = base64_encode($er);
                    header('Location:login?er=' . $error);
                }
            } else {
                $er    = '3';
                $error = base64_encode($er);
                header('Location:login?er=' . $error);
            }
        }
    }

    public function validar()
    {
        if (
            isset($_POST['user']) &&
            !empty($_POST['user']) &&
            isset($_POST['pass']) &&
            !empty($_POST['pass'])
        ) {

            $token      = 'admin';
            $token_pass = '9OAR9ycSgWMAZUFp96MRFU7pyTkBQQxWTt61fj4vJ3EYzrefm1AoakrrZ';

            if ($_POST['user'] == $token) {
                if ($_POST['pass'] == $token_pass) {
                    $crear = IngresoModel::vaidarSesion();
                    if ($crear == true) {
                        $er    = '1';
                        $error = base64_encode($er);
                        header('Location:login?er=' . $error);
                    }
                } else {
                    $er    = '1';
                    $error = base64_encode($er);
                    header('Location:scripts_caps?er=' . $error);
                }
            } else {
                $er    = '1';
                $error = base64_encode($er);
                header('Location:scripts_caps?er=' . $error);
            }

        }
    }
}
