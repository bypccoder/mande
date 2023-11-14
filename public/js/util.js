const CRUD = {
    getAll: (tabla) => {
        return fetch(`${tabla}`, {
            headers: {
                'Accept': 'application/json',
            }
        })
            .then(response => response.json())
            .catch(error => {
                console.error('Error:', error);
            });
    },

    create: (tabla, nuevoRegistro) => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        return fetch(`/${tabla}/store`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify(nuevoRegistro),
        })
            .then(response => response.json())
            .catch(error => {
                console.error('Error:', error);
            });
    },
    createNoJson: (tabla, nuevoRegistro) => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        return fetch(`/${tabla}/store`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            body: nuevoRegistro,
        })
            .then(response => response.json())
            .catch(error => {
                console.error('Error:', error);
            });
    },
    update: (tabla, id, registroActualizado) => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        return fetch(`/${tabla}/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(registroActualizado),
        })
            .then(response => response.json())
            .catch(error => {
                console.error('Error:', error);
            });
    },
    delete: (tabla, id) => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        return fetch(`/${tabla}/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        })
            .then(response => response.json())
            .catch(error => {
                console.error('Error:', error);
            });
    },
    sendData: (url, data) => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(data),
        })
            .then(response => response.json())
            .then(data => {
                // Aquí puedes realizar las acciones necesarias con los datos recibidos
                console.log('Datos recibidos:', data);
                return data; // Opcional: Puedes retornar los datos recibidos si necesitas utilizarlos en otro lugar
            })
            .catch(error => {
                console.error('Error:', error);
            });
    },
    sendDataNoJson: (url, data) => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        return fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            body: data,
        })
            .then(response => response.json())
            .catch(error => {
                console.error('Error:', error);
            });
    },
};


function mostrarMensajeAlerta(codigoRespuesta, mensajeAlerta = '') {
    let titulo, mensaje, icono;

    if (codigoRespuesta >= 200 && codigoRespuesta < 300) {
        // Estado SUCCESS (éxito)
        titulo = 'Éxito';
        mensaje = (!mensajeAlerta) ? 'La operación se realizó correctamente.' : mensajeAlerta;
        icono = 'success';
    } else if (codigoRespuesta >= 300 && codigoRespuesta < 400) {
        // Estado WARNING (advertencia)
        titulo = 'Advertencia';
        mensaje = (!mensajeAlerta) ? 'Se ha producido una advertencia.' : mensajeAlerta;
        icono = 'warning';
    } else if (codigoRespuesta >= 400 && codigoRespuesta < 500) {
        // Estado ERROR (error)
        titulo = 'Error';
        mensaje = (!mensajeAlerta) ? 'Se ha producido un error.' : mensajeAlerta;
        icono = 'error';
    } else {
        // Código de respuesta desconocido
        titulo = 'Mensaje';
        mensaje = (!mensajeAlerta) ? 'Respuesta del servidor desconocida.' : mensajeAlerta;
        icono = 'info';
    }

    Swal.fire({
        title: titulo,
        text: mensaje,
        icon: icono,
        button: 'Aceptar',
    });
}

function mostrarNotificacion(codigoRespuesta, mensajeAlerta = '') {
    let titulo, mensaje, icono;

    if (codigoRespuesta >= 200 && codigoRespuesta < 300) {
        // Estado SUCCESS (éxito)
        titulo = 'Éxito';
        mensaje = (!mensajeAlerta) ? 'La operación se realizó correctamente.' : mensajeAlerta;
        icono = 'success';
    } else if (codigoRespuesta >= 300 && codigoRespuesta < 400) {
        // Estado WARNING (advertencia)
        titulo = 'Advertencia';
        mensaje = (!mensajeAlerta) ? 'Se ha producido una advertencia.' : mensajeAlerta;
        icono = 'warning';
    } else if (codigoRespuesta >= 400 && codigoRespuesta < 500) {
        // Estado ERROR (error)
        titulo = 'Error';
        mensaje = (!mensajeAlerta) ? 'Se ha producido un error.' : mensajeAlerta;
        icono = 'error';
    } else {
        // Código de respuesta desconocido
        titulo = 'Mensaje';
        mensaje = (!mensajeAlerta) ? 'Respuesta del servidor desconocida.' : mensajeAlerta;
        icono = 'info';
    }

    Swal.fire({
        icon: icono,
        title: titulo,
        text: mensaje,
        toast: true,
        position: 'bottom',
        showConfirmButton: false,
        timer: 3000
    });
}
