<!-- Modal -->
<div class="modal fade" id="agregar_zona" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-bold text-primary" id="exampleModalLabel">Agregar area</h5>
      </div>
      <form method="POST">
        <input type="hidden" name="id_log" value="<?=$id_log?>">
        <div class="modal-body">
          <div class="row p-2">
            <div class="col-lg-12 form-group">
              <label class="font-weight-bold">Nombre <span class="text-danger">*</span></label>
              <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="form-group col-lg-12">
              <label class="font-weight-bold">Zona <span class="text-danger">*</span></label>
              <select name="area" class="form-control select2">
                <option value="" selected>Seleccione una opcion...</option>
                <?php
                foreach ($datos_area as $area) {
                  $id_area  = $area['id'];
                  $nom_area = $area['nombre'];
                  $activo   = $area['activo'];

                  $ver = ($activo == 0) ? 'd-none' : '';
                  ?>
                  <option value="<?=$id_area?>" class="<?=$ver?>"><?=$nom_area?></option>
                  <?php
                }
                ?>
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
            Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
