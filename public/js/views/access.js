var url = "http://localhost/taiuniversityapi/public/admin";

function deleteEmployee(button){
    var data = $(button).data('data');
    data['action'] = 'deleteEmployee';
    bootbox.dialog({
        className: 'modal',
        closeButton: false,
        title: 'Confirmación de promocion',
        message: "<p>Confirma la configuración que has realizado, si no estas seguro oprime cancelar o cierra esta ventana.</p>",
        buttons: {
            Cancelar: {
                label: 'Cancelar',
                className: 'btn-secondary',
                callback: function () {
                }
            },
            Guardar: {
                label: 'Confirmar Cambios',
                className: 'btn-primary',
                callback: function () {
                    $.ajax({
                        type: 'POST',
                        data: data,
                        url: url,
                        cache: false,
                        async: true,
                        success: function (data) {
                            console.log(data);
                            if (data.status == 'OK') {

                                bootbox.dialog({
                                    className: 'modal-success',
                                    closeButton: false,
                                    title: '<i class="fa fa-check fa-lg"></i>Cambios Confirmados',
                                    message: "<p>Se han realizado los cambios</p>",
                                    buttons: {
                                        ok: {
                                            label: '<i class="fa fa-check"></i> Ok',
                                            className: 'btn-success',
                                            callback: function () {

                                            }
                                        }
                                    }
                                });
                            } else {
                                bootbox.dialog({
                                    className: 'modal-danger',
                                    closeButton: false,
                                    title: '<i class="fa fa-times fa-lg"></i>Error',
                                    message: "<p>No se han podido realizar los cambios, verifica que los campos esten correctos e intente de nuevo</p>",
                                    buttons: {
                                        ok: {
                                            label: '<i class="fa fa-times"></i> Ok',
                                            className: 'btn-danger'
                                        }
                                    }
                                });
                            }
                        },
                        error: function () {
                            bootbox.dialog({
                                className: 'modal-danger',
                                closeButton: false,
                                title: '<i class="fa fa-times fa-lg"></i>Error del Sistema',
                                message: "<p>No se han podido realizar los cambios, contacte con el Administrador</p>",
                                buttons: {
                                    ok: {
                                        label: '<i class="fa fa-times"></i> Ok',
                                        className: 'btn-danger'
                                    }
                                }
                            });
                        }
                    });
                }

            }
        }
    });
}


