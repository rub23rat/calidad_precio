<?php
// Detalle de un producto: imagen, nombre, marca, categoría, precio,
// descripción y botones de compra y de favorito (este último solo si
// hay sesión iniciada).

$producto = isset($producto) ? $producto : [];
$imagen = isset($imagen) ? $imagen : '';
$esFavorito = isset($esFavorito) ? $esFavorito : false;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($producto['nombre_producto']) ?> - CALIDAD-PRECIO</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/style_producto.css">
</head>
<body>
    <?php require __DIR__ . '/../plantillas/header.php'; ?>

    <main>
        <div class="producto-detalle">
            <div class="producto-imagen">
                <img src="<?= htmlspecialchars($imagen) ?>"
                     alt="<?= htmlspecialchars($producto['nombre_producto']) ?>">
            </div>

            <div class="producto-info">
                <h1 class="producto-nombre">
                    <?= htmlspecialchars($producto['nombre_producto']) ?>
                </h1>

                <p class="producto-meta">
                    <?= isset($producto['nombre_marca']) ? htmlspecialchars($producto['nombre_marca']) : '' ?>
                    <?php if (!empty($producto['nombre_categoria'])): ?>
                        · <?= htmlspecialchars($producto['nombre_categoria']) ?>
                    <?php endif; ?>
                </p>

                <p class="producto-precio">
                    <?= number_format($producto['precio'], 2, ',', '.') ?> &euro;
                </p>

                <p class="producto-descripcion">
                    <?= isset($producto['descripcion']) ? nl2br(htmlspecialchars($producto['descripcion'])) : '' ?>
                </p>

                <div class="producto-acciones">
                    <a href="<?= htmlspecialchars($producto['url_compra']) ?>"
                       target="_blank" rel="noopener" class="boton-compra">
                        Enlace Compra
                    </a>

                    <form method="post" action="index.php?c=favorito&a=alternar">
                        <input type="hidden" name="id_producto"
                               value="<?= $producto['id_producto'] ?>">
                        <button type="submit"
                                class="boton-favorito <?= $esFavorito ? 'activo' : '' ?>"
                                title="<?= $esFavorito ? 'Quitar de favoritos' : 'Guardar en favoritos' ?>">
                            <svg viewBox="0 0 24 24" width="30" height="30"
                                 fill="<?= $esFavorito ? 'currentColor' : 'none' ?>"
                                 stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06
                                         a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84
                                         a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php require __DIR__ . '/../plantillas/footer.php'; ?>
</body>
</html>
