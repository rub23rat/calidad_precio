<?php
// Listado de productos guardados como favoritos por el usuario actual.

$productos = isset($productos) ? $productos : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos guardados - CALIDAD-PRECIO</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/style_inicio.css">
</head>
<body>
    <?php require __DIR__ . '/../plantillas/header.php'; ?>

    <main>
        <h1 class="titulo-principal">Productos guardados</h1>
        <p class="descripcion-principal">
            Aquí tienes los productos que has guardado como favoritos.
        </p>

        <?php if (empty($productos)): ?>
            <p class="sin-resultados">Todavía no has guardado ningún producto.</p>
        <?php else: ?>
            <div class="rejilla-productos">
                <?php foreach ($productos as $producto): ?>
                    <?php $imagen = imagenProducto($producto); ?>
                    <a href="index.php?c=inicio&a=detalle&id=<?= $producto['id_producto'] ?>"
                       class="tarjeta">
                        <div class="tarjeta-imagen">
                            <img src="<?= htmlspecialchars($imagen) ?>"
                                 alt="<?= htmlspecialchars($producto['nombre_producto']) ?>">
                        </div>
                        <div class="tarjeta-cuerpo">
                            <h3 class="tarjeta-titulo">
                                <?= htmlspecialchars($producto['nombre_producto']) ?>
                            </h3>
                            <div class="tarjeta-pie">
                                <span class="tarjeta-precio">
                                    <?= number_format($producto['precio'], 2, ',', '.') ?> &euro;
                                </span>
                                <?php if (!empty($producto['etiqueta'])): ?>
                                    <span class="tarjeta-etiqueta">
                                        <?= htmlspecialchars($producto['etiqueta']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <?php require __DIR__ . '/../plantillas/footer.php'; ?>
</body>
</html>
