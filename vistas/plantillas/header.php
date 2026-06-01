<?php
// Cabecera principal de la web (logo, buscador, menú de usuario, ayuda).
// En móvil se sustituye por una hamburguesa que abre un panel con el
// usuario, las categorías y la ayuda.
$q = isset($q) ? $q : '';
$cat_actual = isset($categoria) ? $categoria : '';
?>
<header>
    <div class="contenedor cabecera-interior">
        <div class="cabecera-izquierda">
            <button type="button" class="boton-menu" id="boton-menu" aria-label="Menú">☰</button>
            <a href="index.php" class="logo">
                <img src="img/logo.png" alt="CALIDAD-PRECIO">
            </a>
        </div>

        <form class="buscador" method="get" action="index.php">
            <input type="hidden" name="c" value="inicio">
            <input type="hidden" name="a" value="inicio">
            <input type="text" name="q" placeholder="Buscar productos..."
                   value="<?= htmlspecialchars($q) ?>">
            <button type="submit">Buscar</button>
        </form>

        <div class="usuario">
            <a href="index.php?c=inicio&a=ayuda" class="enlace-ayuda">Ayuda</a>
            <?php if (estaLogueado()): ?>
                <div class="usuario-menu">
                    <span class="usuario-pildora">
                        <?= htmlspecialchars($_SESSION['nombre']) ?>
                    </span>
                    <div class="desplegable">
                        <?php if (puedeEditar()): ?>
                            <a href="index.php?c=panel&a=listar">Panel</a>
                        <?php endif; ?>
                        <a href="index.php?c=favorito&a=listar">Productos guardados</a>
                        <a href="index.php?c=acceso&a=salir">Cerrar sesión</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="index.php?c=acceso&a=entrar" class="boton-acceso">Iniciar Sesión</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="menu-movil" id="menu-movil">
        <?php if (estaLogueado()): ?>
            <span class="menu-movil-titulo"><?= htmlspecialchars($_SESSION['nombre']) ?></span>
            <?php if (puedeEditar()): ?>
                <a href="index.php?c=panel&a=listar">Panel</a>
            <?php endif; ?>
            <a href="index.php?c=favorito&a=listar">Productos guardados</a>
            <a href="index.php?c=acceso&a=salir">Cerrar sesión</a>
        <?php else: ?>
            <span class="menu-movil-titulo">Cuenta</span>
            <a href="index.php?c=acceso&a=entrar">Iniciar Sesión</a>
        <?php endif; ?>

        <span class="menu-movil-titulo">Categorías</span>
        <?php foreach (categoriasMenu() as $cat): ?>
            <a href="index.php?c=inicio&a=inicio&cat=<?= urlencode($cat) ?>"
               class="<?= $cat_actual == $cat ? 'activa' : '' ?>">
                <?= htmlspecialchars($cat) ?>
            </a>
        <?php endforeach; ?>

        <span class="menu-movil-titulo">Ayuda</span>
        <a href="index.php?c=inicio&a=ayuda">Manual de usuario</a>
    </div>
</header>
