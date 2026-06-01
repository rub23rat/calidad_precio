<?php // Cabecera del panel admin/empleado. Más simple que la pública: solo logo y menú de usuario. ?>
<header>
    <div class="contenedor cabecera-interior">
        <a href="index.php" class="logo">
            <img src="img/logo.png" alt="CALIDAD-PRECIO">
        </a>

        <div class="usuario">
            <div class="usuario-menu">
                <span class="usuario-pildora">
                    <?= htmlspecialchars($_SESSION['nombre']) ?>
                </span>
                <div class="desplegable">
                    <a href="index.php?c=panel&a=listar">Panel</a>
                    <a href="index.php?c=favorito&a=listar">Productos guardados</a>
                    <a href="index.php?c=acceso&a=salir">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </div>
</header>
