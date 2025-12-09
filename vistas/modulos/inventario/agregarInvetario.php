<!-- Agregar Area -->

<div class="modal fade" id="agregar_inventario" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Agregar Articulo</h5>

            </div>

            <form method="POST" id="agr_inventario" enctype="multipart/form-data">

                <div class="modal-body border-0">

                    <div class="row p-2">

                        <input type="hidden" value="<?=$id_super_empresa?>" name="super_empresa" id="super_empresa">

                        <input type="hidden" value="<?=$id_log?>" name="id_log" id="id_log">

                        <div class="form-group col-lg-6">

                            <label class="font-weight-bold">Descripcion <span class="text-danger">*</span></label>

                            <input type="text" class="form-control" id="descripcion" name="descripcion" required >

                        </div>

                        <div class="form-group col-lg-6">

                            <label class="font-weight-bold">Marca</label>

                            <input type="text" class="form-control" name="marca" id="marca">

                        </div>

                        <div class="form-group col-lg-6">

                            <label class="font-weight-bold">Modelo</label>

                            <input type="text" class="form-control" name="modelo" id="modelo">

                        </div>

                        <div class="form-group col-lg-6">

                            <label class="font-weight-bold">Precio</label>

                            <input type="text" class="form-control numeros" name="precio" id="precio">

                        </div>

                        <div class="form-group col-lg-6">

                            <label class="font-weight-bold">Fecha de compra</label>

                            <input type="date" class="form-control" name="fecha" id="fecha">

                        </div>

                        <div class="form-group col-lg-6">

                            <label class="font-weight-bold">Fecha de ingreso <span class="text-danger">*</span></label>

                            <input type="date" class="form-control" name="fecha_ingreso" id="fecha_ingreso">

                        </div>

                        <div class="form-group col-lg-6">

                            <label class="font-weight-bold">Usuario <span class="text-danger">*</span></label>

                            <select name="usuario" class="form-control select2" id="usuario" required >

                                <option value="" selected>Seleccione una opcion...</option>

                                <?php

                                foreach ($datos_usuario as $usuarios) {

                                    $id_user         = $usuarios['id_user'];

                                    $nombre_completo = $usuarios['nombre'] . ' ' . $usuarios['apellido'];

                                    $estado          = $usuarios['estado'];



                                    $visible_user = ($estado == 'activo') ? '' : 'd-none';

                                    ?>

                                    <option value="<?=$id_user?>" class="<?=$visible_user?>"><?=$nombre_completo?></option>

                                    <?php

                                }

                                ?>

                            </select>

                        </div>

                        <div class="form-group col-lg-6">

                            <label class="font-weight-bold">Area <span class="text-danger">*</span></label>

                            <select name="area" class="form-control select2" id="area" required >

                                <option value="" selected>Seleccione una opcion...</option>

                                <?php

                                foreach ($datos_areas as $areas) {

                                    $id_area     = $areas['id'];

                                    $nombre_area = $areas['nombre'];

                                    $estado      = $areas['activo'];



                                    $visible_area = ($estado == 1) ? '' : 'd-none';



                                    ?>

                                    <option value="<?=$id_area?>" class="<?=$visible_area?>"><?=$nombre_area?></option>

                                    <?php

                                }

                                ?>

                            </select>

                        </div>

                        <div class="form-group col-lg-6">

                            <label class="font-weight-bold">Cantidad <span class="text-danger">*</span></label>

                            <input type="text" class="form-control numeros" name="cantidad" id="cantidad" name="nombre" maxlength="10" minlength="1">

                        </div>

                        <div class="form-group col-lg-12">

                            <label class="font-weight-bold">Categoria <span class="text-danger">*</span></label>

                            <select name="categoria" class="form-control select2" id="categoria">

                                <option value="" selected>Seleccione una opcion...</option>

                                <?php

                                foreach ($datos_categoria as $categorias) {

                                    $id_categoria     = $categorias['id'];

                                    $nombre_categoria = $categorias['nombre'];



                                    ?>

                                    <option value="<?=$id_categoria?>"><?=$nombre_categoria?></option>

                                    <?php

                                }

                                ?>

                            </select>

                        </div>

                        <div class="form-group col-lg-12">

                            <label class="font-weight-bold">Evidencia</label>

                            <input id="file" type="file" class="file" name="archivo" accept=".png,.jpg,.jpeg" >

                        </div>

                    </div>

                </div>

                <div class="modal-footer border-0">

                    <a href="<?=BASE_URL?>imprimir/cartaEntrega?id_log=<?=base64_encode($id_log)?>&super_empresa=<?=base64_encode($id_super_empresa)?>" class="btn btn-primary btn-sm mr-auto disabled" id="carta_entrega">

                        <i class="fas fa-file-alt" id="carta_entrega"></i>

                        &nbsp;

                        Generar Carta de entrega

                    </a>

                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">

                        <i class="fa fa-times"></i>

                        &nbsp;

                        Cerrar

                    </button>

                    <button type="submit" id="guardar_inventario" class="btn btn-success btn-sm">

                        <i class="fa fa-save"></i>

                        &nbsp;

                        Registrar

                    </button>

                </div>

            </form>

            <div class="modal-body border-0">

                <div class="row p-2">

                    <div class="table-responsive mt-2 p-2">

                        <table class="table table-hover border table-sm" width="100%" cellspacing="0">

                            <thead>

                                <tr class="text-center border">

                                    <td colspan="6">

                                        <h5 class="text-primary font-weight-bold">Articulos agregados</h5>

                                    </td>

                                </tr>

                                <tr class="text-center font-weight-bold">

                                    <th scope="col">Descripcion</th>

                                    <th scope="col">Marca</th>

                                    <th scope="col">Modelo</th>

                                    <th scope="col">Precio</th>

                                    <th scope="col">Fecha Compra</th>

                                    <th scope="col">Cantidad</th>

                                </tr>

                            </thead>

                            <tbody class="buscar" id="tabla_inventario">

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>