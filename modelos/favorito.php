<?php
// Modelo de favoritos: lo que el usuario guarda con el botón corazón.

class Favorito {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listarDeUsuario($id_usuario) {
        $sql = "SELECT p.id_producto, p.nombre_producto, p.precio, p.etiqueta,
                       c.nombre_categoria, m.nombre_marca
                FROM favoritos f
                JOIN productos p ON f.id_producto = p.id_producto
                LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
                LEFT JOIN marcas m ON p.id_marca = m.id_marca
                WHERE f.id_usuario = ?
                ORDER BY f.fecha_guardado DESC";
        $consulta = $this->pdo->prepare($sql);
        $consulta->execute([$id_usuario]);
        return $consulta->fetchAll();
    }

    public function existe($id_usuario, $id_producto) {
        $sql = "SELECT 1 FROM favoritos WHERE id_usuario = ? AND id_producto = ?";
        $consulta = $this->pdo->prepare($sql);
        $consulta->execute([$id_usuario, $id_producto]);
        return (bool) $consulta->fetch();
    }

    // Si el producto ya estaba en favoritos lo quita, si no lo añade.
    // Devuelve true cuando lo acaba de añadir (lo usa el controlador
    // para mostrar uno u otro mensaje de aviso).
    public function alternar($id_usuario, $id_producto) {
        if ($this->existe($id_usuario, $id_producto)) {
            $sql = "DELETE FROM favoritos WHERE id_usuario = ? AND id_producto = ?";
            $consulta = $this->pdo->prepare($sql);
            $consulta->execute([$id_usuario, $id_producto]);
            return false;
        }

        $sql = "INSERT INTO favoritos (id_usuario, id_producto) VALUES (?, ?)";
        $consulta = $this->pdo->prepare($sql);
        $consulta->execute([$id_usuario, $id_producto]);
        return true;
    }
}
