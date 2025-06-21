<?php
// Configura la cabecera para que la respuesta sea en formato JSON (útil para AJAX)
header('Content-Type: application/json');

// Inicia la sesión para poder acceder a las variables de sesión del usuario autenticado
session_start();

// Incluye el archivo de conexión a la base de datos (debe definir la variable $conexion)
include 'conexion_be.php';

// Obtiene el correo del usuario desde la sesión; si no existe, se asigna vacío
$correo = $_SESSION['usuario'] ?? '';

// Si no hay correo (usuario no autenticado), responde con error y termina el script
if(!$correo){
    echo json_encode(['success'=>false]);
    exit();
}

// Busca el id del usuario en la base de datos usando el correo electrónico
$res = mysqli_query($conexion, "SELECT id FROM usuario WHERE correo_elec='$correo'");
$row = mysqli_fetch_assoc($res);
$id_usuario = $row['id'] ?? 0;

// Si no se encontró el usuario, responde con error y termina el script
if(!$id_usuario) {
    echo json_encode(['success'=>false]);
    exit();
}

// Busca si el usuario tiene datos faciales registrados en la tabla usuario_facial
$res2 = mysqli_query($conexion, "SELECT id FROM usuario_facial WHERE id_usuario=$id_usuario");
// Si hay al menos un registro, tiene datos faciales
$tieneFacial = mysqli_num_rows($res2) > 0;

// Devuelve una respuesta JSON indicando si tuvo éxito y si tiene registro facial
echo json_encode(['success'=>true, 'tieneFacial'=>$tieneFacial]);
?>