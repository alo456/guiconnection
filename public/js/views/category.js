var url = "http://localhost/guiconnection/public/admin/menu";

function deleteMenu(id){
    var data = {}; 
    data['action'] = 'deleteMenu';
    data['id_menu'] = id;
    console.log(data);
    bootbox.dialog({
        className: 'modal',
        closeButton: false,
        title: 'Confirmación de Cambios',
        message: "<p>Al eliminar este menú, se eliminaran todos los menús relacionados a él. ¿Seguro qué quieres continuar?</p>",
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
                            data = JSON.parse(data);
                            console.log(data);
                            if (data['status'] == 'OK') {

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
                                                location.reload();
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

