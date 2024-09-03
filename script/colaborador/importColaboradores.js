$(document).ready(function () {
    $("#cargaExcel").submit(function (e) {
      e.preventDefault();
  
      var excelFile = $("#excel_file")[0].files[0];
      if (!excelFile) {
        showWarningMessage("No se ha seleccionado ningún archivo", "4");
        return;
      }
  
      var fileExtension = $("#excel_file").val().split(".").pop().toLowerCase();
      if (fileExtension !== "xlsx") {
        showWarningMessage("El archivo no es un Excel válido", "4");
        return;
      }
  
      disableImportButton(true);
      showLoader(true);
  
      var formData = new FormData();
      formData.append("excel_file", excelFile);
  
      $.ajax({
        url: "../../../app/includes/colaboradores/importColaboradores.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
          console.log(response);
          // disableImportButton(false);
          // showLoader(false);
  
          // if (response.exito) {
          //   showMessage(response.mensaje, "1", "flex");
          // } else if (response.warning) {
          //   showWarningMessage(response.mensaje, "4");
          // } else {
          //   showMessage(response.mensaje, "2", "flex");
          // }
        },
        error: function (xhr, status, error) {
          console.log(error);
          // disableImportButton(false);
          // showLoader(false);
          // alert(
          //   "Ocurrió un error al procesar la solicitud. Detalles: " +
          //     xhr.responseText
          // );
          // console.error("Error details: ", xhr.responseText);
        },
      });
    });
  
    function disableImportButton(disabled) {
      $(".Btnimport").prop("disabled", disabled);
    }
  
    function showLoader(show) {
      $(".loader").css("display", show ? "block" : "none");
    }
  
    function showMessage(message, elementId, displayValue) {
      $("#" + elementId + " .descripcion").text(message);
      $("#" + elementId).css("display", displayValue);
      setTimeout(function () {
        $("#" + elementId).css("display", "none");
        location.reload();
      }, 5000);
    }
  
    function showWarningMessage(message, elementId) {
      showMessage(message, elementId, "flex");
    }
  });
  