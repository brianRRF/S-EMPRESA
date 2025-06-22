<?php
// Incluye el archivo de conexión a la base de datos, que debe definir la variable $conexion
include 'conexion_be.php'; // Conexión a la base de datos

// Prepara una consulta SQL para eliminar los códigos de recuperación cuya fecha de caducidad ya pasó
$query = "DELETE FROM recuperacion WHERE fecha_caducidad < NOW()";

// Ejecuta la consulta en la base de datos
if (mysqli_query($conexion, $query)) {
    // Si la consulta fue exitosa, muestra un mensaje de éxito
    echo "Códigos expirados eliminados correctamente.";
} else {
    // Si hubo un error, muestra el mensaje de error detallado
    echo "Error al eliminar los códigos expirados: " . mysqli_error($conexion);
}

// Cierra la conexión a la base de datos para liberar recursos
mysqli_close($conexion);
?>