<?php
// Inicia la sesión para usar variables de sesión (como el usuario logueado)
session_start();

// Incluye el archivo de conexión a la base de datos (debe definir $conexion)
include 'conexion_be.php';

// Lee la entrada JSON recibida (por ejemplo, desde una petición AJAX) y la decodifica a un array asociativo
$data = json_decode(file_get_contents("php://input"), true);

// Obtiene el correo electrónico del usuario desde la sesión (si existe), o una cadena vacía si no existe
$correo = $_SESSION['usuario'] ?? '';

// Si no hay correo, el usuario no está autenticado; responde con JSON de error y termina el script
if(!$correo) { 
    echo json_encode(['success'=>false, 'message'=>'No autenticado']); 
    exit(); 
}

// Sanitiza y extrae cada campo recibido del formulario/JSON para evitar inyecciones SQL y espacios innecesarios
$nombre = mysqli_real_escape_string($conexion, trim($data['nombreCompleto'] ?? ''));
$sexo = mysqli_real_escape_string($conexion, trim($data['sexo'] ?? ''));
$fecha_nacimiento = mysqli_real_escape_string($conexion, trim($data['fechaNacimiento'] ?? ''));
$edad = mysqli_real_escape_string($conexion, trim($data['edad'] ?? ''));
$bio = mysqli_real_escape_string($conexion, trim($data['bio'] ?? ''));
$telefono = mysqli_real_escape_string($conexion, trim($data['telefono'] ?? ''));
$localidad = mysqli_real_escape_string($conexion, trim($data['localidad'] ?? ''));
$contrasena = trim($data['password'] ?? '');
$contrasena2 = trim($data['password2'] ?? '');

// Prepara la consulta SQL para actualizar los campos del usuario
$update = "UPDATE usuario SET 
    nombre='$nombre', 
    sexo='$sexo', 
    fecha_nacimiento='$fecha_nacimiento', 
    edad='$edad', 
    bio='$bio', 
    telefono='$telefono', 
    localidad='$localidad'";

// Si se recibió una contraseña y ambas coinciden, la actualiza (usando hash seguro)
if($contrasena && $contrasena === $contrasena2) {
    $hashed = hash('sha512', $contrasena); // Hashea la contraseña con SHA-512
    $update .= ", contrasena='$hashed'";
}

// Completa la consulta SQL, filtrando por el correo electrónico (usuario actual)
$update .= " WHERE correo_elec='$correo'";

// Ejecuta la consulta y responde con JSON según si fue exitosa o no
if(mysqli_query($conexion, $update)){
    echo json_encode(['success'=>true]);
}else{
    echo json_encode(['success'=>false,'message'=>mysqli_error($conexion)]);
}
?>