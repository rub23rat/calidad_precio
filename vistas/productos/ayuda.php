<?php
// Página de ayuda que explica cómo usar la web
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayuda - CALIDAD-PRECIO</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/style_inicio.css">
</head>
<body>
    <?php require __DIR__ . '/../plantillas/header.php'; ?>

    <main>
        <div class="ayuda">
            <h1>Manual de usuario</h1>
            <p>¿Cómo usar CALIDAD-PRECIO? Aquí tienes una guía rápida para sacarle partido.</p>

            <h2>¿Qué es CALIDAD-PRECIO?</h2>
            <p>
                Es un catálogo de productos tecnológicos seleccionados por su buena
                relación entre calidad y precio. Encontrarás móviles, portátiles,
                componentes, monitores y periféricos.
            </p>

            <h2>Buscar y filtrar productos</h2>
            <p>
                Usa el buscador de la parte superior para encontrar un producto por su
                nombre. También puedes pulsar en una categoría del menú (Móviles,
                Portátiles, etc.) para ver solo los productos de ese tipo. Dentro de una
                categoría aparece un panel de filtros a la izquierda: marca las casillas
                de marca de producto o de rango de precio que quieras y pulsa "Filtrar".
            </p>

            <h2>Ver el detalle de un producto</h2>
            <p>
                Haz clic en cualquier producto del catálogo para abrir la página de detalle. Ahí
                verás la imagen, el nombre, la marca, la categoría, el precio, la
                descripción y un enlace externo para comprarlo. También puedes guardar el producto 
                en tus favoritos (si has iniciado sesión).
            </p>

            <h2>Registrarse e iniciar sesión</h2>
            <p>
                Pulsa en "Iniciar Sesión" arriba a la derecha. Si todavía no tienes
                cuenta, usa el enlace de "Regístrate" para crear una en la cual necesitarás 
                rellenar los campos con un nombre, un correo y una contraseña (mínimo 8 caracteres y al menos un número).
                Una vez registrado, podrás entrar con tu correo y tu contraseña.
            </p>

            <h2>Guardar productos en favoritos</h2>
            <p>
                Cuando inicias sesión, en la ficha de cada producto aparece un botón con
                forma de corazón. Púlsalo para guardar el producto en tu lista de
                favoritos. Puedes consultarlos desde el menú de tu usuario, en "Productos
                guardados". Si vuelves a pulsar el corazón, el producto se quita de la lista.
            </p>
        </div>
    </main>

    <?php require __DIR__ . '/../plantillas/footer.php'; ?>
</body>
</html>
