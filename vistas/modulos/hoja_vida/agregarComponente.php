<!-- Agregar Area -->
<div class="modal fade" id="agregar_componente_hard" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-hebreo font-weight-bold" id="exampleModalLabel">Agregar hardware</h5>
            </div>
            <div class="modal-body border-0">
                <div class="table-responsive">
                    <table class="table table-hover border" width="100%" cellspacing="0">
                        <thead>
                            <tr class="text-center font-weight-bold">
                                <th>Descripcion</th>
                                <th>Modelo</th>
                                <th>Marca</th>
                                <th>Codigo</th>
                                <th>Observacion</th>
                            </tr>
                        </thead>
                        <tbody class="buscar">
                            <?php
                            foreach ($datos_componentes as $hardware) {
                                $id = $hardware['id'];
                                $descripcion = $hardware['descripcion'];
                                $marca = $hardware['marca'];
                                $modelo = $hardware['modelo'];
                                $codigo = $hardware['codigo'];
                                $observacion = $hardware['observacion'];
                                $asignado = $hardware['asignado'];

                                if ($asignado == 'si') {
                                    $td = '<span class="badge badge-success">Asignado</span>';
                                } else {
                                    $td = '<button class="btn btn-success btn-sm hardware" data-tooltip="tooltip" title="Agregar" id="' . $id . '">
                                            <i class="fa fa-plus"></i>
                                        </button>';
                                }

                            ?>
                                <tr class="text-center">
                                    <td><?= $descripcion ?></td>
                                    <td><?= $modelo ?></td>
                                    <td><?= $marca ?></td>
                                    <td><?= $codigo ?></td>
                                    <td><?= $observacion ?></td>
                                    <td class="span-<?= $id ?>">
                                        <?= $td ?>
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
</div> </div>
</div>