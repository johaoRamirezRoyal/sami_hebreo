<?php
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
require_once CONTROL_PATH . 'solicitud' . DS . 'ControlSolicitud.php';
require_once CONTROL_PATH . 'areas' . DS . 'ControlAreas.php';
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';

$instancia          = ControlSolicitud::singleton_solicitud();
$instancia_areas    = ControlAreas::singleton_areas();
$instancia_usuarios = ControlUsuarios::singleton_usuarios();

$permisos = $instancia_permiso->permisosUsuarioControl(47, $perfil_log);
if (!$permisos) {
  include_once VISTA_PATH . 'modulos' . DS . '403.php';
  exit();
}

if (isset($_GET['solicitud'])) {

  $id_solicitud     = base64_decode($_GET['solicitud']);
  $datos_solicitud  = $instancia->mostrarDatosSolicitudIdControl($id_solicitud);
  $productos        = $instancia->mostrarProdcutosSolicitudControl($id_solicitud);
  $cotizacion       = $instancia->cotizacionSolicitudControl($id_solicitud);
  $datos_cotizacion = $instancia->mostrarCotizacionControl($id_solicitud);

  $ver_subir_cotizacion    = ($cotizacion['cantidad'] == 0) ? '' : 'd-none';
  $ver_cotizacion_aprobada = ($datos_solicitud['cotizacion'] == 0) ? 'd-none' : '';

  ?>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="card shadow-sm mb-4">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h4 class="m-0 font-weight-bold text-hebreo">
              <a href="<?=BASE_URL?>cotizacion/index" class="text-decoration-none">
                <i class="fa fa-arrow-left text-hebreo"></i>
              </a>
              &nbsp;
              Detalles de solicitud #<?=$id_solicitud?>
            </h4>
          </div>
          <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_log" value="<?=$id_log?>">
            <input type="hidden" name="id_solicitud" value="<?=$datos_solicitud['id']?>">
            <div class="card-body">
              <div class="row">
                <div class="form-group col-lg-12">
                  <h5 class="font-weight-bold text-center text-hebreo">DETALLES DE LA SOLICITUD</h5>
                  <hr>
                </div>
                <div class="form-group col-lg-4">
                  <label class="font-weight-bold">Area</label>
                  <div class="input-group mb-3">
                    <input type="text" class="form-control" disabled aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="<?=$datos_solicitud['area_nom']?>">
                  </div>
                </div>
                <div class="form-group col-lg-4">
                  <label class="font-weight-bold">Grado</label>
                  <div class="input-group mb-3">
                    <input type="text" class="form-control" disabled aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="<?=(empty($datos_solicitud['curso_nom'])) ? 'N/A' : $datos_solicitud['curso_nom']?>">
                  </div>
                </div>
                <div class="form-group col-lg-4">
                  <label class="font-weight-bold">Solicitante</label>
                  <div class="input-group mb-3">
                    <input type="text" class="form-control" disabled aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="<?=$datos_solicitud['nom_usuario']?>">
                  </div>
                </div>
                <div class="form-group col-lg-12 mt-2">
                  <label class="font-weight-bold">Justificacion</label>
                  <textarea class="form-control" disabled><?=$datos_solicitud['justificacion']?></textarea>
                </div>
              </div>
              <div class="table-responsive mt-2">
                <table class="table border table-sm" width="100%" cellspacing="0">
                  <thead>
                    <tr class="text-center font-weight-bold">
                      <th scope="col" colspan="3">Materiales a cotizar</th>
                    </tr>
                    <tr class="text-center font-weight-bold">
                      <th scope="col" class="w-50">Material</th>
                      <th scope="col" class="w-25">Cantidad Solicitada</th>
                    </tr>
                  </thead>
                  <tbody class="buscar">
                    <?php
                    foreach ($productos as $pro) {
                      $id_detalle          = $pro['id'];
                      $nom_producto        = $pro['producto'];
                      $cantidad            = $pro['cantidad'];
                      $cantidad_existencia = $pro['cantidad_existencia'];
                      $existencia          = $pro['existencia'];

                      $cantidad_cotizar = $cantidad - $cantidad_existencia;

                      $ver_producto = ($cantidad_cotizar == 0) ? 'd-none' : '';
                      ?>
                      <tr class="text-center <?=$ver_producto?>">
                        <td><?=$nom_producto?></td>
                        <td><?=$cantidad_cotizar?></td>
                      </tr>
                      <?php
                    }
                    ?>
                  </tbody>
                </table>
              </div>
              <div class="row">
                <div class="form-group col-lg-12 mt-2">
                  <h5 class="font-weight-bold text-center text-hebreo text-uppercase">Proceso Area de compras</h5>
                  <hr>
                </div>
                <div class="form-group col-lg-4">
                  <label class="font-weight-bold">Usuario que reviso</label>
                  <input type="text" class="form-control" disabled value="<?=$datos_solicitud['nom_area_compra']?>">
                </div>
                <div class="form-group col-lg-4">
                  <label class="font-weight-bold">Fecha de revision</label>
                  <input type="text" class="form-control" value="<?=date('Y-m-d', strtotime($datos_solicitud['fecha_revision']))?>" disabled>
                </div>
                <div class="form-group col-lg-12">
                  <label class="font-weight-bold">Observacion de revision</label>
                  <textarea class="form-control" rows="5" disabled><?=$datos_solicitud['observacion_revision']?></textarea>
                </div>
              </div>
              <div class="table-responsive mt-2">
                <table class="table border table-sm" width="100%" cellspacing="0">
                  <thead>
                    <tr class="text-center font-weight-bold">
                      <th scope="col" colspan="3">Materiales listos para entregar</th>
                    </tr>
                    <tr class="text-center font-weight-bold">
                      <th scope="col" class="w-50">Material</th>
                      <th scope="col" class="w-25">Cantidad Solicitada</th>
                    </tr>
                  </thead>
                  <tbody class="buscar">
                    <?php
                    foreach ($productos as $pro) {
                      $id_detalle          = $pro['id'];
                      $nom_producto        = $pro['producto'];
                      $cantidad            = $pro['cantidad'];
                      $cantidad_existencia = $pro['cantidad_existencia'];
                      $existencia          = $pro['existencia'];

                      $cantidad_cotizar  = $cantidad - $cantidad_existencia;
                      $cantidad_entregar = ($cantidad_cotizar == 0) ? $cantidad : $cantidad_cotizar;

                      $ver_producto = ($cantidad_existencia > 0) ? '' : 'd-none';
                      ?>
                      <tr class="text-center <?=$ver_producto?>">
                        <td><?=$nom_producto?></td>
                        <td><?=$cantidad_entregar?></td>
                      </tr>
                      <?php
                    }
                    ?>
                  </tbody>
                </table>
              </div>
              <div class="col-lg-12 form-group mt-4 <?=$ver_cotizacion_aprobada?>">
                <h5 class="font-weight-bold text-center text-hebreo text-uppercase">Cotizaciones / Orden de compra</h5>
                <hr>
              </div>
              <div class="table-responsive mt-2 <?=$ver_cotizacion_aprobada?>">
                <table class="table border table-sm" width="100%" cellspacing="0">
                  <thead>
                    <tr class="text-center font-weight-bold">
                      <th scope="col" class="w-50">Cotizaciones / Orden de compra</th>
                      <th scope="col" class="w-25">Documento</th>
                    </tr>
                  </thead>
                  <tbody class="buscar">
                    <?php
                    $cont = 1;
                    foreach ($datos_cotizacion as $coti) {
                      $id_cotizacion = $coti['id'];
                      $archivo       = $coti['archivo'];
                      $aprobado      = $coti['aprobado'];
                      ?>
                      <tr class="text-center">
                        <td>Documento No. <?=$cont?></td>
                        <td>
                          <div class="btn-group">
                            <a href="<?=PUBLIC_PATH?>upload/<?=$archivo?>" target="_blank" class="btn btn-hebreo btn-sm">
                              <i class="fa fa-eye"></i>
                              &nbsp;
                              Ver cotizacion
                            </a>
                          </div>
                        </td>
                      </tr>
                      <?php
                      $cont++;
                    }
                    ?>
                  </tbody>
                </table>
              </div>
              <div class="row p-2">
                <div class="col-lg-12 form-group <?=$ver_cotizacion_aprobada?>">
                  <label class="font-weight-bold">Observacion de aprobacion de cotizacion</label>
                  <textarea disabled rows="5" class="form-control"><?=$ver_cotizacion_aprobada?></textarea>
                </div>
                <?php
                if (!empty($datos_solicitud['id_pedido'])) {
                  ?>
                  <div class="col-lg-12 form-group mt-2">
                    <h5 class="font-weight-bold text-center text-hebreo text-uppercase">Detalles del pedido</h5>
                    <hr>
                  </div>
                  <div class="col-lg-4 form-group">
                    <label class="font-weight-bold">Quien realiza el pedido</label>
                    <input type="text" class="form-control" disabled value="<?=$datos_solicitud['nom_pedido']?>">
                  </div>
                  <div class="col-lg-4 form-group">
                    <label class="font-weight-bold">Proveedor a quien se realiza el pedido</label>
                    <input type="text" class="form-control" disabled value="<?=$datos_solicitud['nom_proveedor']?>">
                  </div>
                  <div class="col-lg-12 form-group">
                    <label class="font-weight-bold">Obseracion del pedido</label>
                    <textarea class="form-control" disabled rows="5"><?=$datos_solicitud['observacion_pedido']?></textarea>
                  </div>
                <?php }?>
                <div class="col-lg-12 form-group mt-2 <?=$ver_subir_cotizacion?>">
                  <h5 class="font-weight-bold text-center text-hebreo text-uppercase">Cotizacion / Orden de compra</h5>
                  <hr>
                </div>
                <div class="col-lg-6 form-group <?=$ver_subir_cotizacion?>">
                  <label class="font-weight-bold">Cotizaciones u ordenes de compra <span class="text-danger">*</span></label>
                  <input id="file-demo" type="file" class="file" accept=".jpg, .jpeg, .png, .pdf" name="archivo[]" multiple=true data-preview-file-type="any">
                </div>
                <div class="col-lg-12 form-group text-right mt-3 <?=$ver_subir_cotizacion?>">
                  <button class="btn btn-hebreo btn-sm" type="submit">
                    <i class="fa fa-save"></i>
                    &nbsp;
                    Guardar Cotizaciones
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php
  include_once VISTA_PATH . 'script_and_final.php';

  if (isset($_POST['id_log'])) {
    $instancia->subirCotizacionControl();
  }
}