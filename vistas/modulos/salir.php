<?php
require_once CONTROL_PATH . 'Session.php';
$salir = new Session;
$salir->iniciar();
$salir->outsession();
header('Location:login');
exit();
