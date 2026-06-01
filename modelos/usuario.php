<?php
// Modelo de usuarios.

class Usuario {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listar() {
        $sql = "SELECT u.id_usuario, u.nombre, u.correo, u.id_rol, r.nombre_rol
                FROM usuarios u
                LEFT JOIN roles r ON u.id_rol = r.id_rol
                ORDER BY u.id_usuario";
        $consulta = $this->pdo->query($sql);
        return $consulta->fetchAll();
    }

    public function buscarPorCorreo($correo) {
        $sql = "SELECT id_usuario, nombre, passw, id_rol FROM usuarios WHERE correo = ?";
        $consulta = $this->pdo->prepare($sql);
        $consulta->execute([$correo]);
        return $consulta->fetch();
    }

    // Se usa al registrarse para no permitir cuentas duplicadas.
    public function correoExiste($correo) {
        $sql = "SELECT id_usuario FROM usuarios WHERE correo = ?";
        $consulta = $this->pdo->prepare($sql);
        $consulta->execute([$correo]);
        return (bool) $consulta->fetch();
    }

    // La contraseña se guarda hasheada con bcrypt.
    public function insertar($nombre, $correo, $passw) {
        $clave = password_hash($passw, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nombre, correo, passw, id_rol) VALUES (?, ?, ?, ?)";
        $consulta = $this->pdo->prepare($sql);
        $consulta->execute([$nombre, $correo, $clave, ROL_USUARIO]);
    }

    public function cambiarRol($id_usuario, $id_rol) {
        $sql = "UPDATE usuarios SET id_rol = ? WHERE id_usuario = ?";
        $consulta = $this->pdo->prepare($sql);
        $consulta->execute([$id_rol, $id_usuario]);
    }

    public function borrar($id_usuario) {
        $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
        $consulta = $this->pdo->prepare($sql);
        $consulta->execute([$id_usuario]);
    }

    public function listarRoles() {
        $sql = "SELECT id_rol, nombre_rol FROM roles ORDER BY id_rol";
        $consulta = $this->pdo->query($sql);
        return $consulta->fetchAll();
    }
}
