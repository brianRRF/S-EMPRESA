<?php
session_start();
include 'conexion_be.php'; // Conexión a la base de datos

// Verifica que el usuario esté logueado como administrador
if (!isset($_SESSION['usuario']) || $_SESSION['id_cargo'] != 2) {
    echo "Acceso denegado.";
    exit();
}

// Obtiene el ID de la empresa desde la URL
$id_empresa = $_GET['id'];

// Consulta los datos básicos de la empresa
$query = "SELECT e.nombre_legal, u.correo_elec, u.nombre_c, u.id AS id_usuario 
          FROM empresa e
          JOIN usuario u ON e.id = u.id
          WHERE e.id_empresa = $id_empresa";
$resultado = mysqli_query($conexion, $query);

if ($empresa = mysqli_fetch_assoc($resultado)) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Rechazar Empresa</title>
        <link rel="icon" href="4-icono.ico">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container mt-5">
            <h1 class="mb-4">Motivo de Rechazo para la Empresa</h1>
            <form action="enviar_rechazo.php" method="POST">
                <input type="hidden" name="id_empresa" value="<?php echo $id_empresa; ?>">
                <input type="hidden" name="id_usuario" value="<?php echo $empresa['id_usuario']; ?>">
                <input type="hidden" name="correo_elec" value="<?php echo $empresa['correo_elec']; ?>">
                <input type="hidden" name="nombre_c" value="<?php echo $empresa['nombre_c']; ?>">
                <div class="form-group">
                    <label for="motivo">Motivo del Rechazo:</label>
                    <textarea class="form-control" name="motivo" id="motivo" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-danger">Rechazar Empresa</button>
                <a href="gestionar_empresas.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </body>
    </html>
    <?php
} else {
    echo '<script>
        alert("Empresa no encontrada.");
        window.location = "gestionar_empresas.php";
    </script>';
}
?>