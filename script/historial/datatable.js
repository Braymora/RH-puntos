$(document).ready(function () {
  let table = $("#example").DataTable({
    searching: false,
    autoWidth: true,
    dom: "Bfrtilp",
    buttons: [
      {
        extend: "excel",
        footer: true,
        title: "Archivo",
        filename: "Export_File",
        text: '<button class="buttons optionsButtonsExporte" id="exportExcelButton"><img src="../../../assets/icons/share.svg" alt=""> Exportar Excel</button>',
      },
    ],
    language: {
      url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
    },
    footerCallback: function (row, data, start, end, display) {
      var api = this.api(),
        data;

      // Remover el formato para obtener datos enteros para la suma
      var intVal = function (i) {
        return typeof i === "string"
          ? i.replace(/[\$,]/g, "") * 1
          : typeof i === "number"
          ? i
          : 0;
      };

      total = this.api()
      .column(11) //numero de columna a sumar
      //.column(1, {page: 'current'})//para sumar solo la pagina actual
      .data()
      .reduce(function (a, b) {
        return parseInt(a) + parseInt(b);
      }, 0);

    $(this.api().column(1).footer()).html(total);

      
    },
  });

  $("#customSearch").on("keyup", function () {
    var searchTerm = this.value.toLowerCase();
    $("#example tbody tr").hide();
    $("#example tbody tr")
      .filter(function () {
        var lineStr = $(this).text().toLowerCase();
        return lineStr.indexOf(searchTerm) !== -1;
      })
      .show();
  });
});
