<?php
// Gestión de usuarios (solo admin): permite cambiar el rol o borrar
// usuarios. La cuenta del propio admin no se puede modificar.

$usuarios = isset($usuarios) ? $usuarios : [];
$roles = isset($roles) ? $roles : [];
$miId = isset($miId) ? $miId : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - CALIDAD-PRECIO</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/style_panel.css">
</head>
<body>
    <?php require __DIR__ . '/../plantillas/header_panel.php'; ?>

    <main>
        <div class="panel-pestañas">
            <a href="index.php?c=panel&a=listar" class="pestaña">Productos</a>
            <a href="index.php?c=usuario&a=listar" class="pestaña activa">Usuarios</a>
        </div>

        <div class="panel-cabecera">
            <h1>Panel Administrador</h1>
        </div>

        <?php if (empty($usuarios)): ?>
            <p class="sin-resultados">No hay usuarios registrados.</p>
        <?php else: ?>
            <ul class="lista-productos">
                <?php foreach ($usuarios as $usuario): ?>
                    <?php $soy_yo = ($usuario['id_usuario'] == $miId); ?>
                    <li class="fila-producto">
                        <div class="fila-info">
                            <span class="fila-nombre">
                                <?= htmlspecialchars($usuario['nombre']) ?>
                                <?php if ($soy_yo): ?>
                                    <span class="fila-rol-actual">(Tú)</span>
                                <?php endif; ?>
                            </span>
                            <span class="fila-meta">
                                <?= htmlspecialchars($usuario['correo']) ?>
                                · <?= isset($usuario['nombre_rol']) ? htmlspecialchars($usuario['nombre_rol']) : '—' ?>
                            </span>
                        </div>
                        <div class="fila-acciones">
                            <?php if ($soy_yo): ?>
                                <span class="fila-rol-actual">
                                    No puedes modificar tu propia cuenta
                                </span>
                            <?php else: ?>
                                <form class="formulario-rol" method="post"
                                      action="index.php?c=usuario&a=listar">
                                    <input type="hidden" name="id_usuario"
                                           value="<?= $usuario['id_usuario'] ?>">
                                    <select name="id_rol">
                                        <?php foreach ($roles as $rol): ?>
                                            <option value="<?= $rol['id_rol'] ?>"
                                                <?= $usuario['id_rol'] == $rol['id_rol'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($rol['nombre_rol']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" name="cambiar_rol"
                                            value="1" class="boton-guardar">
                                        Guardar
                                    </button>
                                </form>
                                <a class="boton-borrar"
                                   href="index.php?c=usuario&a=listar&borrar=<?= $usuario['id_usuario'] ?>"
                                   onclick="return confirm('¿Seguro que quieres borrar a «<?= htmlspecialchars($usuario['nombre']) ?>»?');">
                                    Borrar
                                </a>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </main>

    <?php require __DIR__ . '/../plantillas/footer.php'; ?>
</body>
</html>
