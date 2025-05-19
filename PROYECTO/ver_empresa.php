<?php
session_start();
include 'conexion_be.php'; // Conexión a la base de datos

// Verifica que el usuario esté logueado y tenga permisos de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['id_cargo'] != 2) {
    echo "Acceso denegado.";
    exit();
}

// Obtiene el ID de la empresa desde la URL
$id_empresa = $_GET['id'];

// Consulta los datos de la empresa
$query = "SELECT * FROM empresa WHERE id_empresa = $id_empresa";
$resultado = mysqli_query($conexion, $query);

// Muestra los datos de la empresa si existe
if ($empresa = mysqli_fetch_assoc($resultado)) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Detalles de Empresa</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container mt-5">
            <h1 class="mb-4">Detalles de la Empresa</h1>
            <p><strong>Nombre Legal:</strong> <?php echo $empresa['nombre_legal']; ?></p>
            <p><strong>Nombre Comercial:</strong> <?php echo $empresa['nombre_comercial']; ?></p>
            <p><strong>Identificación Fiscal:</strong> <?php echo $empresa['identificacion_fiscal']; ?></p>
            <p><strong>Teléfono:</strong> <?php echo $empresa['telefono_empresa']; ?></p>
            <p><strong>Dirección:</strong> <?php echo $empresa['direccion_empresa']; ?></p>
            <p><strong>Representante Legal:</strong> <?php echo $empresa['nombre_representante']; ?></p>
            <p><strong>Descripción del Negocio:</strong> <?php echo $empresa['descripcion_negocio']; ?></p>
            <p><strong>Página Web:</strong> <a href="<?php echo $empresa['pagina_web']; ?>" target="_blank"><?php echo $empresa['pagina_web']; ?></a></p>

            <h3>Documentos</h3>
            <ul>
                <li><a href="<?php echo $empresa['certificado_constitucion']; ?>" target="_blank">Certificado de Constitución</a></li>
                <li><a href="<?php echo $empresa['registro_mercantil']; ?>" target="_blank">Registro Mercantil</a></li>
                <li><a href="<?php echo $empresa['licencias_permisos']; ?>" target="_blank">Licencias o Permisos</a></li>
                <li><a href="<?php echo $empresa['documento_identidad_representante']; ?>" target="_blank">Documento de Identidad del Representante</a></li>
            </ul>

            <a href="gestionar_empresas.php" class="btn btn-secondary">Volver</a>
        </div>
    </body>
    </html>
    <?php
} else {
    echo "<p>No se encontró la empresa.</p>";
    echo '<a href="gestionar_empresas.php">Volver</a>';
}
?>