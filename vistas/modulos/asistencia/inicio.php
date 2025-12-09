<?php
date_default_timezone_set('America/Bogota');
include_once VISTA_PATH . 'cabeza.php';
require_once CONTROL_PATH . 'asistencia' . DS . 'ControlAsistencia.php';

$instancia = ControlAsistencia::singleton_asistencia();

if (isset($_GET['token'])) {

  $token = $instancia->validarTokenControl($_GET['token']);

  if ($token != 'No') {
    ?>
    <div class="container-fluid">
      <div class="row mt-2">
        <div class="col-lg-3"></div>
        <div class="col-lg-6 mt-1">
          <div class="card shadow-sm mb-4">
            <div class="card-body">
              <div class="row p-2">
                <div class="input-group mb-3">
                  <input type="text" class="form-control" id="documento" placeholder="Documento" aria-label="Recipient's username" aria-describedby="basic-addon2">
                  <div class="input-group-append">
                    <button type="button" class="btn btn-primary btn-sm" id="buscar">
                     <i class="fa fa-search"></i>
                     &nbsp;
                     Buscar
                   </button>
                 </div>
               </div>
             </div>
           </div>
         </div>
       </div>
     </div>
     <div class="row">
      <div class="col-lg-3"></div>
      <div class="col-lg-6 alerta_tomada d-none">
        <div class="card shadow-lg bg-success border-0">
          <div class="card-body text-center text-white p-4">
            <h3>Asistencia Tomada</h3>
            <h4 class="mt-4"><?=date('Y-m-d')?></h4>
          </div>
        </div>
      </div>
      <div class="col-lg-6 alerta_ya_tomada d-none">
        <div class="card shadow-lg bg-danger border-0">
          <div class="card-body text-center text-white p-4">
            <h3>Asistencia Ya Tomada</h3>
            <h4 class="mt-4"><?=date('Y-m-d')?></h4>
          </div>
        </div>
      </div>
      <div class="col-lg-6 alerta_documento d-none">
        <div class="card shadow-lg bg-danger border-0">
          <div class="card-body text-center text-white p-4">
            <h4 class="font-weigth-bold text-uppercase">El documento no se encuentra registrado en S.A.M.I</h4>
            <h5 class="mt-4">Favor Comunicarse con el area de sistemas.</h5>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php
} else {
  ?>
  <div class="container-fluid">
   <div class="row">
    <div class="col-lg-3"></div>
    <div class="col-lg-6">
      <div class="card shadow-lg bg-danger border-0">
        <div class="card-body text-center text-white p-4">
          <h3>Codigo no corresponde al dia</h3>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid">
    <?php
  }
}
include_once VISTA_PATH . 'script_and_final.php';
?>
<script>
  $(".loader").hide();
</script>
<script src="<?=PUBLIC_PATH?>js/asistencia/funcionesDocumento.js"></script>