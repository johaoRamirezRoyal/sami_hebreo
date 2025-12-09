<div class="modal fade" id="agregar_contenido" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-bold text-primary" id="exampleModalLabel">Agregar Contenido</h5>
      </div>
      <div class="modal-body">
        <form method="POST">
          <input type="hidden" name="id_log" value="<?=$id_log?>">
          <input type="hidden" name="id_paquete" value="<?=$id_paquete?>">
          <div class="row p-2">
            <div class="col-lg-12 form-group">
              <label class="font-weight-bold">Titulo del contenido <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="nom_contenido" required>
            </div>
            <div class="col-lg-12 form-group text-right">
              <button class="btn btn-success btn-sm" type="submit">
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
