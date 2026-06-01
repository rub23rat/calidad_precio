// Muestra un mensaje en pantalla durante 3,5 segundos.
function mostrarMensaje(texto, tipo) {
    if (!tipo) {
        tipo = 'correcto';
    }

    // Si ya hay un mensaje, lo quitamos
    var anterior = document.getElementById('aviso');
    if (anterior) {
        anterior.remove();
    }

    var aviso = document.createElement('div');
    aviso.id = 'aviso';
    aviso.className = 'aviso aviso-' + tipo + ' aviso-visible';
    aviso.textContent = texto;
    document.body.appendChild(aviso);

    setTimeout(function () {
        aviso.classList.remove('aviso-visible');
        setTimeout(function () {
            aviso.remove();
        }, 400);
    }, 3500);
}

document.addEventListener('DOMContentLoaded', function () {

    // Animar el mensaje que viene del servidor (mensaje de sesión)
    var avisoServidor = document.getElementById('aviso');
    if (avisoServidor) {
        avisoServidor.classList.add('aviso-visible');
        setTimeout(function () {
            avisoServidor.classList.remove('aviso-visible');
            setTimeout(function () {
                avisoServidor.remove();
            }, 400);
        }, 3500);
    }

    // Abrir/cerrar el menú móvil (hamburguesa)
    var botonMenu = document.getElementById('boton-menu');
    var menuMovil = document.getElementById('menu-movil');
    if (botonMenu && menuMovil) {
        botonMenu.addEventListener('click', function () {
            menuMovil.classList.toggle('abierto');
        });
    }

    // Borrar producto con AJAX
    var botones = document.querySelectorAll('.boton-borrar-ajax');

    botones.forEach(function (boton) {
        boton.addEventListener('click', function () {
            var id = boton.dataset.id;
            var nombre = boton.dataset.nombre;

            if (!confirm('¿Seguro que quieres borrar "' + nombre + '"?')) {
                return;
            }

            var datos = new FormData();
            datos.append('id', id);

            fetch('index.php?c=panel&a=borrar', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: datos
            })
            .then(function (respuesta) {
                return respuesta.json();
            })
            .then(function (resultado) {
                if (resultado.ok) {
                    var fila = document.querySelector('.fila-producto[data-id="' + id + '"]');
                    if (fila) {
                        fila.remove();
                    }
                    mostrarMensaje('Producto "' + resultado.nombre + '" eliminado correctamente.');
                } else {
                    mostrarMensaje('No se pudo borrar el producto.', 'error');
                }
            })
            .catch(function () {
                mostrarMensaje('Error de conexión.', 'error');
            });
        });
    });

});
