var url = "http://localhost/guiconnection/public/admin/menu";

function deleteMenu(id){
    bootbox.dialog({
        className: 'modal',
        closeButton: false,
        title: 'Confirmación de Cambios',
        message: "<p>Al eliminar este menú, se eliminaran todos los submenús relacionados a él. ¿Seguro qué quieres continuar?</p>",
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
                        url: "http://localhost/guiconnection/public/admin/menu/delete/" + id ,
                        cache: false,
                        async: true,
                        success: function (data) {
                            if (data == 'OK') {
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
                                    message: "<p>Opps! Algo ha salido mal. Intente de nuevo.</p>",
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
                                message: "<p>Error, contacte con Soporte</p>",
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

function updateMenu(url){
    $.get(url, function (data) {
        bootbox.dialog({
            className: 'modal',
            closeButton: false,
            title: 'Editar Menu',
            message: data,
            onEscape: true
        });
        
    });         
}