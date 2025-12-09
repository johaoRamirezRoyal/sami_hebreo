<?php
date_default_timezone_set('America/Bogota');
require_once MODELO_PATH . 'categorias' . DS . 'ModeloCategorias.php';
require_once CONTROL_PATH . 'hash.php';

class ControlCategorias
{

    private static $instancia;

    public static function singleton_categorias()
    {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }


    public function mostrarCategoriasControl($super_empresa)
    {
        $mostrar = ModeloCategorias::mostrarCategoriasModel($super_empresa);
        return $mostrar;
    }
}
