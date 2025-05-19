<?php
session_start();
include 'conexion_be.php';

if (!isset($_SESSION['usuario'])) {
    header("location: inicio.php");
    exit();
}

$id_empresa = $_POST['id_empresa'];
$servicios = mysqli_real_escape_string($conexion, $_POST['servicios']);
$correo_empresa = mysqli_real_escape_string($conexion, $_POST['correo_empresa']);
$youtube_link = mysqli_real_escape_string($conexion, $_POST['youtube_link']);
$pinterest_link = mysqli_real_escape_string($conexion, $_POST['pinterest_link']);
$github_link = mysqli_real_escape_string($conexion, $_POST['github_link']);
$facebook_link = mysqli_real_escape_string($conexion, $_POST['facebook_link']);

// Inicializar mensaje de estado
$message = null;

// Subir la foto de la empresa a ImgBB
$foto_empresa_path = null;
if (isset($_FILES['foto_empresa']) && $_FILES['foto_empresa']['error'] == UPLOAD_ERR_OK) {
    // Datos del archivo
    $fileTmpPath = $_FILES['foto_empresa']['tmp_name'];
    $fileName = $_FILES['foto_empresa']['name'];

    // Leer el contenido del archivo para enviarlo a ImgBB
    $fileData = base64_encode(file_get_contents($fileTmpPath));

    // Clave API de ImgBB
    $imgbbApiKey = '07156e3c5b2c0ccc35236dbcaae4b5a9'; // Reemplaza con tu clave API de ImgBB

    // URL de la API de ImgBB
    $imgbbApiUrl = 'https://api.imgbb.com/1/upload';

    // Datos para la solicitud POST
    $postData = [
        'key' => $imgbbApiKey,
        'image' => $fileData,
        'name' => pathinfo($fileName, PATHINFO_FILENAME)
    ];

    // Inicializar cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $imgbbApiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    // Ejecutar la solicitud y obtener la respuesta
    $response = curl_exec($ch);
    curl_close($ch);

    // Decodificar la respuesta JSON
    $responseDecoded = json_decode($response, true);

    if (isset($responseDecoded['data']['url'])) {
        // URL pública de la imagen
        $foto_empresa_path = $responseDecoded['data']['url'];
    } else {
        echo '<script>
            alert("Hubo un problema al subir la imagen a ImgBB. Inténtalo nuevamente más tarde.");
            window.history.back();
        </script>';
        exit();
    }
}

// Verifica si ya existen detalles para esta empresa
$query_check = "SELECT * FROM empresa_detalles WHERE id_empresa = $id_empresa";
$result_check = mysqli_query($conexion, $query_check);

if (mysqli_num_rows($result_check) > 0) {
    // Actualiza los detalles existentes
    $query_update = "
        UPDATE empresa_detalles
        SET 
            foto_empresa = '$foto_empresa_path',
            servicios = '$servicios',
            correo_empresa = '$correo_empresa',
            youtube_link = '$youtube_link',
            pinterest_link = '$pinterest_link',
            github_link = '$github_link',
            facebook_link = '$facebook_link'
        WHERE id_empresa = $id_empresa
    ";
    mysqli_query($conexion, $query_update) or die("Error al actualizar los detalles: " . mysqli_error($conexion));
} else {
    // Inserta nuevos detalles
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
            '$foto_empresa_path',
            '$servicios',
            '$correo_empresa',
            '$youtube_link',
            '$pinterest_link',
            '$github_link',
            '$facebook_link'
        )
    ";
    mysqli_query($conexion, $query_insert) or die("Error al guardar los detalles: " . mysqli_error($conexion));
}

// NOTA: No se actualiza el estado de auditoría para permitir publicación sin reauditoría.

echo '<script>
    alert("Los detalles de la empresa se guardaron correctamente. La imagen fue subida a ImgBB.");
    window.location = "mis_empresas.php";
</script>';
?>