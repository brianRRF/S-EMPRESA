<?php
session_start();
include 'conexion_be.php'; // Conexión a la base de datos

if (!isset($_SESSION['usuario'])) {
    header("location: inicio.php");
    exit();
}

$id_usuario = $_SESSION['id'];

$query = "SELECT titulo, mensaje, leida, fecha_creacion, id_empresa 
          FROM notificaciones 
          WHERE id_usuario = $id_usuario 
          ORDER BY fecha_creacion DESC";
$resultado = mysqli_query($conexion, $query);

if (mysqli_num_rows($resultado) > 0) {
    while ($notificacion = mysqli_fetch_assoc($resultado)) {
        $estado = $notificacion['leida'] ? "Leída" : "Nueva";
        echo "<div class='alert alert-info'>
            <strong>{$notificacion['titulo']}</strong><br>
            {$notificacion['mensaje']}<br>
            <small>{$notificacion['fecha_creacion']} - Estado: $estado</small><br>";
        if ($notificacion['id_empresa']) {
            echo "<a href='mis_empresas.php?id_empresa={$notificacion['id_empresa']}' class='btn btn-primary btn-sm'>Ver Empresa</a>";
        }
        echo "</div>";
    }
} else {
    echo "<div class='alert alert-warning' role='alert'>
        No tienes notificaciones en este momento.
    </div>";
}
?>