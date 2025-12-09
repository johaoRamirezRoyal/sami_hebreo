<!-- Modal -->
<div class="modal fade" id="agregar_salon" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-bold text-hebreo" id="exampleModalLabel">Agregar Salon</h5>
      </div>
      <form method="POST">
        <input type="hidden" name="id_log" value="<?=$id_log?>">
        <div class="modal-body">
          <div class="row p-2">
            <div class="col-lg-6 form-group">
              <label class="font-weight-bold">Nombre del salon <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="nombre" required>
            </div>
            <div class="col-lg-6 form-group">
              <label class="font-weight-bold">Configuracion del salon</label>
              <div class="form-inline mt-2">
                <!-- Default switch -->
                <div class="custom-control custom-switch ml-2">
                  <input type="checkbox" class="custom-control-input" id="customSwitches" value="si" name="portatil">
                  <label class="custom-control-label" for="customSwitches">Portatil</label>
                </div>

                <!-- Default switch -->
                <div class="custom-control custom-switch ml-4">
                  <input type="checkbox" class="custom-control-input" id="customSwitches2" value="si" name="sonido">
                  <label class="custom-control-label" for="customSwitches2">Sonido</label>
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
          <button type="submit" class="btn btn-hebreo btn-sm">
            <i class="fa fa-save"></i>
            &nbsp;
            Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
