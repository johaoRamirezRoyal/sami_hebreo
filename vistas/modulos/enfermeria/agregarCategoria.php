<!-- Modal -->
<div class="modal fade" id="agregar_categoria" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-bold text-primary" id="exampleModalLabel">Agregar Categoria</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST">
          <input type="hidden" name="id_log" value="<?=$id_log?>">
          <div class="row p-2">
            <div class="col-lg-12 form-group">
              <label class="font-weight-bold">Nombre Categoria <span class="text-danger">*</span></label>
              <input type="text" name="nom_categoria" class="form-control" required>
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
