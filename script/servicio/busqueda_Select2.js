// Inicializa Select2 en el elemento select
$(document).ready(function () {
  $("#n_contrato").select2({
    placeholder: "Selecciona una opción", // Texto del placeholder
  });

  $("#ceco").select2({
    placeholder: "Selecciona una opción", // Texto del placeholder
  });

  $("#anexo").select2({
    placeholder: "Selecciona una opción", // Texto del placeholder
  });

  $("#n_contrato").change(function () {
    var selectedContract = $(this).val();
    var selectedContractCorreo = $("#n_contrato option:selected").data(
      "correo"
    );
    var selectedceco = $("#n_contrato option:selected").data("ceco");
    $("#correo").val(selectedContractCorreo);
    $("#ceco").val(selectedceco);
  });
});
