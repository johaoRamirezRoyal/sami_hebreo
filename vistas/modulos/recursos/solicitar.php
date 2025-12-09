<!-- Modal -->
<div class="modal fade" id="sol_cert" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold text-primary">
                    Solicitar certificado
                </h5>
            </div>
            <form method="POST">
                <input type="hidden" name="id_log" value="<?=$id_log?>">
                <input type="hidden" name="id_super_empresa" value="<?=$id_super_empresa?>">
                <div class="modal-body border-0">
                    <div class="row p-3">
                        <div class="col-lg-6 form-group mb-2">
                            <label class="font-weight-bold">Documento <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" disabled value="<?=$datos_usuario['documento']?>">
                        </div>
                        <div class="col-lg-6 form-group mb-2">
                            <label class="font-weight-bold">Lugar de expedición <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="lugar" required>
                        </div>
                        <div class="col-lg-6 form-group mb-2">
                            <label class="font-weight-bold">Cargo que ocupa <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="cargo" required>
                        </div>
                        <div class="col-lg-6 form-group mb-2">
                            <label class="font-weight-bold">Entidad dirigida en el certificado <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nom_entidad" required>
                        </div>
                        <div class="col-lg-12 form-group mb-4 mt-4">
                            <label class="font-weight-bold">Trabaja actualmente<span class="text-danger">*</span></label>
                            <div class="row">
                                <!-- Default unchecked -->
                                <div class="custom-control custom-radio col-lg-2 ml-4">
                                    <input type="radio" class="custom-control-input" id="si" name="trabaja" value="si" required>
                                    <label class="custom-control-label" for="si">Si</label>
                                </div>
                                <!-- Default unchecked -->
                                <div class="custom-control custom-radio col-lg-2 ml-4">
                                    <input type="radio" class="custom-control-input" id="no" name="trabaja" value="no" required>
                                    <label class="custom-control-label" for="no">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 form-group mb-4 mt-2">
                            <label class="font-weight-bold">Certificado a solicitar<span class="text-danger">*</span></label>
                            <div class="row">
                                <?php
                                foreach ($datos_tipo as $tipo) {
                                    $id_tipo = $tipo['id'];
                                    $nombre  = $tipo['nombre'];

                                    $ver = ($id_tipo == 1 || $id_tipo == 2) ? '' : 'd-none';
                                    ?>
                                    <!-- Default unchecked -->
                                    <div class="custom-control custom-radio col-lg-5 ml-4 mb-2 <?=$ver?>">
                                        <input type="radio" class="custom-control-input tipo_doc" id="<?=$id_tipo?>" value="<?=$id_tipo?>" name="tipo_cert" required>
                                        <label class="custom-control-label" for="<?=$id_tipo?>"><?=$nombre?></label>
                                    </div>
                                <?php }?>
                                <div class="col-lg-12 form-group mt-2" id="anio">
                                    <label class="font-weight-bold">A&ntilde;o gravable<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control numeros" name="anio">
                                </div>
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
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-check"></i>
                        &nbsp;
                        Solicitar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>