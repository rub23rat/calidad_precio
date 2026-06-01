<?php if (!empty($_SESSION['mensaje'])): ?>
    <div id="aviso" class="aviso aviso-<?= htmlspecialchars($_SESSION['mensaje']['tipo']) ?>">
        <?= htmlspecialchars($_SESSION['mensaje']['texto']) ?>
    </div>
    <?php unset($_SESSION['mensaje']); ?>
<?php endif; ?>

<footer>
    <img src="img/logo.png" alt="CALIDAD-PRECIO">
    <p>&copy; <?= date('Y') ?> CALIDAD-PRECIO. Todos los derechos reservados.</p>
</footer>

<script src="js/aplicacion.js"></script>
