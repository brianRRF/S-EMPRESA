<?php
session_start();
include 'conexion_be.php'; // Conexión a la base de datos

if (!isset($_SESSION['usuario'])) {
    header("location: inicio.php");
    exit();
}

// Obtener el ID de la empresa desde la URL
$id_empresa = isset($_GET['id_empresa']) ? intval($_GET['id_empresa']) : null;
if (!$id_empresa) {
    echo '<script>
        alert("No se proporcionó el ID de la empresa.");
        window.history.back();
    </script>';
    exit();
}

// Verifica si la empresa existe y está aceptada
$query = "SELECT estado FROM empresa WHERE id_empresa = $id_empresa";
$resultado = mysqli_query($conexion, $query);
$empresa = mysqli_fetch_assoc($resultado);

if (!$empresa) {
    echo '<script>
        alert("La empresa no existe.");
        window.history.back();
    </script>';
    exit();
} elseif ($empresa['estado'] !== 'aceptada') {
    echo '<script>
        alert("La empresa debe estar aceptada para agregar detalles.");
        window.history.back();
    </script>';
    exit();
}
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