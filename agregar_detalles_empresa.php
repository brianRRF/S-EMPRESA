<?php
// Inicia la sesión para poder usar variables de sesión del usuario
session_start();

// Incluye el archivo de conexión a la base de datos (debe definir $conexion)
include 'conexion_be.php'; // Conexión a la base de datos

// Verifica si el usuario está autenticado.
// Si no hay sesión de usuario, redirige a la página de inicio y termina el script.
if (!isset($_SESSION['usuario'])) {
    header("location: inicio.php");
    exit();
}

// Obtiene el ID de la empresa desde la URL con GET y lo convierte a entero para evitar inyecciones y errores.
// Si no existe 'id_empresa' en GET, $id_empresa será null.
$id_empresa = isset($_GET['id_empresa']) ? intval($_GET['id_empresa']) : null;

// Si no se proporcionó el ID de la empresa, muestra una alerta y regresa a la página anterior.
if (!$id_empresa) {
    echo '<script>
        alert("No se proporcionó el ID de la empresa.");
        window.history.back();
    </script>';
    exit();
}

// Consulta la base de datos para obtener el estado de la empresa con el ID dado
$query = "SELECT estado FROM empresa WHERE id_empresa = $id_empresa";
$resultado = mysqli_query($conexion, $query);

// Intenta obtener la fila de resultado (si la empresa existe)
$empresa = mysqli_fetch_assoc($resultado);

// Si no se encontró la empresa, muestra una alerta y regresa
if (!$empresa) {
    echo '<script>
        alert("La empresa no existe.");
        window.history.back();
    </script>';
    exit();
} 
// Si la empresa existe pero su estado NO es 'aceptada', muestra una alerta y regresa
elseif ($empresa['estado'] !== 'aceptada') {
    echo '<script>
        alert("La empresa debe estar aceptada para agregar detalles.");
        window.history.back();
    </script>';
    exit();
}

// Si el código llega aquí, la empresa existe y está aceptada. El script puede continuar normalmente.
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Detalles de la Empresa</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Agregar Detalles de la Empresa</h1>
        <form action="procesar_detalles_empresa.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_empresa" value="<?php echo $id_empresa; ?>">
            <div class="form-group">
                <label for="foto_empresa">Foto de la Empresa:</label>
                <input type="file" name="foto_empresa" id="foto_empresa" class="form-control" accept="image/*">
            </div>
            <div class="form-group">
                <label for="servicios">Servicios que ofrece:</label>
                <textarea name="servicios" id="servicios" rows="5" class="form-control" placeholder="Describe los servicios" required></textarea>
            </div>
            <div class="form-group">
                <label for="correo_empresa">Correo Electrónico de la Empresa:</label>
                <input type="email" name="correo_empresa" id="correo_empresa" class="form-control" placeholder="ejemplo@gmail.com">
            </div>
            <div class="form-group">
                <label for="youtube_link">YouTube:</label>
                <input type="url" name="youtube_link" id="youtube_link" class="form-control" placeholder="https://youtube.com/">
            </div>
            <div class="form-group">
                <label for="pinterest_link">Pinterest:</label>
                <input type="url" name="pinterest_link" id="pinterest_link" class="form-control" placeholder="https://pinterest.com/">
            </div>
            <div class="form-group">
                <label for="github_link">GitHub:</label>
                <input type="url" name="github_link" id="github_link" class="form-control" placeholder="https://github.com/">
            </div>
            <div class="form-group">
                <label for="facebook_link">Facebook:</label>
                <input type="url" name="facebook_link" id="facebook_link" class="form-control" placeholder="https://facebook.com/">
            </div>
            <button type="submit" class="btn btn-primary">Guardar Detalles</button>
        </form>
    </div>
</body>
</html>