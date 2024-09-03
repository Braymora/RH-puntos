$(document).ready(function () {

    $('#cedula').select2({
        placeholder: 'Selecciona una opción', // Texto del placeholder
    });

    $('#cedula').change(function () {
        var selectedCedula = $('#cedula').find(':selected');
        
        // Asignar el valor al campo '#name'
        $('#name').val(selectedCedula.data('nombrecolaborador'));
        
        // Asignar el valor al campo '#idcolaborador'
        $('#idcolaborador').val(selectedCedula.data('idcolaborador'));

        // Asignar el valor al campo '#namececo'
        $('#namececo').val(selectedCedula.data('idceco'));
    });

});
