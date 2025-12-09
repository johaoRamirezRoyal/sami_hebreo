<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


require_once CONTROL_PATH . 'Session.php';
$objss = new Session;
$objss->iniciar();
if (!$_SESSION['rol']) {
    $er    = '2';
    $error = base64_encode($er);
    $salir = new Session;
    $salir->iniciar();
    $salir->outsession();
    header('Location:../login?er=' . $error);
    exit();
}
include_once VISTA_PATH . 'cabeza.php';
include_once VISTA_PATH . 'navegacion.php';
require_once CONTROL_PATH . 'salon' . DS . 'ControlSalon.php';

$instancia = ControlSalon::singleton_salon();

$datos_salon = $instancia->mostrarSalonesControl();

$permisos = $instancia_permiso->permisosUsuarioControl(41, $perfil_log);

if (!$permisos) {
    include_once VISTA_PATH . DS . 'modulos' . DS . '403.php';
    exit();
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-hebreo">
                        <a href="<?=BASE_URL?>inicio" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-hebreo"></i>
                        </a>
                        &nbsp;
                        Reservar Salon
                    </h4>
                    <a href="<?=BASE_URL?>salon/disponibilidad" class="btn btn-success btn-sm">
                        <i class="fas fa-clock"></i>
                        &nbsp;
                        Consultar disponibilidad
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="id_log" value="<?=$id_log?>">
                        <div class="row">
                        <div class="form-group col-lg-8">
                                <label class="font-weight-bold">Nombre de la actividad<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nombre_actividad" id="" required>
                            </div>
                            <div class="form-group col-lg-4">
                                <label class="font-weight-bold">Aforo <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="aforo" id="" required>
                            </div>
                            <div class="form-group col-lg-6">
                                <label class="font-weight-bold">Salon <span class="text-danger">*</span></label>
                                <select name="salon" class="form-control" required id="salon">
                                    <option value="" selected="">Selecione una opcion...</option>
                                    <?php
                                    foreach ($datos_salon as $salon) {
                                        $id_salon = $salon['id'];
                                        $nombre   = $salon['nombre'];
                                        $activo   = $salon['activo'];

                                        ?>
                                        <option value="<?=$id_salon?>" class="<?=$ver?>"><?=$nombre?></option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label class="font-weight-bold">Fecha a reservar <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="fecha" id="fecha" required>
                            </div>
                            <div class="form-group col-lg-6">
                                <label class="font-weight-bold">Horas disponibles <span class="text-danger">*</span></label>
                                <div class="hora form-inline">

                                </div>
                            </div>

                          <div class="col-lg-12 form-group">
                              <label class="font-weight-bold">Detalles de la reserva <span class="text-danger">*</span></label>
                              <textarea name="detalles" id="" cols="30" rows="10" class="form-control" required placeholder="Describa todas las necesidades que requiere para el desarrollo de la actividad"></textarea>
                          </div>
                          <div class="form-group col-lg-12">
                            <button class="btn btn-hebreo btn-sm float-right">
                              <i class="fa fa-save"></i>
                              &nbsp;
                              Reservar
                          </button>
                      </div>
                  </div>
              </form>
          </div>
      </div>
  </div>
</div>
</div>
<?php
include_once VISTA_PATH . 'script_and_final.php';

if (isset($_POST['id_log'])) {
    $instancia->reservarSalonControl();
}
?>
<script src="<?=PUBLIC_PATH?>js/salon/funcionesSalon.js"></script>