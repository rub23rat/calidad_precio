<?php
// Controlador de gestión de usuarios (solo accesible para el admin).

class UsuarioControlador {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listar() {
        pideAdmin();

        $modelo = new Usuario($this->pdo);
        $miId = $_SESSION['id_usuario'];

        // Cambiar el rol de un usuario (acción del formulario).
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cambiar_rol'])) {
            $id_usuario = isset($_POST['id_usuario']) ? (int) $_POST['id_usuario'] : 0;
            $id_rol = isset($_POST['id_rol']) ? (int) $_POST['id_rol'] : 0;

            // El admin no puede modificarse a sí mismo (para no quitarse
            // los permisos por error y quedarse fuera del panel).
            if ($id_usuario > 0 && $id_usuario != $miId && $id_rol > 0) {
                $modelo->cambiarRol($id_usuario, $id_rol);
                guardarMensaje('correcto', 'Rol actualizado correctamente.');
            }
            header('Location: index.php?c=usuario&a=listar');
            exit;
        }

        // Borrar un usuario (llega por GET con ?borrar=ID).
        $borrar = isset($_GET['borrar']) ? (int) $_GET['borrar'] : 0;
        if ($borrar > 0 && $borrar != $miId) {
            $modelo->borrar($borrar);
            guardarMensaje('correcto', 'Usuario eliminado correctamente.');
            header('Location: index.php?c=usuario&a=listar');
            exit;
        }

        $usuarios = $modelo->listar();
        $roles = $modelo->listarRoles();

        require __DIR__ . '/../vistas/panel/usuarios.php';
    }
}
