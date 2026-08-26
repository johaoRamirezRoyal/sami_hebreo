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
require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';
require_once CONTROL_PATH . 'areas' . DS . 'ControlAreas.php';

$instancia         = ControlInventario::singleton_inventario();
$instancia_usuario = ControlUsuarios::singleton_usuarios();
$instancia_areas   = ControlAreas::singleton_areas();

$datos_usuario   = $instancia_usuario->mostrarTodosUsuariosControl();
$datos_areas     = $instancia_areas->mostrarAreasControl($id_super_empresa);
$datos_categoria = $instancia->mostrarCategoriasControl($id_super_empresa);

if (isset($_POST['area_buscar'])) {

    $area     = $_POST['area_buscar'];
    $usuario  = $_POST['usuario_buscar'];
    $articulo = $_POST['articulo'];

    $datos = array(
        'area'     => $area,
        'usuario'  => $usuario,
        'articulo' => $_POST['articulo'],
    );

    $datos_articulo = $instancia->buscarInventarioDetalleControl($datos);
} else {

    $area     = '';
    $usuario  = '';
    $articulo = '';

    $datos_articulo = $instancia->mostrarInventarioDetalleControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl(12, $perfil_log);
if (!$permisos) {
    include_once VISTA_PATH . 'modulos' . DS . '403.php';
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
                        Listado
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-lg-4 form-group">
                                <select name="area_buscar" id="" class="form-control select2" data-tooltip="tooltip" title="Area">
                                    <option value="" selected>Seleccione un area...</option>
                                    <?php
                                    foreach ($datos_areas as $areas) {
                                        $id_area     = $areas['id'];
                                        $nombre_area = $areas['nombre'];

                                        $ver      = ($areas['activo'] == 0) ? 'd-none' : '';
                                        $selected = ($id_area == $area) ? 'selected' : '';
                                    ?>
                                        <option value="<?= $id_area ?>" class="<?= $ver ?> text-uppercase" <?= $selected ?>><?= $nombre_area ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-4 form-group">
                                <select name="usuario_buscar" id="" class="form-control select2" data-tooltip="tooltip" title="Usuario">
                                    <option value="" selected>Seleccione una opcion...</option>
                                    <?php
                                    foreach ($datos_usuario as $usuarios) {
                                        $id_usuario  = $usuarios['id_user'];
                                        $nombre_user = $usuarios['nom_user'];

                                        $ver      = ($areas['estado'] == 'inactivo') ? 'd-none' : '';
                                        $selected = ($id_usuario == $usuario) ? 'selected' : '';
                                    ?>
                                        <option value="<?= $id_usuario ?>" class="<?= $ver ?> text-uppercase" <?= $selected ?>><?= $nombre_user ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-4 form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control filtro" placeholder="Buscar" aria-describedby="basic-addon2" name="articulo" value="<?= $articulo ?>">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary btn-sm" type="submit">
                                            <i class="fa fa-search"></i>
                                            &nbsp;
                                            Buscar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="col-lg-12 text-left">
                        <form id="form-hoja-vida">
                            <div class="col-lg-4 input-group">
                                <input type="text" class="form-control filtro" placeholder="Buscar" name="buscar_id" id="buscar_id">
                                <div class="input-group-append">
                                    <button class="btn btn-primary btn-sm" type="submit">
                                        <i class="fa fa-search"></i> Hoja de vida
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive mt-2">
                        <table class="table table-hover border table-sm" width="100%" cellspacing="0">
                            <thead>
                                <tr class="text-center font-weight-bold">
                                    <th scope="col">USUARIO</th>
                                    <th scope="col">AREA</th>
                                    <th scope="col">DESCRIPCION</th>
                                    <th scope="col">MARCA</th>
                                    <th scope="col">CANTIDAD</th>
                                    <th scope="col">Inspeccionar</th>
                                    <th scope="col">Editar</th>
                                </tr>
                            </thead>
                            <tbody class="buscar text-uppercase">
                                <?php
                                $url = base64_encode('1');
                                foreach ($datos_articulo as $inventario) {
                                    $id_inventario = $inventario['id'];
                                    $descripcion   = $inventario['descripcion'];
                                    $marca         = $inventario['marca'];
                                    $modelo        = $inventario['modelo'];
                                    $usuario       = $inventario['nom_user'];
                                    $area          = $inventario['nom_area'];
                                    $estado        = $inventario['estado'];
                                    $observacion   = $inventario['observacion'];
                                    $id_user       = $inventario['id_user'];
                                    $id_area       = $inventario['id_area'];
                                    $precio        = $inventario['precio'];
                                    $cantidad      = $inventario['cantidad'];

                                    $codigo = ($inventario['codigo'] == '') ? $inventario['id'] : $inventario['codigo'];
                                    $hoja_vida = $descripcion;
                                    $ver             = ($estado == 5 || $inventario['confirmado'] == 2) ? 'd-none' : '';
                                    //

                                ?>
                                    <tr class="text-center <?= $ver ?>" data-descripcion="<?= $descripcion ?>" data-id-user="<?= $id_user ?>" data-id-area="<?= $id_area ?>" data-cantidad="<?= $cantidad ?>">
                                        <td><?= $usuario ?></td>
                                        <td><?= $area ?></td>
                                        <td>
                                            <span class="span-desc"><?= $hoja_vida ?></span>
                                            <input type="text" class="form-control form-control-sm inp-desc d-none" value="<?= $hoja_vida ?>">
                                        </td>
                                        <td><?= $marca ?></td>
                                        <td>
                                            <span class="span-cant"><?= $cantidad ?></span>
                                            <input type="number" class="form-control form-control-sm inp-cant d-none" value="<?= $cantidad ?>" min="0">
                                        </td>
                                        <!-- Boton de span confirmado para mostrar el estado del inventario! -->
                                        <!-- <td><?= $span_confirmado ?></td> -->
                                        <td>
                                            <div class="align-items-center">
                                                <button class="btn btn-success btn-sm" type="button" data-toggle="modal" data-target="#modalInventario_<?= $id_inventario ?>" data-id="<?= base64_encode($id_inventario) ?>">
                                                    Inspeccionar
                                                </button>
                                            </div>

                                            <!-- Model para ver los elementos del inventario de manera individual -->
                                            <div class="modal fade" id="modalInventario_<?= $id_inventario ?>" tabindex="-1" role="dialog" aria-labelledby="modalInventario">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h2 class="modal-tittle font-weight-bold" id="modalTitulo">Inventario: <?= $descripcion ?></h2>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="table-responsive mt-2">
                                                                <table class="table table-hover border table-sm" width="100%" cellspacing="1">
                                                                    <thead>
                                                                        <tr class="text-center font-weight-bold">
                                                                            <th>ID inventario</th>
                                                                            <th>Descripción</th>
                                                                            <th>Usuario</th>
                                                                            <th>Area</th>
                                                                            <th>Estado</th>
                                                                            <th>Historial</th>
                                                                            <th>Acciones</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $datos_inventario = $instancia->obtenerInventarioDesagrupadoControl($descripcion, $id_area, $id_user);
                                                                        foreach ($datos_inventario as $dato) {

                                                                            $id_inventario_modal = $dato['id'];
                                                                            $descripcion_modal = $dato['descripcion'];
                                                                            $marca_modal = $dato['marca'];
                                                                            $modelo_modal = $dato['modelo'];
                                                                            $usuario_modal = $dato['nom_user'];
                                                                            $area_modal = $dato['nom_area'];
                                                                            $estado_modal = $dato['estado'];
                                                                            $estado_nombre = $dato['estado_nombre'];
                                                                            $observacion_modal = $dato['observacion'];
                                                                            $id_user_modal = $dato['id_user'];
                                                                            $id_area_modal = $dato['id_area'];
                                                                            $precio_modal = $dato['precio'];
                                                                            $cantidad_modal = $dato['cantidad'];
                                                                            $span_confirmado_modal = '<span class="badge badge-success">Asignado</span>';
                                                                            $reporte_inv = ($instancia->obtenerReporteDeInventarioControl($id_inventario_modal) != null) ? $instancia->obtenerReporteDeInventarioControl($id_inventario_modal) : array('id' => '');
                                                                            $id_reporte_inv = $reporte_inv['id'];
                                                                            if ($dato['estado'] != 1) {
                                                                                if ($dato['estado'] == 2) {
                                                                                    $span_confirmado_modal = '<span class="badge badge-warning">Mantenimiento correctivo</span>';
                                                                                } elseif ($dato['estado'] == 3) {
                                                                                    $span_confirmado_modal = '<span class="badge badge-info">Solucionado</span>';
                                                                                } else {
                                                                                    $span_confirmado_modal = '<span class="badge badge-info">' . $estado_nombre . '</span>';
                                                                                }
                                                                            }
                                                                            if ($estado_modal == 6 || $estado_modal == 2) {
                                                                                $visible_group     = 'd-none';
                                                                                $visible_descargar = '';
                                                                                $remover_casa      = 'd-none';
                                                                            } else {
                                                                                $visible_group     = '';
                                                                                $visible_descargar = 'd-none';
                                                                                $remover_casa      = 'd-none';
                                                                            }

                                                                            if ($estado_modal == 5) {
                                                                                $ver_articulo = 'd-none';
                                                                            }

                                                                            if ($estado_modal == 4) {
                                                                                $visible_lib  = 'd-none';
                                                                                $visible_mant = 'd-none';
                                                                                $visible_rep  = 'd-none';
                                                                                $visible_desc = '';
                                                                                $remover_casa = 'd-none';
                                                                                $trabajo_casa = '';
                                                                            }

                                                                            if ($estado_modal == 1) {
                                                                                $visible_group = '';
                                                                                $visible_lib   = '';
                                                                                $visible_mant  = '';
                                                                                $visible_rep   = '';
                                                                                $visible_desc  = '';
                                                                                $remover_casa  = 'd-none';
                                                                                $trabajo_casa  = '';
                                                                            }

                                                                            if ($estado_modal == 8) {
                                                                                $trabajo_casa = 'd-none';
                                                                                $remover_casa = '';
                                                                                $visible_lib  = '';
                                                                                $visible_mant = '';
                                                                                $visible_rep  = '';
                                                                                $visible_desc = '';
                                                                            }



                                                                            if ($estado_modal == 9) {
                                                                                $trabajo_casa = '';
                                                                                $remover_casa = 'd-none';
                                                                                $visible_lib  = '';
                                                                                $visible_mant = '';
                                                                                $visible_rep  = '';
                                                                                $visible_desc = '';
                                                                            }

                                                                            if ($inventario['confirmado'] == 0) {
                                                                                $visible_group   = 'd-none';
                                                                                $span_confirmado = '<span class="badge badge-danger">No Confirmado</span>';
                                                                            } else if ($inventario['confirmado'] == 1) {
                                                                                $span_confirmado = '<span class="badge badge-success">Confirmado</span>';
                                                                            } else if ($inventario['confirmado'] == 2) {
                                                                                $trabajo_casa    = 'd-none';
                                                                                $remover_casa    = 'd-none';
                                                                                $visible_lib     = '';
                                                                                $visible_mant    = 'd-none';
                                                                                $visible_rep     = 'd-none';
                                                                                $span_confirmado = '<span class="badge badge-warning">Pendiente de revision</span>';
                                                                            }
                                                                        ?>

                                                                            <tr class="">
                                                                                <td><?= $id_inventario_modal ?></td>
                                                                                <td><?= $descripcion_modal ?></td>
                                                                                <td><?= $usuario_modal ?></td>
                                                                                <td><?= $area_modal?></td>
                                                                                <td><?= $span_confirmado_modal?></td>
                                                                                <td>
                                                                                    <a href="<?= BASE_URL ?>listado/historial?inventario=<?= base64_encode($id_inventario_modal) ?>" class="btn btn-info btn-sm" data-tooltip="tooltip" data-placement="bottom" title="Ver historial">
                                                                                        <i class="fas fa-eye"></i>
                                                                                    </a>
                                                                                </td>
                                                                                <td class="<?=$visible_group?>">
                                                                                    <div class="btn-group btn-group-sm" role="group">
                                                                                        <a href="<?= BASE_URL ?>listado/panelControl/liberar?id_inventario=<?= base64_encode($id_inventario_modal) ?>" class="btn btn-success btn-sm <?=$visible_lib?>" data-tooltip="tooltip" data-placement="bottom" title="Liberar">
                                                                                            <i class="fab fa-telegram-plane"></i>
                                                                                        </a>
                                                                                        <a href="<?= BASE_URL?>listado/panelControl/reportar?id_inventario=<?= base64_encode($id_inventario_modal) ?>" class="btn btn-danger btn-sm <?=$visible_rep?>" data-tooltip="tooltip" data-placement="bottom" title="Reportar">
                                                                                            <i class="fas fa-clipboard-check"></i>
                                                                                        </a>
                                                                                        <a href="<?=BASE_URL?>listado/panelControl/descontinuar?id_inventario=<?= base64_encode($id_inventario_modal) ?>" class="btn btn-sm btn-warning <?=$visible_desc?>" data-tooltip="tooltip" data-placement="bottom" title="Descontinuar">
                                                                                            <i class="fas fa-minus-circle"></i>
                                                                                        </a>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="<?=$visible_descargar?>">
                                                                                    <a href="<?=BASE_URL?>imprimir/reporte?id=<?=base64_encode($id_reporte_inv)?>" target="_blank" class="btn btn-primary btn-sm" data-tooltip="tooltip" title="Descargar reporte">
                                                                                        <i class="fa fa-download"></i>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                    </tbody>
                                                                <?php } ?>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- Botón que redirigue al historial del inventario -->
                                            <!-- <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?= BASE_URL ?>listado/historial?inventario=<?= base64_encode($id_inventario) ?>" class="btn btn-warning btn-sm" data-tooltip="tooltip" data-placement="bottom" title="Ver historial">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div> -->
                                        </td>
                                        <td>
                                            <input type="checkbox" class="chk-edit">
                                            <button class="btn btn-success btn-sm btn-save-edit d-none">Guardar</button>
                                        </td>
                                    </tr>

                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById("form-hoja-vida").addEventListener("submit", function(e) {
        e.preventDefault();

        const buscar_id = document.getElementById("buscar_id").value.trim();

        if (buscar_id !== "") {
            const urlEncoded = btoa(buscar_id);
            window.location.href = "<?php echo BASE_URL; ?>listado/historial?inventario=" + urlEncoded;
        } else {
            ohSnap("Por favor ingrese un ID", {
                color: "red",
                "duration": 2000
            });
        }
    });

    document.querySelectorAll('.chk-edit').forEach(function(chk) {
        chk.addEventListener('change', function() {
            var tr = this.closest('tr');
            var spanDesc = tr.querySelector('.span-desc');
            var inpDesc = tr.querySelector('.inp-desc');
            var spanCant = tr.querySelector('.span-cant');
            var inpCant = tr.querySelector('.inp-cant');
            var btnSave = tr.querySelector('.btn-save-edit');

            if (this.checked) {
                spanDesc.classList.add('d-none');
                inpDesc.classList.remove('d-none');
                spanCant.classList.add('d-none');
                inpCant.classList.remove('d-none');
                btnSave.classList.remove('d-none');
            } else {
                spanDesc.classList.remove('d-none');
                inpDesc.classList.add('d-none');
                spanCant.classList.remove('d-none');
                inpCant.classList.add('d-none');
                btnSave.classList.add('d-none');
                inpDesc.value = spanDesc.textContent.trim();
                inpCant.value = spanCant.textContent.trim();
            }
        });
    });

    document.querySelectorAll('.btn-save-edit').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var tr = this.closest('tr');
            var descripcionOriginal = tr.getAttribute('data-descripcion');
            var idUser = tr.getAttribute('data-id-user');
            var idArea = tr.getAttribute('data-id-area');
            var currentCantidad = parseInt(tr.getAttribute('data-cantidad'));
            var descripcionNueva = tr.querySelector('.inp-desc').value.trim();
            var newCantidad = parseInt(tr.querySelector('.inp-cant').value);

            if (descripcionNueva === "") {
                ohSnap("La descripcion no puede estar vacia", { color: "red", duration: 3000 });
                return;
            }

            var formData = new FormData();
            formData.append('descripcion_original', descripcionOriginal);
            formData.append('descripcion_nueva', descripcionNueva);
            formData.append('id_user', idUser);
            formData.append('id_area', idArea);
            formData.append('current_cantidad', currentCantidad);
            formData.append('new_cantidad', newCantidad);

            fetch('<?= BASE_URL ?>vistas/ajax/inventario/editarListado.php', {
                method: 'POST',
                body: formData
            })
            .then(function(response) {
                if (!response.ok) {
                    return response.text().then(function(text) { throw new Error('HTTP ' + response.status + ': ' + text); });
                }
                return response.text();
            })
            .then(function(text) {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    throw new Error('Respuesta no es JSON: ' + text);
                }
            })
            .then(function(data) {
                if (data.success) {
                    ohSnap(data.msg, { color: "green", duration: 2000 });
                    if (newCantidad == 0) {
                        tr.remove();
                    } else {
                        tr.setAttribute('data-descripcion', descripcionNueva);
                        tr.setAttribute('data-cantidad', newCantidad);
                        tr.querySelector('.span-desc').textContent = descripcionNueva;
                        tr.querySelector('.span-cant').textContent = newCantidad;
                        tr.querySelector('.inp-desc').value = descripcionNueva;
                        tr.querySelector('.inp-cant').value = newCantidad;
                        tr.querySelector('.chk-edit').checked = false;
                        tr.querySelector('.chk-edit').dispatchEvent(new Event('change'));
                    }
                } else {
                    ohSnap(data.msg, { color: "red", duration: 3000 });
                }
            })
            .catch(function(err) {
                console.error(err);
                ohSnap(err.message || "Error de conexion", { color: "red", duration: 5000 });
            });
        });
    });
</script>
<?php
include_once VISTA_PATH . 'script_and_final.php';
