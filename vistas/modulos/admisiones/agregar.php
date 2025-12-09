<!-- Agregar acudinete -->
<div class="modal fade" id="agregar_acudiente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Agregar acudiente</h5>
            </div>
            <form method="POST">
                <div class="modal-body border-0">
                    <div class="row p-2">
                        <input type="hidden" value="<?=$id_super_empresa?>" name="super_empresa">
                        <input type="hidden" value="<?=$id_log?>" name="id_log">
                        <div class="form-group col-lg-6">
                            <label class="font-weight-bold">Documento <span class="text-danger">*</span></label>
                            <input type="text" class="form-control numeros" required name="documento" maxlength="50" minlength="1" required>
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control letras" required name="nombre" maxlength="50" minlength="1" required>
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="font-weight-bold">Apellido</label>
                            <input type="text" class="form-control letras" name="apellido  " maxlength="50" minlength="1">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="font-weight-bold">Correo <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="correo" maxlength="80" minlength="1" required>
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="font-weight-bold">Telefono</label>
                            <input type="text" class="form-control numeros" name="telefono" maxlength="50" minlength="1">
                        </div>
                        <div class="form-group col-lg-6">
                            <label class="font-weight-bold">Nivel <span class="text-danger">*</span></label>
                            <select name="nivel" class="form-control select2" required>
                                <option value="" selected>Seleccione una opcion...</option>
                                <option value="2">Bloque infantil</option>
                                <option value="3">Bloque primaria</option>
                                <option value="4">Bloque secundaria</option>
                            </select>
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
                        <i class="fa fa-save"></i>
                        &nbsp;
                        Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>