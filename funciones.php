<?php
// Funciones auxiliares: roles, sesión, mensajes, validaciones e imágenes.

// IDs de los roles (deben coincidir con la tabla roles de la BD)
define('ROL_ADMIN', 1);
define('ROL_EMPLEADO', 2);
define('ROL_USUARIO', 3);

// Sesión y permisos

function estaLogueado() {
    return isset($_SESSION['id_usuario']);
}

function esAdmin() {
    return estaLogueado() && $_SESSION['id_rol'] == ROL_ADMIN;
}

function esEmpleado() {
    return estaLogueado() && $_SESSION['id_rol'] == ROL_EMPLEADO;
}

function puedeEditar() {
    return esAdmin() || esEmpleado();
}

function pideLogin() {
    if (!estaLogueado()) {
        header('Location: index.php?c=acceso&a=entrar');
        exit;
    }
}

function pideAdmin() {
    pideLogin();
    if (!esAdmin()) {
        header('Location: index.php');
        exit;
    }
}

function pideEmpleadoOAdmin() {
    pideLogin();
    if (!puedeEditar()) {
        header('Location: index.php');
        exit;
    }
}

// Mensajes flash (se muestran una vez y se borran al imprimirse)

function guardarMensaje($tipo, $texto) {
    $_SESSION['mensaje'] = ['tipo' => $tipo, 'texto' => $texto];
}

// Validaciones

// Comprueba la contraseña del registro. Sigue el mismo patrón que
// guardarImagen: devuelve '' si está bien o el mensaje de error.
function errorPassw($passw) {
    if (strlen($passw) < 8) {
        return 'La contraseña debe tener al menos 8 caracteres.';
    }
    if (!preg_match('/[0-9]/', $passw)) {
        return 'La contraseña debe contener al menos un número.';
    }
    return '';
}

// Categorías del menú principal. Centralizadas aquí para no duplicarlas
// entre el inicio (píldoras de escritorio) y la hamburguesa (móvil).
function categoriasMenu() {
    return ['Móviles', 'Portátiles', 'Componentes', 'Monitores', 'Periféricos'];
}

function etiquetasDisponibles() {
    return ['Mejor calidad-precio', 'Muy recomendado', 'Chollo'];
}

// Imágenes de productos 

if (!defined('DIR_PUBLICO')) {
    define('DIR_PUBLICO', __DIR__ . '/public');
}

// Devuelve la URL pública de la imagen del producto. Si no encuentra
// archivo con ninguna extensión permitida, devuelve el placeholder.
function imagenProducto($producto) {
    $marca = isset($producto['nombre_marca']) ? strtolower($producto['nombre_marca']) : '';
    $nombre = isset($producto['nombre_producto']) ? strtolower(str_replace(' ', '_', $producto['nombre_producto'])) : '';
    $extensiones = ['png', 'jpg', 'jpeg', 'webp'];

    foreach ($extensiones as $extension) {
        $ruta = "img/marcas/$marca/$nombre.$extension";
        if (file_exists(DIR_PUBLICO . "/$ruta")) {
            return $ruta;
        }
    }
    return 'img/placeholder.png';
}

// Guarda la imagen subida en public/img/marcas/<marca>/. Antes de
// escribir, borra cualquier versión previa con otra extensión para no
// acumular duplicados. Devuelve '' si todo bien o un mensaje de error.
function guardarImagen($archivo, $nombre_marca, $nombre_producto) {
    if (empty($archivo['name'])) {
        return '';
    }
    if ($archivo['error'] != UPLOAD_ERR_OK) {
        return 'Error al subir la imagen.';
    }

    $permitidas = ['png', 'jpg', 'jpeg', 'webp'];
    $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, $permitidas)) {
        return 'Formato no permitido (png, jpg, jpeg, webp).';
    }
    if ($archivo['size'] > 5 * 1024 * 1024) {
        return 'La imagen no puede superar 5 MB.';
    }

    $carpeta = strtolower(trim($nombre_marca));
    $fichero = strtolower(str_replace(' ', '_', trim($nombre_producto)));
    $ruta_carpeta = DIR_PUBLICO . "/img/marcas/$carpeta";

    if (!is_dir($ruta_carpeta)) {
        mkdir($ruta_carpeta, 0755, true);
    }

    // Limpiamos cualquier archivo previo con el mismo nombre y otra
    // extensión para que no se quede una imagen "fantasma".
    foreach ($permitidas as $ext) {
        $previo = "$ruta_carpeta/$fichero.$ext";
        if (file_exists($previo)) {
            unlink($previo);
        }
    }

    $destino = "$ruta_carpeta/$fichero.$extension";
    if (!move_uploaded_file($archivo['tmp_name'], $destino)) {
        return 'No se pudo guardar la imagen.';
    }
    return '';
}

// Si se cambia el nombre del producto, hay que mover el archivo de
// imagen en disco para que la ruta de imagenProducto siga coincidiendo.
function renombrarImagen($nombre_marca, $nombre_viejo, $nombre_nuevo) {
    if ($nombre_viejo == $nombre_nuevo) {
        return;
    }

    $marca = strtolower($nombre_marca);
    $viejo = strtolower(str_replace(' ', '_', $nombre_viejo));
    $nuevo = strtolower(str_replace(' ', '_', $nombre_nuevo));

    foreach (['png', 'jpg', 'jpeg', 'webp'] as $extension) {
        $ruta_vieja = DIR_PUBLICO . "/img/marcas/$marca/$viejo.$extension";
        $ruta_nueva = DIR_PUBLICO . "/img/marcas/$marca/$nuevo.$extension";
        if (file_exists($ruta_vieja)) {
            rename($ruta_vieja, $ruta_nueva);
            return;
        }
    }
}
