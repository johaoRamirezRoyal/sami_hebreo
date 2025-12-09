<!-- Modal -->
<div class="modal fade" id="agregar_area" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-bold text-primary" id="exampleModalLabel">Agregar zona</h5>
      </div>
      <form method="POST">
        <input type="hidden" name="id_log" value="<?=$id_log?>">
        <div class="modal-body">
          <div class="row p-2">
            <div class="col-lg-12 form-group">
              <label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
              <input type="text" name="nom_area" class="form-control" required>
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
            Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
