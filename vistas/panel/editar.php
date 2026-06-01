<?php
// Formulario para editar un producto existente (admin/empleado).

$error = isset($error) ? $error : '';
$producto = isset($producto) ? $producto : [];
$categorias = isset($categorias) ? $categorias : [];
$marcas = isset($marcas) ? $marcas : [];
$imagen_actual = isset($imagen_actual) ? $imagen_actual : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar producto - CALIDAD-PRECIO</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/style_panel.css">
</head>
<body>
    <?php require __DIR__ . '/../plantillas/header_panel.php'; ?>

    <main>
        <div class="panel-cabecera">
            <h1>Editar producto</h1>
            <a href="index.php?c=panel&a=listar" class="boton-volver">Volver</a>
        </div>

        <?php if ($error != ''): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post"
              action="index.php?c=panel&a=editar&id=<?= $producto['id_producto'] ?>"
              class="formulario" enctype="multipart/form-data">

            <label for="nombre">Nombre del producto</label>
            <input type="text" id="nombre" name="nombre"
                   value="<?= htmlspecialchars($producto['nombre_producto']) ?>">

            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion"
                      rows="6"><?= isset($producto['descripcion']) ? htmlspecialchars($producto['descripcion']) : '' ?></textarea>

            <label for="url_compra">URL de compra</label>
            <input type="url" id="url_compra" name="url_compra"
                   value="<?= htmlspecialchars($producto['url_compra']) ?>">

            <label for="precio">Precio (€)</label>
            <input type="number" step="0.01" min="0" id="precio" name="precio"
                   value="<?= htmlspecialchars($producto['precio']) ?>">

            <label for="id_categoria">Categoría</label>
            <select id="id_categoria" name="id_categoria">
                <option value="">-- Selecciona --</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= $categoria['id_categoria'] ?>"
                        <?= $producto['id_categoria'] == $categoria['id_categoria'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($categoria['nombre_categoria']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="id_marca">Marca</label>
            <select id="id_marca" name="id_marca">
                <option value="">-- Selecciona --</option>
                <?php foreach ($marcas as $marca): ?>
                    <option value="<?= $marca['id_marca'] ?>"
                        <?= $producto['id_marca'] == $marca['id_marca'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($marca['nombre_marca']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="etiqueta">Etiqueta de recomendación</label>
            <select id="etiqueta" name="etiqueta">
                <option value="">-- Sin etiqueta --</option>
                <?php foreach (etiquetasDisponibles() as $opcion): ?>
                    <option value="<?= htmlspecialchars($opcion) ?>"
                        <?= $producto['etiqueta'] == $opcion ? 'selected' : '' ?>>
                        <?= htmlspecialchars($opcion) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Imagen actual</label>
            <div class="imagen-actual">
                <img src="<?= htmlspecialchars($imagen_actual) ?>"
                     alt="<?= htmlspecialchars($producto['nombre_producto']) ?>">
            </div>

            <label for="imagen">Cambiar imagen</label>
            <input type="file" id="imagen" name="imagen"
                   accept=".png,.jpg,.jpeg,.webp">
            <span class="ayuda">
                Déjalo vacío para conservar la imagen actual.
                Formatos: png, jpg, jpeg, webp (máx. 5 MB).
            </span>

            <button type="submit" class="boton-añadir">Guardar cambios</button>
        </form>
    </main>

    <?php require __DIR__ . '/../plantillas/footer.php'; ?>
</body>
</html>
