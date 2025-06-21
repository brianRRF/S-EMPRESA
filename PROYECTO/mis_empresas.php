<?php
session_start();
include 'conexion_be.php'; // Conexión a la base de datos

if (!isset($_SESSION['usuario'])) {
    header("location: inicio.php");
    exit();
}

$id_usuario = $_SESSION['id'];
$query = "SELECT * FROM empresa WHERE id = $id_usuario AND estado = 'aceptada'";
$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Empresas</title>
    <link rel="icon" href="4-icono.ico">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Mis Empresas Aceptadas</h1>
        <?php if (mysqli_num_rows($resultado) > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nombre Legal</th>
                        <th>Nombre Comercial</th>
                        <th>Estado de Auditoría</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
                        <tr>
                            <td><?php echo $row['nombre_legal']; ?></td>
                            <td><?php echo $row['nombre_comercial']; ?></td>
                            <td><?php echo $row['auditada'] ? 'Auditada' : 'No Auditada'; ?></td>
                            <td>
                                <a href="agregar_detalles_empresa.php?id_empresa=<?php echo $row['id_empresa']; ?>" class="btn btn-primary">Agregar Detalles</a>
                                <?php if ($row['auditada']): ?>
                                    <a href="publicar_empresa.php?id=<?php echo $row['id_empresa']; ?>" class="btn btn-success">Publicar</a>
                                <?php else: ?>
                                    <button class="btn btn-secondary" disabled>No disponible</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No tienes empresas aceptadas por el momento.</p>
        <?php endif; ?>
    </div>
</body>
</html>