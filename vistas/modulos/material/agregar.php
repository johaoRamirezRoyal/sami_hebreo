<!-- Agregar Area -->
<div class="modal fade" id="agregar_material" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Agregar material did&aacute;ctico</h5>
            </div>
            <form method="POST">
                <input type="hidden" value="<?=$id_super_empresa?>" name="id_super_empresa">
                <input type="hidden" value="<?=$id_log?>" name="id_log">
                <div class="modal-body border-0">
                    <div class="row p-2">
                        <div class="col-lg-12 form-group">
                            <label class="font-weight-bold">Descripcion <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" required maxlength="50" minlength="1" name="descripcion">
                        </div>
                        <div class="col-lg-12 form-group">
                            <label class="font-weight-bold">Cantidad <span class="text-danger">*</span></label>
                            <input type="text" class="form-control numeros" maxlength="3" minlength="0" name="cantidad" required>
                        </div>
                        <div class="col-lg-12 form-group">
                            <label class="font-weight-bold">Usuario <span class="text-danger">*</span></label>
                            <select name="usuario" class="form-control select2" required>
                                <option value="" selected>Seleccione una opcion...</option>
                                <?php
                                foreach ($datos_usuario as $usuario) {
                                    $id_usuario = $usuario['id_user'];
                                    $nombre     = $usuario['nombre'] . ' ' . $usuario['apellido'];
                                    $ver        = ($nivel == $usuario['id_nivel']) ? '' : 'd-none';
                                    $ver_admin  = ($_SESSION['rol'] == 2 && $nivel == 1) ? $ver  = '' : $ver;
                                    ?>
                                    <option value="<?=$id_usuario?>" class="<?=$ver_admin?>"><?=$nombre?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-12">
                            <label class="font-weight-bold">Procesos a realizar</label>
                        </div>
                        <div class="col-lg-6 form-group mt-2">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="estado1" value="1" name="estado" required>
                                <label class="custom-control-label" for="estado1">Solo asignar</label>
                            </div>
                        </div>
                        <div class="col-lg-6 form-group mt-2">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="estado2" value="8" name="estado" required>
                                <label class="custom-control-label" for="estado2">Asignar trabajo en casa</label>
                            </div>
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
                        <i class="fa fa-save"></i>
                        &nbsp;
                        Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>