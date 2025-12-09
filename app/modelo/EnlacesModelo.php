<?php

class EnlacesModelo
{
	public static function DevolverVistaAdmin($enlace)
	{
		$vista = VISTA_PATH . 'modulos' . DS . $enlace . '.php';
		if (!is_readable($vista)) {
			$vista = VISTA_PATH . 'modulos' . DS . '404.php';
		}
		return $vista;
	}
}
