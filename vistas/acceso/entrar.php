<?php
// Formulario de inicio de sesión.

$error = isset($error) ? $error : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - CALIDAD-PRECIO</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/style_acceso.css">
</head>
<body>
    <div class="tarjeta-acceso">
        <a href="index.php" class="logo-acceso">
            <img src="img/logo.png" alt="CALIDAD-PRECIO">
        </a>
        <h1>Iniciar Sesión</h1>

        <?php if ($error != ''): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post" action="index.php?c=acceso&a=entrar">
            <label for="correo">Correo</label>
            <input type="email" id="correo" name="correo"
                   value="<?= isset($_POST['correo']) ? htmlspecialchars($_POST['correo']) : '' ?>">

            <label for="passw">Contraseña</label>
            <input type="password" id="passw" name="passw">

            <button type="submit">Entrar</button>
        </form>

        <p class="enlace-acceso">
            ¿No tienes cuenta? <a href="index.php?c=acceso&a=registro">Regístrate</a>
        </p>
    </div>

    <script src="js/aplicacion.js"></script>
</body>
</html>
