<?php
// Vista del catálogo: hero (solo en el inicio limpio), píldoras de
// categorías, filtros laterales (si hay categoría) y rejilla de productos.

$productos = isset($productos) ? $productos : [];
$categoria = isset($categoria) ? $categoria : '';
$buscar = isset($buscar) ? $buscar : '';
$marcas_disponibles = isset($marcas_disponibles) ? $marcas_disponibles : [];
$marcas_elegidas = isset($marcas_elegidas) ? $marcas_elegidas : [];
$rangos_elegidos = isset($rangos_elegidos) ? $rangos_elegidos : [];
$q = $buscar;

$rangos_lista = [
    'menos100' => 'Menos de 100 €',
    '100_300' => '100 € - 300 €',
    '300_600' => '300 € - 600 €',
    'mas600' => 'Más de 600 €',
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CALIDAD-PRECIO</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/style_inicio.css">
</head>
<body>
    <?php require __DIR__ . '/../plantillas/header.php'; ?>

    <nav>
        <div class="contenedor menu-interior">
            <?php foreach (categoriasMenu() as $categoria_item): ?>
                <a href="index.php?c=inicio&a=inicio&cat=<?= urlencode($categoria_item) ?>"
                   class="pildora <?= ($categoria == $categoria_item) ? 'activa' : '' ?>">
                    <?= htmlspecialchars($categoria_item) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </nav>

    <main>
        <?php if ($categoria == '' && $buscar == ''): ?>
            <div class="hero">
                <img src="img/logo.png" alt="CALIDAD-PRECIO" class="hero-logo">
                <div class="hero-texto">
                    <h2 class="hero-titulo">Encuentra la tecnología que encaja contigo</h2>
                    <p class="hero-subtitulo">
                        Productos con la mejor relación
                        <span class="hero-calidad">calidad</span>-<span class="hero-precio">precio</span>,
                        recomendados para ti
                    </p>
                </div>
            </div>
        <?php endif; ?>

        <div class="distribucion">

            <?php if ($categoria != ''): ?>
                <aside class="filtros">
                    <h2>Filtros</h2>
                    <form method="get" action="index.php">
                        <input type="hidden" name="c" value="inicio">
                        <input type="hidden" name="a" value="inicio">
                        <input type="hidden" name="cat" value="<?= htmlspecialchars($categoria) ?>">

                        <?php if (!empty($marcas_disponibles)): ?>
                            <h3>Marca</h3>
                            <?php foreach ($marcas_disponibles as $marca): ?>
                                <label class="filtro-opcion">
                                    <input type="checkbox" name="marcas[]"
                                           value="<?= $marca['id_marca'] ?>"
                                           <?= in_array($marca['id_marca'], $marcas_elegidas) ? 'checked' : '' ?>>
                                    <?= htmlspecialchars($marca['nombre_marca']) ?>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <h3>Precio</h3>
                        <?php foreach ($rangos_lista as $valor => $etiqueta): ?>
                            <label class="filtro-opcion">
                                <input type="checkbox" name="rangos[]"
                                       value="<?= $valor ?>"
                                       <?= in_array($valor, $rangos_elegidos) ? 'checked' : '' ?>>
                                <?= $etiqueta ?>
                            </label>
                        <?php endforeach; ?>

                        <button type="submit" class="boton-filtrar">Filtrar</button>
                        <a class="limpiar-filtros"
                           href="index.php?c=inicio&a=inicio&cat=<?= urlencode($categoria) ?>">
                            Quitar filtros
                        </a>
                    </form>
                </aside>
            <?php endif; ?>

            <div class="zona-productos">

                <?php if ($buscar != ''): ?>
                    <h1 class="titulo-principal">Resultado de la búsqueda:</h1>
                <?php elseif (!empty($productos)): ?>
                    <h1 class="titulo-principal">
                        <?= $categoria != '' ? htmlspecialchars($categoria) : 'Recomendaciones recientes' ?>
                    </h1>
                    <p class="descripcion-principal">
                        Descubre los productos tecnológicos con la mejor relación calidad-precio,
                        seleccionados y recomendados para ti.
                    </p>
                <?php endif; ?>

                <?php if (empty($productos)): ?>
                    <p class="sin-resultados">No se encontraron productos.</p>
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
            </div>

        </div>
    </main>

    <?php require __DIR__ . '/../plantillas/footer.php'; ?>
</body>
</html>
