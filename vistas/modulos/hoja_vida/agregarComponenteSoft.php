<!-- Agregar Area -->
<div class="modal fade" id="agregar_componente_soft" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Agregar software</h5>
            </div>
            <form method="POST">
                <input type="hidden" value="<?= $id_super_empresa ?>" name="id_super_empresa">
                <input type="hidden" value="<?= $id_log ?>" name="id_log">
                <input type="hidden" value="<?= $datos_articulo['id_hoja_vida'] ?>" name="id_hoja_vida">
                <input type="hidden" value="<?= $id_inventario ?>" name="id_inventario">
                <div class="modal-body border-0">
                    <div class="row p-2">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Descripcion</label>
                                <input type="text" class="form-control" maxlength="50" minlength="1" required name="descripcion_soft">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Version</label>
                                <input type="text" class="form-control" maxlength="50" minlength="1" required name="version">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Fabricante</label>
                                <input type="text" class="form-control" maxlength="50" minlength="1" required name="fabricante">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Licencia</label>
                                <input type="text" class="form-control" maxlength="50" minlength="1" required name="licencia">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Observacion</label>
                                <textarea name="observacion" id="" cols="30" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button class="btn btn-danger btn-sm" type="button" data-dismiss="modal">
                        <i class="fa fa-times"></i>
                        &nbsp;
                        Cerrar
                    </button>
                    <button class="btn btn-success btn-sm" type="submit">
                        <i class="fa fa-save"></i>
                        &nbsp;
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>