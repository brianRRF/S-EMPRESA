<?php
session_start();
include 'conexion_be.php'; 

if (!isset($_SESSION['usuario'])) {
    header("location: inicio.php");
    exit();
}

$id_usuario = $_SESSION['id'];
$id_empresa = $_GET['id'];

// Verifica que la empresa pertenece al usuario logueado
$query = "SELECT * FROM empresa WHERE id_empresa = $id_empresa AND id = $id_usuario";
$resultado = mysqli_query($conexion, $query);

if (mysqli_num_rows($resultado) === 0) {
    echo '<script>
        alert("No tienes permiso para editar esta empresa.");
        window.location = "mis_empresas.php";
    </script>';
    exit();
}

// Si la verificación pasa, carga los datos y muestra el formulario de edición
// ...
?>