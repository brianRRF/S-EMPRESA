<?php
// Configura la cabecera para que la respuesta sea en formato JSON (ideal para peticiones AJAX)
header('Content-Type: application/json');

// Inicia la sesión para acceder a las variables de sesión del usuario autenticado
session_start();

// Incluye el archivo de conexión a la base de datos (debe definir la variable $conexion)
include 'conexion_be.php';

// Obtiene el correo del usuario desde la sesión; si no existe, $correo será vacío
$correo = $_SESSION['usuario'] ?? '';

// Si no hay correo (usuario no autenticado), responde con error y termina el script
if(!$correo) { 
    echo json_encode(['success'=>false, 'message'=>'No autenticado']); 
    exit(); 
}

// Busca el id del usuario en la base de datos usando el correo electrónico
$res = mysqli_query($conexion, "SELECT id FROM usuario WHERE correo_elec='$correo'");
$row = mysqli_fetch_assoc($res);
$id_usuario = $row['id'] ?? 0;

// Si se encontró el usuario (id válido)
if($id_usuario){
    // Elimina el registro facial del usuario (si existe) en la tabla usuario_facial
    mysqli_query($conexion, "DELETE FROM usuario_facial WHERE id_usuario=$id_usuario");
    // Actualiza el campo facial_activo a 0 (desactivado) en la tabla usuario
    mysqli_query($conexion, "UPDATE usuario SET facial_activo=0 WHERE id=$id_usuario");
    // Devuelve una respuesta JSON de éxito
    echo json_encode(['success'=>true]);
} else {
    // Si no se encontró el usuario, responde con mensaje de error
    echo json_encode(['success'=>false,'message'=>'Usuario no encontrado']);
}
?>