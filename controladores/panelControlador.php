<?php
// Controlador del panel: alta, baja, modificación e informe PDF de productos.

class PanelControlador {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listar() {
        pideEmpleadoOAdmin();

        $modelo = new Producto($this->pdo);
        $productos = $modelo->listar();

        require __DIR__ . '/../vistas/panel/productos.php';
    }

    public function crear() {
        pideEmpleadoOAdmin();

        $error = '';
        $nombre = '';
        $descripcion = '';
        $url_compra = '';
        $precio = '';
        $id_categoria = '';
        $id_marca = '';
        $etiqueta = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
            $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
            $url_compra = isset($_POST['url_compra']) ? trim($_POST['url_compra']) : '';
            $precio = isset($_POST['precio']) ? trim($_POST['precio']) : '';
            $id_categoria = isset($_POST['id_categoria']) ? $_POST['id_categoria'] : '';
            $id_marca = isset($_POST['id_marca']) ? $_POST['id_marca'] : '';
            $etiqueta = isset($_POST['etiqueta']) ? trim($_POST['etiqueta']) : '';
            // Si no es una de las etiquetas válidas la dejamos vacía.
            if (!in_array($etiqueta, etiquetasDisponibles())) {
                $etiqueta = '';
            }

            if ($nombre == '' || $url_compra == '' || $precio == '' || $id_categoria == '' || $id_marca == '') {
                $error = 'Rellena todos los campos obligatorios.';
            } elseif (!is_numeric($precio) || $precio < 0) {
                $error = 'El precio no es válido.';
            } else {
                $modeloMarca = new Marca($this->pdo);
                $nombre_marca = $modeloMarca->obtenerNombre($id_marca);
                $archivo = isset($_FILES['imagen']) ? $_FILES['imagen'] : [];
                $error = guardarImagen($archivo, $nombre_marca, $nombre);

                // Si la imagen falla no insertamos el producto porque queremos
                // que ambas cosas se hagan juntas.
                if ($error == '') {
                    $modelo = new Producto($this->pdo);
                    $modelo->insertar([
                        'nombre' => $nombre,
                        'descripcion' => $descripcion,
                        'url_compra' => $url_compra,
                        'precio' => $precio,
                        'id_categoria' => $id_categoria,
                        'id_marca' => $id_marca,
                        'etiqueta' => $etiqueta,
                    ]);
                    guardarMensaje('correcto', "Producto \"$nombre\" añadido correctamente.");
                    header('Location: index.php?c=panel&a=listar');
                    exit;
                }
            }
        }

        $modeloCategoria = new Categoria($this->pdo);
        $categorias = $modeloCategoria->listar();
        $modeloMarca = new Marca($this->pdo);
        $marcas = $modeloMarca->listar();

