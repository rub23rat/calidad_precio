<?php
// Modelo de categorías.

class Categoria {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listar() {
        $sql = "SELECT id_categoria, nombre_categoria FROM categorias ORDER BY nombre_categoria";
        $consulta = $this->pdo->query($sql);
        return $consulta->fetchAll();
    }
}
