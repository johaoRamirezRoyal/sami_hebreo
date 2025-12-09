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

    header('Location:../../login?er=' . $error);

    exit();
}

include_once VISTA_PATH . 'cabeza.php';

include_once VISTA_PATH . 'navegacion.php';

require_once CONTROL_PATH . 'recursos' . DS . 'ControlRecursos.php';

require_once CONTROL_PATH . 'usuarios' . DS . 'ControlUsuarios.php';


$instancia         = ControlRecursos::singleton_recursos();

$instancia_usuario = ControlUsuarios::singleton_usuarios();

$datos_usuario = $instancia_usuario->mostrarTodosUsuariosInventarioControl();

$datos = [];

if ($perfil_log == 22 || $perfil_log == 23 || $perfil_log == 11 || $perfil_log == 28) {
    $datos_permiso = $instancia->mostrarPermisosLicenciasUsuarioNivelControl($nivel);
} else {
    $datos_permiso = $instancia->mostrarListadoPermisosControl();
}

$permisos = $instancia_permiso->permisosUsuarioControl(81, $perfil_log);

$fecha_inicio = '';
$fecha_fin = '';


if (isset($_POST['fecha_inicio'])) {
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
}

//Busqueda filtrado 
if (isset($_POST['buscar'])) {
    $datos = array(
        'buscar' => $_POST['buscar'],
        'usuario' => $_POST['usuario'],
        'fecha' => $_POST['fecha'],
        'id_nivel' => $nivel
    );

    if ($perfil_log != 1 && $perfil_log != 8 && $perfil_log != 2) {
        $datos_permiso = $instancia->mostrarPermisosLicenciasUsuarioNivelFiltroControl($datos);
    } else {
        $datos_permiso = $instancia->mostrarPermisosLicenciasUsuariosFiltroControl($datos);
    }
}

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

                        <a href="<?= BASE_URL ?>recursos/permisos/index" class="text-decoration-none">

                            <i class="fa fa-arrow-left text-primary"></i>

                        </a>

                        &nbsp;

                        Listado de solicitudes

                    </h4>

                    <div class="btn-group">

                        <a href="<?= BASE_URL ?>recursos/permisos/index" class="btn btn-secondary btn-sm">

                            <i class="fa fa-plus"></i>

                            &nbsp;

                            Nueva Solicitud

                        </a>

                    </div>


                </div>

                <div class="card-body">

                    <form method="POST">
                        <input type="hidden" name="id_nivel" value="<?= $nivel ?>">
                        <div class="row">

                            <div class="col-lg-4 form-group">

                                <select name="usuario" class="form-control" id="usuario" data-tooltip="tooltip" title="Usuario">

                                    <option value="" selected>Seleccione un usuario...</option>

                                    <?php

                                    foreach ($datos_usuario as $usuarios) {

                                        $id_user         = $usuarios['id_user'];

                                        $nombre_completo = $usuarios['nombre'] . ' ' . $usuarios['apellido'];

                                    ?>

                                        <option value="<?= $id_user ?>"><?= $nombre_completo ?></option>

                                    <?php

                                    }

                                    ?>

                                </select>

                            </div>

                            <div class="col-lg-4 form-group">

                                <input type="month" name="fecha" class="form-control" data-tooltip="tooltip" title="Fecha" placeholder="2025-05">

                            </div>

                            <div class="col-lg-4 form-group">

                                <div class="input-group">

                                    <input type="text" class="form-control filtro" placeholder="Buscar" aria-describedby="basic-addon2" name="buscar" data-tooltip="tooltip" data-trigger="focus" data-placement="top" title="Presione ENTER para buscar">

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

                    <div class="col-lg-12 form-group mt-2 text-right">

                        <?php if ($datos != []): ?>
                            <?php if (!empty($datos)): ?>
                                <?php
                                $params = array_filter([
                                    'buscar' => $datos['buscar'] ?? null,
                                    'usuario' => $datos['usuario'] ?? null,
                                    'fecha' => $datos['fecha'] ?? null,
                                    'nivel' => $nivel,
                                    'perfil' => $perfil_log
                                ]);
                                $query = http_build_query($params);
                                ?>
                                <a href="<?= BASE_URL ?>imprimir/recursos/permisosLicencias?<?= $query ?>"
                                    target="_blank"
                                    class="btn btn-success btn-sm">

                                    <i class="fa fa-file-excel"></i>
                                    &nbsp; Descargar Reporte
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>

                    </div>

                    <div class="table-responsive mt-2">

                        <table class="table table-hover border table-sm" width="100%" cellspacing="0" id="tabla-permisos">

                            <thead>

                                <tr class="text-center font-weight-bold">

                                    <th scope="col">No. Solicitud</th>

                                    <th scope="col">Documento</th>

                                    <th scope="col">Nombre</th>

                                    <th scope="col">Telefono</th>

                                    <th scope="col">Motivo Permiso</th>

                                    <th scope="col">Tipo Permiso</th>

                                    <th scope="col" onclick="sortTable(6)" style="cursor:pointer">⬍ Fecha Solicitado ⬍</th>

                                    <th scope="col" onclick="sortTable(7)" style="cursor:pointer">⬍ Fecha del Permiso ⬍</th>

                                </tr>

                            </thead>

                            <tbody class="buscar text-center">

                                <?php

                                foreach ($datos_permiso as $permiso) {

                                    $id_permiso       = $permiso['id'];

                                    $documento        = $permiso['documento'];

                                    $nom_user         = $permiso['nom_user'];

                                    $telefono         = $permiso['telefono'];

                                    $motivo_permiso   = $permiso['nom_motivo'];

                                    $tipo_permiso     = $permiso['nom_tipo'];

                                    $fecha_permiso    = $permiso['fecha_permiso'];

                                    $correo           = $permiso['correo_user'];

                                    $fecha_solicitado = date('Y-m-d', strtotime($permiso['fechareg']));

                                    $evidencia_solicitud = $permiso['evidencia_permiso'];





                                    if ($permiso['estado'] == 0) {

                                        $span_estado = '<span class="badge badge-warning">Pendiente</span>';
                                    }



                                    if ($permiso['estado'] == 1) {

                                        $span_estado = '<span class="badge badge-success">Aprobado</span>';
                                    }



                                    if ($permiso['estado'] == 2) {

                                        $span_estado = '<span class="badge badge-danger">Rechazado</span>';
                                    }

                                    $disable_boton = ($permiso['estado'] != 0 && $evidencia_solicitud != '') ? 'disabled' : '';

                                ?>

                                    <tr>

                                        <td><?= $id_permiso ?></td>

                                        <td><?= $documento ?></td>

                                        <td><?= $nom_user ?></td>

                                        <td><?= $telefono ?></td>

                                        <td><?= $motivo_permiso ?></td>

                                        <td><?= $tipo_permiso ?></td>

                                        <td><?= $fecha_solicitado ?></td>

                                        <td><?= $fecha_permiso ?></td>

                                        <td><?= $span_estado ?></td>

                                        <td>

                                            <a href="<?= BASE_URL ?>recursos/permisos/detalles?id=<?= base64_encode($id_permiso) ?>&enlace=<?= base64_encode(0) ?>" class="btn btn-info btn-sm" data-tooltip="tooltip" title="Ver detalles" data-placement="bottom">

                                                <i class="fa fa-eye"></i>

                                            </a>

                                            <form method="POST">
                                                <input type="hidden" name="id" value="<?= $id_permiso ?>">
                                                <input type="hidden" name="id_log" value="<?= $id_log ?>">
                                                <input type="hidden" name="correo" value="<?= $correo ?>">
                                                <button type="submit" class="btn btn-warning btn-sm mt-2" name="enviar_recordatorio" data-tooltip="tooltip" title="Enviar recordatorio" data-placement="right" <?= $disable_boton ?>>
                                                    <i class="fa fa-envelope"></i>
                                                </button>
                                            </form>

                                            <?php
                                            $ruta_archivo = PUBLIC_PATH_ARCH . 'upload/' . $evidencia_solicitud;
                                            if (!empty($evidencia_solicitud) && file_exists($ruta_archivo)) {
                                            ?>
                                                <!-- Descargar evidencia de solicitud de permiso -->
                                                <a href="<?= PUBLIC_PATH ?>upload/<?= $evidencia_solicitud ?>" class="btn btn-primary btn-sm mt-2" download data-tooltip="tooltip" title="Descargar evidencia de solicitud de permiso" data-placement="left">

                                                    <i class="fa fa-download"></i>

                                                </a>
                                            <?php
                                            }
                                            ?>

                                        </td>

                                    </tr>

                                <?php

                                }

                                ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script>
        function sortTable(n) {
            var table = document.getElementById("tabla-permisos");
            var tbody = table.tBodies[0];
            var rows = Array.from(tbody.rows);
            var asc = table.getAttribute("data-sort-col") == n && table.getAttribute("data-sort-dir") == "asc" ? false : true;

            rows.sort(function(a, b) {
                let x = a.cells[n].innerText.trim();
                let y = b.cells[n].innerText.trim();

                // Detectar si es fecha (YYYY-MM-DD o DD/MM/YYYY)
                let dateX = Date.parse(x.replace(/(\d{2})\/(\d{2})\/(\d{4})/, "$2/$1/$3"));
                let dateY = Date.parse(y.replace(/(\d{2})\/(\d{2})\/(\d{4})/, "$2/$1/$3"));

                if (!isNaN(dateX) && !isNaN(dateY)) {
                    return asc ? dateX - dateY : dateY - dateX;
                }

                // Si no es fecha, comparar como texto
                return asc ? x.localeCompare(y) : y.localeCompare(x);
            });

            rows.forEach(r => tbody.appendChild(r));

            table.setAttribute("data-sort-col", n);
            table.setAttribute("data-sort-dir", asc ? "asc" : "desc");
        }
    </script>

</div>

<?php
if (isset($_POST['enviar_recordatorio'])) {
    $instancia->enviarRecordatorioControl();
}

include_once VISTA_PATH . 'script_and_final.php';

?>