        require __DIR__ . '/../vistas/panel/crear.php';
    }

    public function editar() {
        pideEmpleadoOAdmin();

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $modeloProducto = new Producto($this->pdo);
        $producto = $modeloProducto->obtenerPorId($id);

        // Si el id no existe, volvemos al listado.
        if (!$producto) {
            header('Location: index.php?c=panel&a=listar');
            exit;
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
            $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
            $url_compra = isset($_POST['url_compra']) ? trim($_POST['url_compra']) : '';
            $precio = isset($_POST['precio']) ? trim($_POST['precio']) : '';
            $id_categoria = isset($_POST['id_categoria']) ? $_POST['id_categoria'] : '';
            $id_marca = isset($_POST['id_marca']) ? $_POST['id_marca'] : '';
            $etiqueta = isset($_POST['etiqueta']) ? trim($_POST['etiqueta']) : '';
            // Si no es una de las etiquetas válidas la dejamos vacía.
            if (!in_array($etiqueta, etiquetasDisponibles())) {
                $etiqueta = '';
            }

            if ($nombre == '' || $url_compra == '' || $precio == '' || $id_categoria == '' || $id_marca == '') {
                $error = 'Rellena todos los campos obligatorios.';
            } elseif (!is_numeric($precio) || $precio < 0) {
                $error = 'El precio no es válido.';
            } else {
                $modeloMarca = new Marca($this->pdo);
                $nombre_marca = $modeloMarca->obtenerNombre($id_marca);

                // Si se cambió el nombre, renombramos también el archivo
                // de imagen para que siga apareciendo en el listado.
                renombrarImagen($producto['nombre_marca'], $producto['nombre_producto'], $nombre);

                $archivo = isset($_FILES['imagen']) ? $_FILES['imagen'] : [];
                $error = guardarImagen($archivo, $nombre_marca, $nombre);

                if ($error == '') {
                    $modeloProducto->actualizar($id, [
                        'nombre' => $nombre,
                        'descripcion' => $descripcion,
                        'url_compra' => $url_compra,
                        'precio' => $precio,
                        'id_categoria' => $id_categoria,
                        'id_marca' => $id_marca,
                        'etiqueta' => $etiqueta,
                    ]);
                    guardarMensaje('correcto', "Producto \"$nombre\" editado correctamente.");
                    header('Location: index.php?c=panel&a=listar');
                    exit;
                }
            }

            // Si hubo error, conservamos lo que escribió el usuario para
            // que no tenga que rellenar todo el formulario otra vez.
            $producto['nombre_producto'] = $nombre;
            $producto['descripcion'] = $descripcion;
            $producto['url_compra'] = $url_compra;
            $producto['precio'] = $precio;
            $producto['id_categoria'] = $id_categoria;
            $producto['id_marca'] = $id_marca;
            $producto['etiqueta'] = $etiqueta;
        }

        $modeloCategoria = new Categoria($this->pdo);
        $categorias = $modeloCategoria->listar();
        $modeloMarca = new Marca($this->pdo);
        $marcas = $modeloMarca->listar();
        $imagen_actual = imagenProducto($producto);

        require __DIR__ . '/../vistas/panel/editar.php';
    }

    // Genera un PDF con el listado de productos usando la librería FPDF.
    public function informeProductos() {
        pideEmpleadoOAdmin();

        $modelo = new Producto($this->pdo);
        $productos = $modelo->listar();

        require_once __DIR__ . '/../lib/fpdf.php';

        $pdf = new FPDF();
        $pdf->AddPage();

        // FPDF trabaja en Latin-1 por defecto, así que pasamos los textos
        // por utf8_decode (si no, los acentos se ven con caracteres raros).
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, utf8_decode('Informe de productos - CALIDAD-PRECIO'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 8, 'Total de productos: ' . count($productos), 0, 1, 'C');
        $pdf->Ln(8);

        // Cabecera de la tabla con el teal de la web (#088395).
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetFillColor(8, 131, 149);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(75, 8, 'Nombre', 1, 0, 'C', true);
        $pdf->Cell(45, 8, utf8_decode('Categoría'), 1, 0, 'C', true);
        $pdf->Cell(40, 8, 'Marca', 1, 0, 'C', true);
        $pdf->Cell(30, 8, 'Precio', 1, 1, 'C', true);

        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        foreach ($productos as $producto) {
            $pdf->Cell(75, 7, utf8_decode($producto['nombre_producto']), 1);
            $pdf->Cell(45, 7, utf8_decode($producto['nombre_categoria']), 1);
            $pdf->Cell(40, 7, utf8_decode($producto['nombre_marca']), 1);
            $pdf->Cell(30, 7, number_format($producto['precio'], 2, ',', '.') . ' EUR', 1, 1, 'R');
        }

        // 'D' fuerza descarga del archivo (en vez de abrirlo en el navegador).
        $pdf->Output('D', 'informe_productos.pdf');
        exit;
    }

    public function borrar() {
        pideEmpleadoOAdmin();

        // El id puede llegar por POST (botón AJAX del listado) o por GET
        // (enlace de respaldo si no funcionase el JS).
        if (isset($_POST['id'])) {
            $id = (int) $_POST['id'];
        } elseif (isset($_GET['id'])) {
            $id = (int) $_GET['id'];
        } else {
            $id = 0;
        }
        $nombre = '';

        if ($id > 0) {
            $modelo = new Producto($this->pdo);
            $producto = $modelo->obtenerPorId($id);
            $nombre = isset($producto['nombre_producto']) ? $producto['nombre_producto'] : '';
            $modelo->borrar($id);
        }

        // Si la petición viene de AJAX devolvemos JSON; si no, redirigimos
        // al panel con un mensaje flash.
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            header('Content-Type: application/json');
            echo json_encode(['ok' => $id > 0, 'nombre' => $nombre]);
            exit;
        }

        guardarMensaje('correcto', "Producto \"$nombre\" eliminado correctamente.");
        header('Location: index.php?c=panel&a=listar');
        exit;
    }
}
