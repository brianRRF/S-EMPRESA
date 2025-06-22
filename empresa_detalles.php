<?php
// Inicia la sesión para poder utilizar variables de sesión
session_start();

// Incluye el archivo de conexión a la base de datos (debe definir $conexion)
include 'conexion_be.php';

// Verifica que el usuario esté logueado
if (!isset($_SESSION['usuario'])) {
    // Si no está logueado, muestra una alerta y redirige al login
    echo '<script>
        alert("Debe iniciar sesión para acceder a esta página.");
        window.location = "login.php";
    </script>';
    exit();
}

// Obtiene el ID de la empresa desde la URL (GET), o null si no viene especificado
$id_empresa = $_GET['id_empresa'] ?? null;
// Si no se especifica, muestra alerta y vuelve a la página anterior
if (!$id_empresa) {
    echo '<script>
        alert("Empresa no especificada.");
        window.history.back();
    </script>';
    exit();
}

// Consulta la tabla empresa_detalles para obtener los detalles de la empresa
$query = "SELECT * FROM empresa_detalles WHERE id_empresa = $id_empresa";
$resultado = mysqli_query($conexion, $query);
// Toma el primer resultado como array asociativo (o false si no hay resultado)
$detalles = mysqli_fetch_assoc($resultado);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Empresa</title>
    <link rel="icon" href="4-icono.ico">
</head>
<body>
    <h1>Detalles de la Empresa</h1>
    <?php if ($detalles): ?>
        <!-- Si existen detalles, los muestra -->
        <p><strong>Foto:</strong></p>
        <img src="<?php echo $detalles['foto_empresa']; ?>" alt="Foto de la Empresa" style="max-width: 200px;"><br><br>

        <p><strong>Servicios:</strong> <?php echo $detalles['servicios']; ?></p>
        <p><strong>Correo Electrónico:</strong> <?php echo $detalles['correo_empresa']; ?></p>

        <p><strong>Redes Sociales:</strong></p>
        <ul>
            <li><a href="<?php echo $detalles['youtube_link']; ?>">YouTube</a></li>
            <li><a href="<?php echo $detalles['pinterest_link']; ?>">Pinterest</a></li>
            <li><a href="<?php echo $detalles['github_link']; ?>">GitHub</a></li>
            <li><a href="<?php echo $detalles['facebook_link']; ?>">Facebook</a></li>
        </ul>
    <?php else: ?>
        <!-- Si no hay detalles, muestra mensaje alternativo -->
        <p>No se han agregado detalles para esta empresa.</p>
    <?php endif; ?>
</body>
</html>