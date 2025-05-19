<?php
session_start();
include 'conexion_be.php'; // Conexión a la base de datos

// Verifica que el usuario esté logueado y tenga permisos de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['id_cargo'] != 2) {
    echo "Acceso denegado.";
    exit();
}

// Consulta las empresas registradas, excluyendo las rechazadas
$query = "SELECT e.*, u.nombre_c AS usuario_registro 
          FROM empresa e
          JOIN usuario u ON e.id = u.id
          WHERE e.estado NOT IN ('rechazada')"; // Excluye empresas con estado 'rechazada'
$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empresas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Gestión de Empresas</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Empresa</th>
                    <th>Representante</th>
                    <th>Usuario que Registró</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($resultado)): ?>
                    <tr>
                        <td><?php echo $row['nombre_legal']; ?></td>
                        <td><?php echo $row['nombre_representante']; ?></td>
                        <td><?php echo $row['usuario_registro']; ?></td>
                        <td><?php echo $row['estado'] ?? 'Pendiente'; ?></td>
                        <td>
                            <a href="ver_empresa.php?id=<?php echo $row['id_empresa']; ?>" class="btn btn-info">Ver Datos</a>
                            <a href="aceptar_empresa.php?id=<?php echo $row['id_empresa']; ?>" class="btn btn-success">Aceptar</a>
                            <a href="rechazar_empresa.php?id=<?php echo $row['id_empresa']; ?>" class="btn btn-danger">Rechazar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>