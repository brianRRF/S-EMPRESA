<?php
session_start();
include 'conexion_be.php';

// Verifica que el usuario esté logueado
if (!isset($_SESSION['usuario'])) {
    echo '<script>
        alert("Debe iniciar sesión para acceder a esta página.");
        window.location = "login.php";
    </script>';
    exit();
}

// Obtener el ID de la empresa
$id_empresa = $_GET['id_empresa'] ?? null;
if (!$id_empresa) {
    echo '<script>
        alert("Empresa no especificada.");
        window.history.back();
    </script>';
    exit();
}

// Consultar los detalles de la empresa
$query = "SELECT * FROM empresa_detalles WHERE id_empresa = $id_empresa";
$resultado = mysqli_query($conexion, $query);
$detalles = mysqli_fetch_assoc($resultado);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Empresa</title>
</head>
<body>
    <h1>Detalles de la Empresa</h1>
    <?php if ($detalles): ?>
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
        <p>No se han agregado detalles para esta empresa.</p>
    <?php endif; ?>
</body>
</html>