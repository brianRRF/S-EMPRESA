<?php
include 'conexion_be.php'; // Conexión a la base de datos

// Eliminar los códigos que ya hayan expirado
$query = "DELETE FROM recuperacion WHERE fecha_caducidad < NOW()";
if (mysqli_query($conexion, $query)) {
    echo "Códigos expirados eliminados correctamente.";
} else {
    echo "Error al eliminar los códigos expirados: " . mysqli_error($conexion);
}

// Cerrar la conexión
mysqli_close($conexion);
?>