<?php
// Controlador de favoritos: listar y añadir/quitar.

class FavoritoControlador {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listar() {
        pideLogin();

        $modelo = new Favorito($this->pdo);
        $productos = $modelo->listarDeUsuario($_SESSION['id_usuario']);

        require __DIR__ . '/../vistas/productos/favoritos.php';
    }

    // Añade o quita el producto de favoritos (lo decide el modelo según
    // si ya estaba o no). Se llama desde el botón corazón del detalle.
    public function alternar() {
        pideLogin();

        $id = isset($_POST['id_producto']) ? (int) $_POST['id_producto'] : 0;

        if ($id > 0) {
            $modelo = new Favorito($this->pdo);
            $anadido = $modelo->alternar($_SESSION['id_usuario'], $id);

            if ($anadido) {
                guardarMensaje('correcto', 'Producto guardado en favoritos.');
            } else {
                guardarMensaje('correcto', 'Producto eliminado de favoritos.');
            }
        }

        header('Location: index.php?c=inicio&a=detalle&id=' . $id);
        exit;
    }
}
