<?php
// Modelo de productos.

class Producto {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Lista los productos con filtros opcionales (búsqueda, categoría,
    // marcas y rangos de precio). Si $limite > 0 devuelve solo los N
    // más recientes (se usa en el inicio para mostrar solo 8 tarjetas).
    public function listar($buscar = '', $categoria = '', $marcas = [], $rangos = [], $limite = 0) {
        $sql = "SELECT p.id_producto, p.nombre_producto, p.descripcion,
                       p.url_compra, p.precio, p.etiqueta,
                       c.nombre_categoria, m.nombre_marca
                FROM productos p
                LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
                LEFT JOIN marcas m ON p.id_marca = m.id_marca";

        $condiciones = [];
        $valores = [];

        if ($buscar != '') {
            $condiciones[] = "p.nombre_producto LIKE ?";
            $valores[] = "%$buscar%";
        }
        if ($categoria != '') {
            $condiciones[] = "c.nombre_categoria = ?";
            $valores[] = $categoria;
        }

        // PDO no admite pasar un array directamente como parámetro de un
        // IN(...), así que generamos "?,?,?" a mano según cuántas marcas
        // hayan marcado y luego añadimos los valores uno por uno.
        if (!empty($marcas)) {
            $huecos = '';
            foreach ($marcas as $id) {
                if ($huecos != '') {
                    $huecos .= ',';
                }
                $huecos .= '?';
            }
            $condiciones[] = "p.id_marca IN ($huecos)";
            foreach ($marcas as $id) {
                $valores[] = (int) $id;
            }
        }

        // Cada rango se mapea a [min, max). Si el usuario marca varios
        // los unimos con OR (quiere ver productos en cualquiera de ellos).
        $rangos_precio = [
            'menos100' => [0, 100],
            '100_300' => [100, 300],
            '300_600' => [300, 600],
            'mas600' => [600, 999999],
        ];
        if (!empty($rangos)) {
            $or_rangos = [];
            foreach ($rangos as $rango) {
                if (isset($rangos_precio[$rango])) {
                    $or_rangos[] = "(p.precio >= ? AND p.precio < ?)";
                    $valores[] = $rangos_precio[$rango][0];
                    $valores[] = $rangos_precio[$rango][1];
                }
            }
            if (!empty($or_rangos)) {
                $condiciones[] = "(" . implode(' OR ', $or_rangos) . ")";
            }
        }

        if (!empty($condiciones)) {
            $sql .= " WHERE " . implode(' AND ', $condiciones);
        }
        $sql .= " ORDER BY p.id_producto DESC";

        // LIMIT no se puede pasar como placeholder en MySQL, así que lo
        // concatenamos directamente. El (int) evita inyección.
        if ($limite > 0) {
            $sql .= " LIMIT " . (int) $limite;
        }

        $consulta = $this->pdo->prepare($sql);
        $consulta->execute($valores);
        return $consulta->fetchAll();
    }

    public function obtenerPorId($id) {
        $sql = "SELECT p.*, c.nombre_categoria, m.nombre_marca
                FROM productos p
                LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
                LEFT JOIN marcas m ON p.id_marca = m.id_marca
                WHERE p.id_producto = ?";
        $consulta = $this->pdo->prepare($sql);
        $consulta->execute([$id]);
        return $consulta->fetch();
    }

    // $datos debe traer: nombre, descripcion, url_compra, precio, id_categoria, id_marca, etiqueta
    public function insertar($datos) {
        $sql = "INSERT INTO productos
                (nombre_producto, descripcion, url_compra, precio, id_categoria, id_marca, etiqueta)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $consulta = $this->pdo->prepare($sql);
        $consulta->execute([
            $datos['nombre'],
            $datos['descripcion'],
            $datos['url_compra'],
            $datos['precio'],
            $datos['id_categoria'],
            $datos['id_marca'],
            $datos['etiqueta'],
        ]);
    }

    public function actualizar($id, $datos) {
        $sql = "UPDATE productos SET
                    nombre_producto = ?,
                    descripcion = ?,
                    url_compra = ?,
                    precio = ?,
                    id_categoria = ?,
                    id_marca = ?,
                    etiqueta = ?
                WHERE id_producto = ?";
        $consulta = $this->pdo->prepare($sql);
        $consulta->execute([
            $datos['nombre'],
            $datos['descripcion'],
            $datos['url_compra'],
            $datos['precio'],
            $datos['id_categoria'],
            $datos['id_marca'],
            $datos['etiqueta'],
            $id,
        ]);
    }

    public function borrar($id) {
        $sql = "DELETE FROM productos WHERE id_producto = ?";
        $consulta = $this->pdo->prepare($sql);
        $consulta->execute([$id]);
    }
}
