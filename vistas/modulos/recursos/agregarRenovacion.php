<div class="modal fade" id="agregar_renovacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Agregar Documento</h5>
      </div>
      <div class="modal-body">
        <form method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id_log" value="<?=$id_log?>">
          <div class="row p-2">
            <div class="col-lg-6 form-group">
              <label class="font-weight-bold">Tipo de proceso <span class="text-danger">*</span></label>
              <select class="form-control" name="tipo" required>
                <option value="" selected>Seleccione una opcion...</option>
                <?php
                foreach ($datos_tipo_gestion as $gestion) {
                  $id_gestion = $gestion['id'];
                  $nombre     = $gestion['nombre'];
                  ?>
                  <option value="<?=$id_gestion?>"><?=$nombre?></option>
                <?php }?>
              </select>
            </div>
            <div class="col-lg-6 form-group">
              <label class="font-weight-bold">Nombre del Documento <span class="text-danger">*</span></label>
              <input type="text" class="form-control" required name="nombre">
            </div>
            <div class="col-lg-6 form-group">
              <label class="font-weight-bold">Version del Documento <span class="text-danger">*</span></label>
              <input type="text" class="form-control" required name="version">
            </div>
            <div class="col-lg-6 form-group">
              <label class="font-weight-bold">Fecha Vigencia <span class="text-danger">*</span></label>
              <input type="date" class="form-control" name="fecha_vigencia" required>
            </div>
            <div class="col-lg-6 form-group">
              <label class="font-weight-bold">Fecha Revision</label>
              <input type="date" class="form-control" name="fecha_revision">
            </div>
            <div class="col-lg-6 form-group">
              <label class="font-weight-bold">Categoria del documento <span class="text-danger">*</span></label>
              <select class="form-control" name="cate_doc" id="cate_doc" required>
                <option value="" selected>Seleccione una opcion...</option>
                <option value="1">Virtual</option>
                <option value="2">Fisico</option>
              </select>
            </div>
            <div class="col-lg-12 form-group">
              <label class="font-weight-bold">Gestion del Cambio</label>
              <textarea class="form-control" name="gestion" rows="5"></textarea>
            </div>
            <div class="col-lg-12 form-group"  id="url_archivo">
              <label class="font-weight-bold">URL del archivo</label>
              <input type="text" name="url_archivo" class="form-control">
            </div>
            <div class="form-group col-lg-12" id="file_doc">
              <label class="font-weight-bold">Evidencia</label>
              <input type="file" class="file" name="archivo" accept=".png,.jpg,.jpeg,.pdf" >
            </div>
            <div class="col-lg-12 form-group text-right mt-2">
              <button class="btn btn-danger btn-sm" type="button" data-dismiss="modal">
                <i class="fa fa-times"></i>
                &nbsp;
                Cancelar
              </button>
              <button class="btn btn-primary btn-sm" type="submit">
                <i class="fa fa-save"></i>
                &nbsp;
                Guardar
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
