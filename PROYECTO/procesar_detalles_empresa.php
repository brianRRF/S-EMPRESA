<?php
// Inicia la sesión para usar variables de sesión (saber qué usuario está logueado)
session_start();

// Incluye el archivo de conexión a la base de datos (debe definir $conexion)
include 'conexion_be.php';

// Verifica si el usuario está logueado; si no, lo redirige a la página de inicio y termina el script
if (!isset($_SESSION['usuario'])) {
    header("location: inicio.php");
    exit();
}

// Recibe y sanitiza los datos enviados por POST desde el formulario
$id_empresa = intval($_POST['id_empresa']); // Convierte el ID de la empresa a entero para seguridad
$servicios = mysqli_real_escape_string($conexion, $_POST['servicios']);
$correo_empresa = mysqli_real_escape_string($conexion, $_POST['correo_empresa']);
$youtube_link = mysqli_real_escape_string($conexion, $_POST['youtube_link']);
$pinterest_link = mysqli_real_escape_string($conexion, $_POST['pinterest_link']);
$github_link = mysqli_real_escape_string($conexion, $_POST['github_link']);
$facebook_link = mysqli_real_escape_string($conexion, $_POST['facebook_link']);

$foto_empresa_url = null; // Inicializa la variable para la URL de la foto de la empresa
$error_subida = false;   // Bandera para saber si hubo error subiendo la imagen

// --- SUBIDA DE IMAGEN A ImgBB ---
if (isset($_FILES['foto_empresa']) && $_FILES['foto_empresa']['error'] == UPLOAD_ERR_OK) {
    // Si se subió una imagen correctamente:
    $fileTmpPath = $_FILES['foto_empresa']['tmp_name']; // Ruta temporal del archivo subido
    $fileName = $_FILES['foto_empresa']['name'];        // Nombre original del archivo
    $fileData = base64_encode(file_get_contents($fileTmpPath)); // Codifica la imagen en base64

    $imgbbApiKey = '07156e3c5b2c0ccc35236dbcaae4b5a9'; // Tu API key de ImgBB
    $imgbbApiUrl = 'https://api.imgbb.com/1/upload';    // URL de la API de ImgBB

    // Prepara los datos para enviar la imagen a ImgBB
    $postData = [
        'key'   => $imgbbApiKey,
        'image' => $fileData,
        'name'  => pathinfo($fileName, PATHINFO_FILENAME) // Nombre sin extensión
    ];

    // Inicia una petición cURL para subir la imagen
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $imgbbApiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    $response = curl_exec($ch);

    // Si la petición cURL falla:
    if ($response === false) {
        $error_subida = true;
        $error_msg = "Error en la conexión cURL: " . curl_error($ch);
    } else {
        // Si la petición cURL fue exitosa:
        $responseDecoded = json_decode($response, true);
        if (isset($responseDecoded['data']['url'])) {
            $foto_empresa_url = $responseDecoded['data']['url']; // URL pública de la imagen subida
        } elseif(isset($responseDecoded['error']['message'])) {
            // Si ImgBB responde con error
            $error_subida = true;
            $error_msg = "ImgBB: " . $responseDecoded['error']['message'];
        } else {
            // Si la respuesta no es válida
            $error_subida = true;
            $error_msg = "Respuesta ImgBB inválida: " . htmlspecialchars($response);
        }
    }
    curl_close($ch);
} else if (isset($_FILES['foto_empresa']) && $_FILES['foto_empresa']['error'] != UPLOAD_ERR_NO_FILE) {
    // Si hay error subiendo la imagen (pero no es "sin archivo")
    $error_subida = true;
    $error_msg = "Error al subir la imagen. Código: " . $_FILES['foto_empresa']['error'];
}

// Si hubo un error subiendo la imagen, muestra alerta y termina el script
if ($error_subida) {
    echo '<script>alert("'.$error_msg.'"); window.history.back();</script>';
    exit();
}

// Si no se subió imagen, el campo se deja en null (o puedes usar una imagen por defecto)
if (!$foto_empresa_url) $foto_empresa_url = null;

// --- INSERTAR O ACTUALIZAR DETALLES DE EMPRESA ---
$query_check = "SELECT id_detalle FROM empresa_detalles WHERE id_empresa = $id_empresa";
$result_check = mysqli_query($conexion, $query_check);

if (mysqli_num_rows($result_check) > 0) {
    // Si ya existen detalles para esta empresa, actualiza los datos
    $query_update = "
        UPDATE empresa_detalles SET
            foto_empresa = ".($foto_empresa_url ? "'$foto_empresa_url'" : "foto_empresa").",
            servicios = '$servicios',
            correo_empresa = '$correo_empresa',
            youtube_link = '$youtube_link',
            pinterest_link = '$pinterest_link',
            github_link = '$github_link',
            facebook_link = '$facebook_link'
        WHERE id_empresa = $id_empresa
    ";
    $ok = mysqli_query($conexion, $query_update);
} else {
    // Si no existen detalles, inserta un nuevo registro
    $query_insert = "
        INSERT INTO empresa_detalles (
            id_empresa, 
            foto_empresa, 
            servicios, 
            correo_empresa, 
            youtube_link, 
            pinterest_link, 
            github_link, 
            facebook_link
        ) VALUES (
            $id_empresa,
            ".($foto_empresa_url ? "'$foto_empresa_url'" : "NULL").",
            '$servicios',
            '$correo_empresa',
            '$youtube_link',
            '$pinterest_link',
            '$github_link',
            '$facebook_link'
        )
    ";
    $ok = mysqli_query($conexion, $query_insert);
}

// --- MARCAR LA EMPRESA COMO AUDITADA ---
// Actualiza la tabla empresa para indicar que ya fue auditada (auditada = 1)
$query_auditar = "UPDATE empresa SET auditada = 1 WHERE id_empresa = $id_empresa";
mysqli_query($conexion, $query_auditar);

// --- MENSAJE FINAL AL USUARIO ---
if ($ok) {
    echo '<script>alert("Detalles guardados correctamente. Ahora puedes publicar tu empresa."); window.location = "mis_empresas.php";</script>';
} else {
    echo '<script>alert("Error guardando en la base de datos: '.mysqli_error($conexion).'"); window.history.back();</script>';
}
?>