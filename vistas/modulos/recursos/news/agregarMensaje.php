<div class="modal fade" id="agregar_mensaje_mensaje_general" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-primary font-weight-bold">Mensaje General</h5>
      </div>
      <div class="modal-body">
        <form method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id_log" value="<?=$id_log?>">
          <div class="row p-2">
            <div class="col-lg-6 form-group">
              <label class="font-weight-bold">Titulo del mensaje <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="titulo" required>
            </div>
            <div class="col-lg-6 form-group">
              <label class="font-weight-bold">Imagen (opcional)</label>
              <div class="custom-file pmd-custom-file-filled">
                <input type="file" class="custom-file-input file_input"  name="imagen" accept=".png, .jpg, .jpeg">
                <label class="custom-file-label file_label" for="customfilledFile"></label>
              </div>
            </div>
            <div class="col-lg-12 form-group">
              <label class="font-weight-bold">Contenido del Mensaje/Noticia <span class="text-danger">*</span></label>
              <textarea class="form-control" rows="5" name="mensaje_general" required></textarea>
            </div>
            <div class="col-lg-12 form-group mt-2 text-right">
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
