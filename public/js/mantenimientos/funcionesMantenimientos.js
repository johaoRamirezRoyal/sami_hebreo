$(document).ready(function () {
  console.log("✅ JS cargado");

  $(document).on("change", "#id_categoria", function () {
    let idCategoria = $(this).val();
    console.log("📌 Categoria seleccionada:", idCategoria);

    if (!idCategoria) {
      $("#contenedor_inventario").hide();
      $("#lista_inventario").html("");
      return;
    }

    $.ajax({
      url: "../vistas/ajax/mantenimientos/MostrarInventarioMantenimiento.ajax.php",
      type: "POST",
      data: { id_categoria: idCategoria },
      dataType: "json",
      success: function (response) {
        console.log("✅ Respuesta AJAX:", response);
        let html = "";

        if (response.length > 0) {
          response.forEach(function (item) {
            html += `
              <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="form-check">
                  <input class="form-check-input" 
                         type="checkbox" 
                         name="inventario_ids[]" 
                         value="${item.id}" 
                         id="inv_${item.id}">
                  <label class="form-check-label" for="inv_${item.id}">
                    ${item.descripcion} - ${item.nom_area}
                  </label>
                </div>
              </div>
            `;
          });
        } else {
          html = `<div class="col-12 text-muted">No hay artículos disponibles</div>`;
        }

        $("#lista_inventario").html(html);
        $("#contenedor_inventario").show();
      },
      error: function (xhr) {
        console.error("❌ Error AJAX:", xhr.responseText);
      },
    });
  });
});
