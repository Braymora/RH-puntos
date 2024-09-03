$(document).ready(function () {
    var table = $('#table_colaborador').DataTable({
        searching: false,
        autoWidth: true,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
        },
        autoWidth: true,
        processing: true,
        dom: 'Bfrtip', // Aquí se configuran los elementos que quieres en la tabla
        buttons: [{
            extend: 'excel',
            footer: true,
            title: 'Archivo',
            filename: 'Export_File',
            text: '<button class="buttons optionsButtonsExporte" id="exportExcelButton"><img src="../../../assets/icons/share.svg" alt=""> Exportar Excel</button>'
        }]
    });

    $('#customSearch').on('keyup', function () {
        var searchTerm = this.value.toLowerCase();
        $('#table_colaborador tbody tr').hide();
        $('#table_colaborador tbody tr').filter(function () {
            var lineStr = $(this).text().toLowerCase();
            return lineStr.indexOf(searchTerm) !== -1;
        }).show();
    });
});
