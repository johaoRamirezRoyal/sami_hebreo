<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'reportes' . DS . 'ModeloReportes.php';
require_once CONTROL_PATH . 'hash.php';

class ControlReporte
{

    private static $instancia;

    public static function singleton_reporte()
    {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
}
