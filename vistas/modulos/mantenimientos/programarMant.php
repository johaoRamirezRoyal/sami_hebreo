<div class="modal fade" id="programar_mant" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-bold text-primary" id="exampleModalLabel">Programar Mantenimiento</h5>
      </div>
      <div class="modal-body">
        <form method="POST" novalidate>
          <input type="hidden" name="id_log" value="<?= $id_log ?? '' ?>">

          <div class="row p-2">
            <div class="col-lg-12 form-group">
              <h6 class="text-danger"><span class="font-weight-bold">Nota:</span> Esta acción solo creará mantenimientos a los artículos que estén en estado NUEVO o ARREGLADO. (Omitirá los que tengan algún reporte y no esté solucionado hasta la fecha)</h6>
            </div>

            <div class="col-lg-6 form-group">
              <label class="font-weight-bold">Escoja una categoría <span class="text-danger">*</span></label>
              <select name="id_categoria" id="id_categoria" class="form-control" required>
                <option value="" disabled selected>--- --- ---</option>
                <?php foreach ($datos_categorias as $categoria_item): ?>
                  <option value="<?= intval($categoria_item['id']) ?>"><?= htmlspecialchars($categoria_item['nombre']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-lg-6 form-group">
              <label class="font-weight-bold">Fecha desde <span class="text-danger">*</span></label>
              <input type="date" class="form-control" name="fecha_desde" required>
            </div>

            <div class="col-lg-6 form-group">
              <label class="font-weight-bold">Fecha hasta <span class="text-danger">*</span></label>
              <input type="date" class="form-control" name="fecha_hasta" required>
            </div>

            <div class="row p-3">
              <div class="form-group col-lg-12">
                <label class="font-weight-bold">Descripción <span class="text-danger">*</span></label>
                <textarea class="form-control" name="descripcion" required maxlength="2000" cols="100" rows="5"></textarea>
              </div>
            </div>

            <div class="col-lg-12 form-group" id="contenedor_inventario" style="display: none;">
              <label class="font-weight-bold">Seleccione los articulos para programar mantenimiento</label>
              <div id="lista_inventario" class="row"></div>
            </div>

            <div class="col-lg-12 form-group text-right mt-2">
              <button class="btn btn-danger btn-sm" type="button" data-dismiss="modal">
                <i class="fa fa-times"></i>
                &nbsp;
                Cancelar
              </button>

              <button class="btn btn-primary btn-sm" type="submit" name="mantenimiento_solucion">
                <i class="fa fa-save"></i>
                &nbsp;
                Generar Mantenimientos Solución
              </button>

              <button class="btn btn-hebreo btn-sm" type="submit" name="reporte_preventivo">
                <i class="fa fa-save"></i>
                &nbsp;
                Generar mantenimiento
              </button>

            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>