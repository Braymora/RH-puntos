$(document).ready(function () {
  var max_fields = 10; // Máximo número de campos
  var wrapper = $(".contActividades"); // Contenedor de campos originales
  var additionalWrapper = $(".additionalFieldsWrapper"); // Contenedor de campos agregados
  var addButton = $(".btnAgregar"); // Botón de añadir

  var x = 1;
  $(addButton).click(function (e) {
    e.preventDefault();
    if (x < max_fields) {
      x++;
      var newField = `
            <div class="addedFields">
                
                <div class="inputGroup">
                    <input type="text" required="" autocomplete="off" name="puntosPorMes[]" class="puntosPorMes">
                    <label for="name">Ingresar Puntos</label>
                </div>
                <div class="inputGroup">
                    <input type="text" required="" autocomplete="off" name="actividades[]" class="actividad">
                    <label for="name">Ingresar actividad</label>
                </div>
                
                <button class="remove_field" type="submit">
                    <span>
                     <img src="../../../assets/icons/delete.svg" alt="">
                    </span>
                </button>
            </div>
        `;
      $(additionalWrapper).append(newField);
    }
  });

  $(document).on("click", ".remove_field", function (e) {
    e.preventDefault();
    $(this).parent(".addedFields").remove();
    x--;
  });
});
