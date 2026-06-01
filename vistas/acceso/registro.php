<?php
// Formulario de registro de un nuevo usuario.

$error = isset($error) ? $error : '';
$exito = isset($exito) ? $exito : false;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - CALIDAD-PRECIO</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/style_acceso.css">
</head>
<body>
    <div class="tarjeta-acceso">
        <a href="index.php" class="logo-acceso">
            <img src="img/logo.png" alt="CALIDAD-PRECIO">
        </a>
        <h1>Crear Cuenta</h1>

        <?php if ($exito): ?>
            <p class="exito">¡Cuenta creada correctamente!</p>
            <a href="index.php?c=acceso&a=entrar" class="boton-bloque">
                Ir al inicio de sesión
            </a>
        <?php else: ?>
            <?php if ($error != ''): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form method="post" action="index.php?c=acceso&a=registro">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre"
                       value="<?= isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : '' ?>">

                <label for="correo">Correo</label>
                <input type="email" id="correo" name="correo"
                       value="<?= isset($_POST['correo']) ? htmlspecialchars($_POST['correo']) : '' ?>">

                <label for="passw">Contraseña</label>
                <input type="password" id="passw" name="passw">

                <label for="passw2">Repetir contraseña</label>
                <input type="password" id="passw2" name="passw2">

                <button type="submit">Registrarse</button>
            </form>

            <p class="enlace-acceso">
                ¿Ya tienes cuenta?
                <a href="index.php?c=acceso&a=entrar">Inicia sesión</a>
            </p>
        <?php endif; ?>
    </div>

    <script src="js/aplicacion.js"></script>
</body>
</html>
