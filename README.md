# CALIDAD-PRECIO

Web para recomendar tecnología con relación calidad y precio.

## Acceso Online

[calidad-precio.com](https://calidad-precio.com/)

## Instalación de programas necesarios

### 1. XAMPP

Es necesario instalar XAMPP para la instalación de servidores Apache y MySQL. Para ello nos dirigimos a la página oficial de descarga, la cual adjunto en el siguiente enlace: [https://www.apachefriends.org/es/index.html](https://www.apachefriends.org/es/index.html)

Una vez descargado, seguimos los pasos para el proceso de instalación.

### 2. Visual Studio Code

Se va a hacer uso de VSCode como herramienta de edición de código. Adjunto el enlace hacia su página oficial para su descarga: [https://code.visualstudio.com/](https://code.visualstudio.com/) y a continuación seguimos los pasos de instalación.

#### 2.1 Extensión PHP Intelephense

Al tener VSCode completamente instalado, dentro del apartado de extensiones de VSCode buscamos **PHP Intelephense** y procedemos a instalar dicha extensión para poder editar código PHP dentro del programa.

## Configuración para la ejecución en local

Para ejecutar la web en local primero tenemos que tener la carpeta con el proyecto, la cual se puede copiar de los archivos adjuntos a la tarea o bien instalarlos desde mi repositorio de GitHub.

A continuación movemos la carpeta del proyecto llamada `calidad_precio` en la ruta que se genera por defecto al instalar XAMPP, que es: `C:\xampp\htdocs`

Antes de acceder a la web tendremos que insertar los datos en la base de datos de MySQL y para ello primero abrimos la base de datos desde el panel de XAMPP y pulsamos en el botón de **Admin** para acceder a la herramienta de phpMyAdmin. A continuación copiamos de los archivos del proyecto el código SQL que está dentro del archivo llamado `calidad_precio.sql` para crear la base de datos con su contenido.

He de recalcar que si ya se instaló XAMPP anteriormente y en la base de datos se estableció un usuario y contraseña en específico, habría que acceder al archivo `conexion.php` que se sitúa dentro del proyecto y modificar los parámetros de usuario y contraseña con las credenciales de su base de datos.

Ya por último podríamos acceder a la web en local con el siguiente enlace: [localhost/calidad_precio/public](http://localhost/calidad_precio/public)
