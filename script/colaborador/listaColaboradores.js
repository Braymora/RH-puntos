$(document).ready(function () {
    var table = $('#table_colaborador').DataTable({
        searching: true,
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

        // Aplica la búsqueda en todas las páginas
        table.search(searchTerm).draw();
    });
});


//enviar datos al modal
$('.buttonEdit').on('click', function () {
    $tr = $(this).closest('tr');
    var datos = $tr.find('td').map(function () {
        return $(this).text();
    });

    $('#id_colaborador').val(datos[0]);
    $('#cedula').val(datos[1]);
    $('#nombre_colaborador').val(datos[2]);
    $('#correo').val(datos[3]);
    $('#id_ceco').val(datos[4]);
    $('#id_cargo').val(datos[5]);
    $('#contratante').val(datos[6]);
    $('#id_ciudad').val(datos[7]);
    $('#direccion').val(datos[8]);
    $('#fecha_ingreso').val(datos[10]);
    $('#Estado').val(datos[11]);
    $('#Observacione').val(datos[12]);
});