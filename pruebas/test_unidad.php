<?php
// Batería de pruebas de unidad
// Comprueba funciones y métodos sueltos

require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../funciones.php';
require_once __DIR__ . '/../modelos/producto.php';
require_once __DIR__ . '/../modelos/marca.php';

// Pinta "OK" en verde o "FALLO" en rojo según se cumpla la condición.
function comprobar($descripcion, $condicion) {
    if ($condicion) {
        echo "<p style='color: green;'>OK - $descripcion</p>";
    } else {
        echo "<p style='color: red;'>FALLO - $descripcion</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pruebas de unidad - CALIDAD-PRECIO</title>
</head>
<body>
    <h1>Pruebas de unidad</h1>

    <h2>Función imagenProducto()</h2>
    <?php
    // Un producto cuya imagen no existe en disco debe devolver el placeholder.
    $ruta = imagenProducto(['nombre_marca' => 'inexistente', 'nombre_producto' => 'inexistente']);
    comprobar("Devuelve el placeholder cuando el producto no tiene imagen", $ruta == 'img/placeholder.png');
    ?>

    <h2>Validación de contraseña: errorPassw()</h2>
    <?php
    comprobar("Rechaza una contraseña corta ('abc')", errorPassw('abc') != '');
    comprobar("Rechaza una contraseña sin números ('abcdefgh')", errorPassw('abcdefgh') != '');
    comprobar("Acepta una contraseña válida ('abcdef12')", errorPassw('abcdef12') == '');
    ?>

    <h2>Modelo Marca</h2>
    <?php
    $modeloMarca = new Marca($pdo);

    // Con un ID que no existe, obtenerNombre debe devolver cadena vacía.
    comprobar("obtenerNombre() devuelve '' con un ID inexistente", $modeloMarca->obtenerNombre(999999) === '');

    // Con la primera marca real de la BD, debe devolver su nombre.
    $marcas = $modeloMarca->listar();
    if (!empty($marcas)) {
        $primera = $marcas[0];
        $nombre = $modeloMarca->obtenerNombre($primera['id_marca']);
        comprobar("obtenerNombre() devuelve el nombre correcto de una marca real", $nombre === $primera['nombre_marca']);
    }
    ?>

    <h2>Modelo Producto</h2>
    <?php
    $modeloProducto = new Producto($pdo);

    // Un ID que no existe debe devolver false.
    comprobar("obtenerPorId() devuelve false con un ID inexistente", $modeloProducto->obtenerPorId(999999) === false);
    ?>

</body>
</html>
