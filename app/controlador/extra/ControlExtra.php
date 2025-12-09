<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'extra' . DS . 'ModeloExtra.php';

class ControlExtra
{

    private static $instancia;

    public static function singleton_extra()
    {
        if (!isset(self::$instancia)) {
            $miclase         = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    public function mostrarDatosExtraControl()
    {
        $mostrar = ModeloExtra::mostrarDatosExtraModel();
        return $mostrar;
    }
}
