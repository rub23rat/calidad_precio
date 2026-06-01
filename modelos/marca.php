<?php
// Modelo de marcas.

class Marca {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listar() {
        $sql = "SELECT id_marca, nombre_marca FROM marcas ORDER BY nombre_marca";
        $consulta = $this->pdo->query($sql);
        return $consulta->fetchAll();
    }

    // Se usa en el filtro lateral del catálogo: solo devuelve marcas
    // que tengan al menos un producto en esa categoría (para no mostrar
    // marcas vacías como opción).
    public function listarPorCategoria($categoria) {
        $sql = "SELECT DISTINCT m.id_marca, m.nombre_marca
                FROM marcas m
                JOIN productos p ON p.id_marca = m.id_marca
                JOIN categorias c ON p.id_categoria = c.id_categoria
                WHERE c.nombre_categoria = ?
                ORDER BY m.nombre_marca";
        $consulta = $this->pdo->prepare($sql);
        $consulta->execute([$categoria]);
        return $consulta->fetchAll();
    }

    public function obtenerNombre($id_marca) {
        $sql = "SELECT nombre_marca FROM marcas WHERE id_marca = ?";
        $consulta = $this->pdo->prepare($sql);
        $consulta->execute([$id_marca]);
        $fila = $consulta->fetch();
        return $fila ? $fila['nombre_marca'] : '';
    }
}
