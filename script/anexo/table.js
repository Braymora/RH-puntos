//función para mostrar los datos en la tabla de colaboradores
$(document).ready(function () {
    var table = $("#table_colaborador").DataTable({
        searching: true,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
        },
        autoWidth: true,
        processing: true,
        dom: "lBfrtip", // Aquí se configuran los elementos que quieres en la tabla
        buttons: [
            {
                extend: "excel",
                footer: true,
                title: "Archivo",
                filename: "Export_File",
                text: '<button class="buttons optionsButtonsExporte" id="exportExcelButton"><img src="../../../assets/icons/share.svg" alt=""> Exportar Excel</button>',
            },
        ],
    });
});
