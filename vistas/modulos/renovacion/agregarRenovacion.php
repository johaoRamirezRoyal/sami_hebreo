<div class="modal fade" id="agregar_renovacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-primary font-weight-bold" id="exampleModalLabel">Agregar Renovacion</h5>
      </div>
      <div class="modal-body">
        <form method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id_log" value="<?=$id_log?>">
          <div class="row p-2">
            <div class="col-lg-6 form-group">
              <label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
              <input type="text" class="form-control" required name="nombre">
            </div>
            <div class="col-lg-6 form-group">
              <label class="font-weight-bold">Fecha renovacion <span class="text-danger">*</span></label>
              <input type="date" class="form-control" name="fecha_renovacion" required>
            </div>
            <div class="col-lg-6 form-group">
              <label class="font-weight-bold">Fecha proxima renovacion <span class="text-danger">*</span></label>
              <input type="date" class="form-control" name="fecha_proxima" required>
            </div>
            <div class="form-group col-lg-12">
              <label class="font-weight-bold">Evidencia</label>
              <input id="file" type="file" class="file" name="archivo" accept=".png,.jpg,.jpeg,.pdf" >
            </div>
            <div class="col-lg-12 form-group text-right mt-2">
              <button class="btn btn-danger btn-sm" type="button" data-dismiss="modal">
                <i class="fa fa-times"></i>
                &nbsp;
                Cancelar
              </button>
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
