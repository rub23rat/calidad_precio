<?php
// Punto de entrada de la aplicación. Decide qué controlador y qué acción ejecutar.

session_start();

define('DIR_PUBLICO', __DIR__);

require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../funciones.php';

// Modelos
require_once __DIR__ . '/../modelos/producto.php';
require_once __DIR__ . '/../modelos/usuario.php';
require_once __DIR__ . '/../modelos/categoria.php';
require_once __DIR__ . '/../modelos/marca.php';
require_once __DIR__ . '/../modelos/favorito.php';

// Controladores
require_once __DIR__ . '/../controladores/inicioControlador.php';
require_once __DIR__ . '/../controladores/accesoControlador.php';
require_once __DIR__ . '/../controladores/favoritoControlador.php';
require_once __DIR__ . '/../controladores/panelControlador.php';
require_once __DIR__ . '/../controladores/usuarioControlador.php';

// Controlador (c) y acción (a) que llegan por la URL
$controlador = isset($_GET['c']) ? $_GET['c'] : 'inicio';
$accion = isset($_GET['a']) ? $_GET['a'] : 'inicio';

// Acciones permitidas por cada controlador (lista blanca de seguridad)
$rutas = [
    'inicio' => ['inicio', 'detalle', 'ayuda'],
    'acceso' => ['entrar', 'registro', 'salir'],
    'favorito' => ['listar', 'alternar'],
    'panel' => ['listar', 'crear', 'editar', 'borrar', 'informeProductos'],
    'usuario' => ['listar'],
];

// Si la ruta no es válida, vamos al inicio
if (!isset($rutas[$controlador]) || !in_array($accion, $rutas[$controlador])) {
    $controlador = 'inicio';
    $accion = 'inicio';
}

// Crear el controlador correspondiente y llamar a su acción
switch ($controlador) {
    case 'inicio':
        $objeto = new InicioControlador($pdo);
        if ($accion == 'inicio') {
            $objeto->inicio();
        } elseif ($accion == 'detalle') {
            $objeto->detalle();
        } elseif ($accion == 'ayuda') {
            $objeto->ayuda();
        }
        break;
    case 'acceso':
        $objeto = new AccesoControlador($pdo);
        if ($accion == 'entrar') {
            $objeto->entrar();
        } elseif ($accion == 'registro') {
            $objeto->registro();
        } elseif ($accion == 'salir') {
            $objeto->salir();
        }
        break;
    case 'favorito':
        $objeto = new FavoritoControlador($pdo);
        if ($accion == 'listar') {
            $objeto->listar();
        } elseif ($accion == 'alternar') {
            $objeto->alternar();
        }
        break;
    case 'panel':
        $objeto = new PanelControlador($pdo);
        if ($accion == 'listar') {
            $objeto->listar();
        } elseif ($accion == 'crear') {
            $objeto->crear();
        } elseif ($accion == 'editar') {
            $objeto->editar();
        } elseif ($accion == 'borrar') {
            $objeto->borrar();
        } elseif ($accion == 'informeProductos') {
            $objeto->informeProductos();
        }
        break;
    case 'usuario':
        $objeto = new UsuarioControlador($pdo);
        if ($accion == 'listar') {
            $objeto->listar();
        }
        break;
}
