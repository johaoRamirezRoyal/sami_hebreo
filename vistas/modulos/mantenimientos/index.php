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
require_once CONTROL_PATH . 'inventario' . DS . 'ControlInventario.php';
require_once CONTROL_PATH . 'areas' . DS . 'ControlAreas.php';
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';

$instancia          = ControlInventario::singleton_inventario();
$instancia_areas    = ControlAreas::singleton_areas();
$instancia_usuarios = ControlUsuarios::singleton_usuarios();

$datos_areas   = $instancia_areas->mostrarAreasControl($id_super_empresa);
$datos_usuario = $instancia_usuarios->mostrarTodosUsuariosControl();
$datos_categorias = $instancia->mostrarCategoriasControl($id_super_empresa);

if (isset($_POST['buscar'])) {
    $datos            = array('area' => $_POST['area'], 'usuario' => $_POST['usuario'], 'buscar' => $_POST['buscar']);
    $datos_inventario = $instancia->mostrarDatosEquipoComputoControl($datos);
} else {
    $datos_inventario = $instancia->mostrarEquiposEnMantenimientoPreventivoControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl(10, $perfil_log);

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
                    <h4 class="m-0 font-weight-bold text-primary">
                        <a href="<?= BASE_URL ?>inicio" class="text-decoration-none">
                            <i class="fa fa-arrow-left text-primary"></i>
                        </a>
                        &nbsp;
                        Mantenimientos preventivos
                    </h4>
                    <div class="btn-group">

                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#programar_mant">

                            <i class="fas fa-calendar-alt"></i>

                            &nbsp;

                            Programar Mantenimientos

                        </button>

                        


                        <a href="<?= BASE_URL ?>mantenimientos/historial_mantenimiento" class="btn btn-primary btn-sm">
                            <!--
                                 <i class="fa fa-plus"></i>
                            -->
                            &nbsp;

                            Historial mantenimiento

                        </a>

                    </div>
                    <!--
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(17px, 19px, 0px);">
                            <div class="dropdown-header">Acciones:</div>
                            <a class="dropdown-item" href="<?= BASE_URL ?>mantenimientos/areas">Mantenimientos areas</a>
                        </div>
                    </div>
                    -->
                </div>
                <div class="card-body">
                    
                    <div class="table-responsive mt-2">
                        <table class="table table-hover text-uppercase border table-sm" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center font-weight-bold">
                                    <th scope="col">ID</th>
                                    <th scope="col">Usuario</th>
                                    <th scope="col">Area</th>
                                    <th scope="col">Descripcion</th>
                                    <th scope="col">Marca</th>
                                    <th scope="col">Modelo</th>
                                    <th scope="col">Codigo</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="buscar text-uppercase">
                                <?php
                                foreach ($datos_inventario as $inventario) {
                                    $id_inventario    = $inventario['id_inventario'];
                                    $id_reporte       = $inventario['id']; 
                                    $descripcion      = $inventario['descripcion'];
                                    $marca            = $inventario['marca'];
                                    $modelo           = $inventario['modelo'];
                                    $codigo           = ($inventario['codigo'] == '') ? $id_inventario : $inventario['codigo'];
                                    $usuario          = $inventario['nom_user'];
                                    $area             = $inventario['nom_area'];
                                    $estado           = $inventario['estado'];
                                    $observacion      = $inventario['observacion'];
                                    $id_user          = $inventario['id_user'];
                                    $id_area          = $inventario['id_area'];
                                    $id_categoria     = $inventario['id_categoria'];

                                    /*
                                    $fecha      = date('Y-m', strtotime($inventario['ultimo_mant']));
                                    $nuevafecha = strtotime('+' . $frecuencia . ' month', strtotime($fecha));
                                    $nuevafecha = date('Y-m', $nuevafecha);
                                    */
                                    if ($id_categoria == 1) {
                                        $hoja_vida = '<a href="' . BASE_URL . 'hoja_vida/index?inventario=' . base64_encode($id_inventario) . '">' . $descripcion . '</a>';
                                    } else {
                                        $hoja_vida = $descripcion;
                                    }

                                    if ($estado == 3 || $estado == 1) {
                                        $visible_descargar = 'd-none';
                                        $visible_mant      = '';
                                    } else {
                                        $visible_mant      = 'd-none';
                                        $visible_descargar = '';
                                    }

                                    if ($estado != 5) {
                                ?>
                                        <tr class="text-center">
                                            <td><?= $id_inventario ?></td>
                                            <td><?= $usuario ?></td>
                                            <td><?= $area ?></td>
                                            <td><?= $hoja_vida ?></td>
                                            <td><?= $marca ?></td>
                                            <td><?= $modelo ?></td>
                                            <td><?= $codigo ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <form method="post">
                                                        <input type="hidden" name="id_log" value="<?= $id_log ?>">
                                                        <input type="hidden" name="id_inventario" value="<?= $id_inventario ?>">
                                                        <input type="hidden" name="id_user" value="<?= $id_user ?>">
                                                        <input type="hidden" name="id_area" value="<?= $id_area ?>">
                                                        <input type="hidden" name="id_reporte" value="<?= $id_reporte ?>">
                                                        <input type="hidden" name="fecha_respuesta" value="<?php echo date('Y-m-d H:i:s'); ?>">
                                                        <button class="btn btn-success btn-sm" data-tooltip="tooltip" data-placement="bottom" title="Solucionar mantenimiento" name="solucionar">
                                                            <i class="fa fa-clock"></i>
                                                        </button>
                                                    </form>
                                                    <button class="btn btn-info btn-sm" data-tooltip="tooltip" data-placement="bottom" title="Realizar copia seguridad" data-toggle="modal" data-target="#copia<?= $id_inventario ?>">
                                                        <i class="fas fa-clone"></i>
                                                    </button>
                                                    <a href="<?= BASE_URL ?>imprimir/reporte?inventario=<?= base64_encode($id_inventario) ?>" target="_blank" class="btn btn-primary btn-sm <?= $visible_descargar ?>" data-tooltip="tooltip" data-placement="bottom" title="Descargar reporte">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>


                                        <!-- Mantenimiento inventario -->
                                        <div class="modal fade" id="mant_inv<?= $id_inventario ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Reportar mantenimiento articulo</h5>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="modal-body border-0">
                                                            <div class="row p-2">
                                                                <input type="hidden" value="<?= $id_super_empresa ?>" name="id_super_empresa">
                                                                <input type="hidden" value="<?= $id_inventario ?>" name="id_inventario_mant">
                                                                <input type="hidden" value="<?= $id_log ?>" name="id_log_mant">
                                                                <input type="hidden" value="<?= $id_user ?>" name="id_user_mant">
                                                                <input type="hidden" value="<?= $id_area ?>" name="id_area_mant">
                                                                <div class="form-group col-lg-6">
                                                                    <label class="font-weight-bold">Area</label>
                                                                    <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?= $area ?>">
                                                                </div>
                                                                <div class="form-group col-lg-6">
                                                                    <label class="font-weight-bold">Descripcion</label>
                                                                    <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?= $descripcion ?>">
                                                                </div>
                                                                <div class="form-group col-lg-6">
                                                                    <label class="font-weight-bold">Marca</label>
                                                                    <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?= $marca ?>">
                                                                </div>
                                                                <div class="form-group col-lg-6">
                                                                    <label class="font-weight-bold">Modelo</label>
                                                                    <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?= $modelo ?>">
                                                                </div>
                                                                <div class="form-group col-lg-6">
                                                                    <label class="font-weight-bold">Responsable</label>
                                                                    <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?= $usuario ?>">
                                                                </div>
                                                                <div class="form-group col-lg-6">
                                                                    <label class="font-weight-bold">No. Serie</label>
                                                                    <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?= $codigo ?>">
                                                                </div>
                                                                <div class="form-group col-lg-12">
                                                                    <label class="font-weight-bold">Fecha</label>
                                                                    <input type="date" name="fecha" class="form-control" required value="<?= date("Y-m-d"); ?>">
                                                                </div>
                                                                <div class="form-group col-lg-12">
                                                                    <label class="font-weight-bold">Observacion</label>
                                                                    <textarea name="observacion" class="form-control" maxlength="1000" cols="30" rows="5"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0">
                                                            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                                                                <i class="fa fa-times"></i>
                                                                &nbsp;
                                                                Cerrar
                                                            </button>
                                                            <button type="submit" class="btn btn-success btn-sm">
                                                                <i class="fas fa-wrench"></i>
                                                                &nbsp;
                                                                Mantenimiento
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="modal fade" id="copia<?= $id_inventario ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Copia de seguridad articulo</h5>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="modal-body border-0">
                                                            <div class="row p-2">
                                                                <input type="hidden" value="<?= $id_inventario ?>" name="id_inventario_copia">
                                                                <input type="hidden" value="<?= $id_log ?>" name="id_log_copia">
                                                                <input type="hidden" value="<?= $id_user ?>" name="id_user_copia">
                                                                <input type="hidden" value="<?= $id_area ?>" name="id_area_copia">
                                                                <div class="form-group col-lg-6">
                                                                    <label class="font-weight-bold">Area</label>
                                                                    <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?= $area ?>">
                                                                </div>
                                                                <div class="form-group col-lg-6">
                                                                    <label class="font-weight-bold">Descripcion</label>
                                                                    <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?= $descripcion ?>">
                                                                </div>
                                                                <div class="form-group col-lg-6">
                                                                    <label class="font-weight-bold">Marca</label>
                                                                    <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?= $marca ?>">
                                                                </div>
                                                                <div class="form-group col-lg-6">
                                                                    <label class="font-weight-bold">Modelo</label>
                                                                    <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?= $modelo ?>">
                                                                </div>
                                                                <div class="form-group col-lg-6">
                                                                    <label class="font-weight-bold">Responsable</label>
                                                                    <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?= $usuario ?>">
                                                                </div>
                                                                <div class="form-group col-lg-6">
                                                                    <label class="font-weight-bold">No. Serie</label>
                                                                    <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?= $codigo ?>">
                                                                </div>
                                                                <div class="form-group col-lg-12">
                                                                    <label class="font-weight-bold">Fecha</label>
                                                                    <input type="date" name="fecha" class="form-control" required value="<?= date("Y-m-d"); ?>">
                                                                </div>
                                                                <div class="form-group col-lg-12">
                                                                    <label class="font-weight-bold">Observacion</label>
                                                                    <textarea name="observacion" class="form-control" maxlength="1000" cols="30" rows="5"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0">
                                                            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                                                                <i class="fa fa-times"></i>
                                                                &nbsp;
                                                                Cerrar
                                                            </button>
                                                            <button type="submit" class="btn btn-primary btn-sm">
                                                                <i class="fas fa-clone"></i>
                                                                &nbsp;
                                                                Registrar copia
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!------------------------------------------------------->


                                        <!-- Mantenimiento inventario -->
                                        <div class="modal fade" id="mant_pro<?= $id_inventario ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Programar mantenimiento articulo</h5>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="modal-body border-0">
                                                            <div class="row p-2">
                                                                <input type="hidden" value="<?= $id_super_empresa ?>" name="id_super_empresa">
                                                                <input type="hidden" value="<?= $id_inventario ?>" name="id_inventario">
                                                                <input type="hidden" value="<?= $id_log ?>" name="id_log">
                                                                <input type="hidden" value="<?= $id_user ?>" name="id_user">
                                                                <input type="hidden" value="<?= $id_area ?>" name="id_area">
                                                                <div class="form-group col-lg-6">
                                                                    <label class="font-weight-bold">Area</label>
                                                                    <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?= $area ?>">
                                                                </div>
                                                                <div class="form-group col-lg-6">
                                                                    <label class="font-weight-bold">Descripcion</label>
                                                                    <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?= $descripcion ?>">
                                                                </div>
                                                                <div class="form-group col-lg-6">
                                                                    <label class="font-weight-bold">Marca</label>
                                                                    <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?= $marca ?>">
                                                                </div>
                                                                <div class="form-group col-lg-6">
                                                                    <label class="font-weight-bold">Modelo</label>
                                                                    <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?= $modelo ?>">
                                                                </div>
                                                                <div class="form-group col-lg-6">
                                                                    <label class="font-weight-bold">Responsable</label>
                                                                    <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?= $usuario ?>">
                                                                </div>
                                                                <div class="form-group col-lg-6">
                                                                    <label class="font-weight-bold">No. Serie</label>
                                                                    <input type="text" class="form-control letras" disabled maxlength="50" minlength="1" value="<?= $codigo ?>">
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <label class="font-weight-bold">Frecuencia mantenimiento (Meses)</label>
                                                                    <input type="text" name="frec_mant" class="form-control numeros" maxlength="2" minlength="1" required value="<?= $frecuencia; ?>">
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <label class="font-weight-bold">Frecuencia copia de seguridad (Meses)</label>
                                                                    <input type="text" name="frec_mant" class="form-control numeros" maxlength="2" minlength="1" required value="<?= $frecuencia_copia; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0">
                                                            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                                                                <i class="fa fa-times"></i>
                                                                &nbsp;
                                                                Cerrar
                                                            </button>
                                                            <button type="submit" class="btn btn-success btn-sm">
                                                                <i class="fas fa-redo-alt"></i>
                                                                &nbsp;
                                                                Actualizar
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!------------------------------------------------------->
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
include_once VISTA_PATH . 'script_and_final.php';
include_once VISTA_PATH . 'modulos' . DS . 'mantenimientos' . DS . 'programarMant.php';
?>
<script src="<?=PUBLIC_PATH?>js/mantenimientos/funcionesMantenimientos.js"></script>

<?php
if (isset($_POST['mantenimiento_solucion'])) {
    $instancia->programarMantenimientosHebreoControl();
}

if (isset($_POST['reporte_preventivo'])) {
    $instancia->programarMantenimientosSinSolucionControl();
}

if(isset($_POST['solucionar'])){
    $instancia->confirmarMantenimientoInventarioControl();
}

//========= FUNCIONES ANTIGUAS =========

/*
if (isset($_POST['id_inventario_mant'])) {
    $instancia->mantenimientoArticuloControl();
}

if (isset($_POST['id_inventario'])) {
    $instancia->programarMantenimientoArticuloControl();
}

if (isset($_POST['id_inventario_copia'])) {
    $instancia->copiaSeguridadArticuloControl();
}

if(isset($_POST['mant_sistemas'])){
    $instancia->programarMantenimientosControl();
}
    */
