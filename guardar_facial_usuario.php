<?php
// Indica que la respuesta será en formato JSON
header('Content-Type: application/json');

// Inicia la sesión para acceder a variables de sesión (usuario logueado)
session_start();

// Incluye el archivo de conexión a la base de datos (debe definir $conexion)
include 'conexion_be.php';

// Decodifica los datos enviados en formato JSON (por ejemplo, desde una petición AJAX)
$data = json_decode(file_get_contents("php://input"), true);

// Obtiene el correo del usuario desde la sesión (si existe)
$correo = $_SESSION['usuario'] ?? '';

// Si el usuario no está autenticado, devuelve error y termina el script
if(!$correo) { 
    echo json_encode(['success'=>false, 'message'=>'No autenticado']); 
    exit(); 
}

// Verifica que se hayan recibido tanto el descriptor facial como la imagen desde el frontend
if(!$data || !isset($data['descriptor']) || !isset($data['imagen'])) {
    echo json_encode(['success'=>false, 'message'=>'Datos incompletos']);
    exit();
}

// Codifica el descriptor facial como JSON para almacenarlo en la base de datos
$descriptor = json_encode($data['descriptor']);

// Obtiene la imagen en base64 y limpia el prefijo (en caso de que venga como 'data:image/png;base64,...')
$imagenBase64 = $data['imagen'];
$img = preg_replace('#^data:image/\w+;base64,#i', '', $imagenBase64);

// Configura los datos para la API de ImgBB
$imgbbApiKey = '07156e3c5b2c0ccc35236dbcaae4b5a9'; // Tu API key de ImgBB
$imgbbApiUrl = 'https://api.imgbb.com/1/upload';

$postData = [
    'key' => $imgbbApiKey,
    'image' => $img,
    'name' => 'facial_' . uniqid()
];

// Realiza la petición a ImgBB para subir la imagen facial
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $imgbbApiUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
$response = curl_exec($ch);
curl_close($ch);

// Decodifica la respuesta de ImgBB
$responseDecoded = json_decode($response, true);

// Si la imagen fue subida correctamente a ImgBB
if (isset($responseDecoded['data']['url'])) {
    $imgUrl = $responseDecoded['data']['url'];

    // Busca el ID del usuario autenticado
    $res = mysqli_query($conexion, "SELECT id FROM usuario WHERE correo_elec='$correo'");
    $row = mysqli_fetch_assoc($res);
    $id_usuario = $row['id'] ?? 0;

    if($id_usuario){
        // Borra el registro facial anterior (si existe) para este usuario
        mysqli_query($conexion, "DELETE FROM usuario_facial WHERE id_usuario=$id_usuario");
        
        // Inserta el nuevo descriptor facial y la URL de la imagen en la tabla usuario_facial
        $sql = "INSERT INTO usuario_facial (id_usuario, facial_descriptor, facial_image) VALUES ($id_usuario, '$descriptor', '$imgUrl')";
        if(mysqli_query($conexion, $sql)){
            // Marca el facial como activo en la tabla usuario
            mysqli_query($conexion, "UPDATE usuario SET facial_activo=1 WHERE id=$id_usuario");
            echo json_encode(['success'=>true]);
        } else {
            echo json_encode(['success'=>false,'message'=>mysqli_error($conexion)]);
        }
    } else {
        echo json_encode(['success'=>false,'message'=>'Usuario no encontrado']);
    }
} else {
    echo json_encode(['success'=>false,'message'=>'Error al subir imagen facial a ImgBB']);
}
?>