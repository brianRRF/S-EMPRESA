<?php
session_start();
include 'conexion_be.php'; // Conexión a la base de datos

// Verifica que el usuario esté logueado y tenga permisos de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['id_cargo'] != 2) {
    echo "Acceso denegado.";
    exit();
}

// Obtiene el ID del administrador desde la sesión
$id_usuario = $_SESSION['id'];

// Consulta para obtener las notificaciones del administrador actual
$query = "SELECT titulo, mensaje, leida, fecha_creacion, id_empresa 
          FROM notificaciones_admin 
          WHERE id_usuario = $id_usuario 
          ORDER BY fecha_creacion DESC";
$resultado = mysqli_query($conexion, $query) or die(mysqli_error($conexion));

// Verificar si existen notificaciones
if (mysqli_num_rows($resultado) > 0) {
    // Mostrar cada notificación
    while ($notificacion = mysqli_fetch_assoc($resultado)) {
        $estado = $notificacion['leida'] ? "Leída" : "Nueva";
        echo "<div class='alert alert-info'>
            <strong>{$notificacion['titulo']}</strong><br>
            {$notificacion['mensaje']}<br>
            <small>{$notificacion['fecha_creacion']} - Estado: $estado</small><br>";
        if ($notificacion['id_empresa']) {
            echo "<a href='gestionar_empresas.php?id_empresa={$notificacion['id_empresa']}' class='btn btn-primary btn-sm'>Gestionar Empresa</a>";
        }
        echo "</div>";
    }
} else {
    // Mostrar mensaje si no hay notificaciones
    echo "<div class='alert alert-warning' role='alert'>
        No tienes notificaciones en este momento.
    </div>";
}
?>