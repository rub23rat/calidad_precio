<?php
// Panel de productos (vista de admin/empleado). Lista todos los productos
// con botones de editar y borrar, e incluye los botones de "Añadir
// producto" y "Descargar informe PDF" en la cabecera.

$productos = isset($productos) ? $productos : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel - CALIDAD-PRECIO</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/style_panel.css">
</head>
<body>
    <?php require __DIR__ . '/../plantillas/header_panel.php'; ?>

    <main>
        <?php if (esAdmin()): ?>
            <div class="panel-pestañas">
                <a href="index.php?c=panel&a=listar" class="pestaña activa">Productos</a>
                <a href="index.php?c=usuario&a=listar" class="pestaña">Usuarios</a>
            </div>
        <?php endif; ?>

        <div class="panel-cabecera">
            <h1>
                <?php if (esAdmin()): ?>
                    Panel Administrador
                <?php else: ?>
                    Panel Empleado
                <?php endif; ?>
            </h1>
            <div class="panel-cabecera-acciones">
                <a href="index.php?c=panel&a=informeProductos" class="boton-añadir">
                    Descargar informe PDF
                </a>
                <a href="index.php?c=panel&a=crear" class="boton-añadir">
                    Añadir producto
                </a>
            </div>
        </div>

        <?php if (empty($productos)): ?>
            <p class="sin-resultados">No hay productos registrados.</p>
        <?php else: ?>
            <ul class="lista-productos" id="lista-productos">
                <?php foreach ($productos as $producto): ?>
                    <?php $imagen = imagenProducto($producto); ?>
                    <li class="fila-producto" data-id="<?= $producto['id_producto'] ?>">
                        <img class="mini-imagen"
                             src="<?= htmlspecialchars($imagen) ?>"
                             alt="<?= htmlspecialchars($producto['nombre_producto']) ?>">
                        <div class="fila-info">
                            <span class="fila-nombre">
                                <?= htmlspecialchars($producto['nombre_producto']) ?>
                            </span>
                            <span class="fila-meta">
                                <?= isset($producto['nombre_marca']) ? htmlspecialchars($producto['nombre_marca']) : '—' ?>
                                · <?= isset($producto['nombre_categoria']) ? htmlspecialchars($producto['nombre_categoria']) : '—' ?>
                                · <?= number_format($producto['precio'], 2, ',', '.') ?> &euro;
                            </span>
                        </div>
                        <div class="fila-acciones">
                            <a class="boton-editar"
                               href="index.php?c=panel&a=editar&id=<?= $producto['id_producto'] ?>">
                                Editar
                            </a>
                            <button type="button" class="boton-borrar boton-borrar-ajax"
                                    data-id="<?= $producto['id_producto'] ?>"
                                    data-nombre="<?= htmlspecialchars($producto['nombre_producto']) ?>">
                                Borrar
                            </button>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </main>

    <?php require __DIR__ . '/../plantillas/footer.php'; ?>
</body>
</html>
