<?php
require_once CONTROL_PATH . 'EnlacesControl.php';
require_once MODELO_PATH . 'EnlacesModelo.php';
$paginas = new EnlacesControl();
$paginas->EnlacesPaginas();
?>
