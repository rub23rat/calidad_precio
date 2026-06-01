<?php
// Controlador de acceso: login, registro y logout.

class AccesoControlador {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function entrar() {
        // Si ya está logueado, no tiene sentido volver al login.
        if (estaLogueado()) {
            header('Location: index.php');
            exit;
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';
            $passw = isset($_POST['passw']) ? $_POST['passw'] : '';

            if ($correo == '' || $passw == '') {
                $error = 'Rellena todos los campos.';
            } else {
                $modelo = new Usuario($this->pdo);
                $usuario = $modelo->buscarPorCorreo($correo);

                // password_verify compara la contraseña con el hash
                // guardado en BD (no se puede comparar a pelo).
                if ($usuario && password_verify($passw, $usuario['passw'])) {
                    $_SESSION['id_usuario'] = $usuario['id_usuario'];
                    $_SESSION['nombre'] = $usuario['nombre'];
                    $_SESSION['id_rol'] = $usuario['id_rol'];
                    header('Location: index.php');
                    exit;
                } else {
                    $error = 'Correo o contraseña incorrectos.';
                }
            }
        }

        require __DIR__ . '/../vistas/acceso/entrar.php';
    }

    public function registro() {
        if (estaLogueado()) {
            header('Location: index.php');
            exit;
        }

        $error = '';
        $exito = false;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
            $correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';
            $passw = isset($_POST['passw']) ? $_POST['passw'] : '';
            $passw2 = isset($_POST['passw2']) ? $_POST['passw2'] : '';

            $modelo = new Usuario($this->pdo);

            // Validaciones encadenadas y se para en el primer fallo.
            if ($nombre == '' || $correo == '' || $passw == '' || $passw2 == '') {
                $error = 'Rellena todos los campos.';
            } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $error = 'El correo no es válido.';
            } elseif (errorPassw($passw) != '') {
                $error = errorPassw($passw);
            } elseif ($passw != $passw2) {
                $error = 'Las contraseñas no coinciden.';
            } elseif ($modelo->correoExiste($correo)) {
                $error = 'Ya existe una cuenta con ese correo.';
            } else {
                $modelo->insertar($nombre, $correo, $passw);
                $exito = true;
            }
        }

        require __DIR__ . '/../vistas/acceso/registro.php';
    }

    public function salir() {
        $_SESSION = [];
        session_destroy();
        header('Location: index.php');
        exit;
    }
}
