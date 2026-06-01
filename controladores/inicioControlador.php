<?php
// Controlador de la zona pública: inicio (catálogo), detalle y ayuda.

class InicioControlador {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function inicio() {
        $buscar = isset($_GET['q']) ? trim($_GET['q']) : '';
        $categoria = isset($_GET['cat']) ? trim($_GET['cat']) : '';

        $marcas_elegidas = isset($_GET['marcas']) ? $_GET['marcas'] : [];
        $rangos_elegidos = isset($_GET['rangos']) ? $_GET['rangos'] : [];
        if (!is_array($marcas_elegidas)) {
            $marcas_elegidas = [];
        }
        if (!is_array($rangos_elegidos)) {
            $rangos_elegidos = [];
        }

        // En el inicio limpio (sin búsqueda ni categoría) solo mostramos
        // 8 productos. En categorías y búsquedas no limitamos.
        $limite = 0;
        if ($buscar == '' && $categoria == '') {
            $limite = 8;
        }

        $modelo = new Producto($this->pdo);
        $productos = $modelo->listar($buscar, $categoria, $marcas_elegidas, $rangos_elegidos, $limite);

        // Las marcas del filtro lateral solo se calculan si hay categoría
        // y en el inicio no aparece el panel de filtros.
        $marcas_disponibles = [];
        if ($categoria != '') {
            $modeloMarca = new Marca($this->pdo);
            $marcas_disponibles = $modeloMarca->listarPorCategoria($categoria);
        }

        require __DIR__ . '/../vistas/productos/inicio.php';
    }

    public function detalle() {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        $modelo = new Producto($this->pdo);
        $producto = $modelo->obtenerPorId($id);

        // Si el id no existe, volvemos al inicio.
        if (!$producto) {
            header('Location: index.php');
            exit;
        }

        $esFavorito = false;
        if (estaLogueado()) {
            $modeloFavorito = new Favorito($this->pdo);
            $esFavorito = $modeloFavorito->existe($_SESSION['id_usuario'], $id);
        }

        $imagen = imagenProducto($producto);

        require __DIR__ . '/../vistas/productos/detalle.php';
    }

    public function ayuda() {
        require __DIR__ . '/../vistas/productos/ayuda.php';
    }
}
