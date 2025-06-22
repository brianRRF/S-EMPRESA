<?php
include 'conexion_be.php';

$query = "SELECT * FROM registro_empresa";
$result = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Empresas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Listado de Empresas Registradas</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['usuario_id']; ?></td>
                        <td>
                            <a href="ver_empresa.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">Ver PDFs</a>
                            <a href="aceptar_empresa.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Aceptar</a>
                            <a href="rechazar_empresa.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Rechazar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>