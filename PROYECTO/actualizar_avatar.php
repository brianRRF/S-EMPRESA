<?php
// Inicia la sesión para poder usar variables de sesión (como el usuario logueado)
session_start();

// Incluye el archivo de conexión a la base de datos (debe definir $conexion)
include 'conexion_be.php';

// Verifica si el usuario está autenticado. 
// Si no está logueado, lo redirige a la página de inicio y termina el script.
if (!isset($_SESSION['usuario'])) {
    header('Location: inicio.php'); 
    exit();
}

// Obtiene el correo electrónico del usuario desde la variable de sesión
$correo_elec = $_SESSION['usuario'];

// Verifica si la petición es de tipo POST y si se ha subido un archivo llamado 'foto_perfil'
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto_perfil'])) {
    // Verifica que la subida del archivo no tenga errores
    if ($_FILES['foto_perfil']['error'] == UPLOAD_ERR_OK) {
        // Obtiene la ruta temporal donde PHP guardó el archivo subido
        $fileTmpPath = $_FILES['foto_perfil']['tmp_name'];
        // Obtiene el nombre original del archivo (incluye la extensión)
        $fileName = $_FILES['foto_perfil']['name'];
        // Lee el contenido del archivo y lo codifica en base64 (formato requerido por ImgBB)
        $fileData = base64_encode(file_get_contents($fileTmpPath));

        // Define la API Key de ImgBB para autenticar la subida
        $imgbbApiKey = '07156e3c5b2c0ccc35236dbcaae4b5a9'; // tu clave ImgBB
        // Define la URL de la API de ImgBB para subir imágenes
        $imgbbApiUrl = 'https://api.imgbb.com/1/upload';

        // Prepara los datos que se enviarán por POST a ImgBB
        $postData = [
            'key' => $imgbbApiKey, // Tu clave de API
            'image' => $fileData, // La imagen codificada en base64
            'name' => pathinfo($fileName, PATHINFO_FILENAME) // El nombre del archivo (sin extensión)
        ];

        // Inicializa una sesión cURL para hacer la petición HTTP a ImgBB
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $imgbbApiUrl); // Establece la URL de la API
        curl_setopt($ch, CURLOPT_POST, true); // Indica que será una petición POST
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Indica que se debe devolver la respuesta como string
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData); // Adjunta los datos al POST
        $response = curl_exec($ch); // Ejecuta la petición y almacena la respuesta
        curl_close($ch); // Cierra la sesión cURL

        // Decodifica la respuesta JSON en un array asociativo
        $responseDecoded = json_decode($response, true);

        // Verifica si la subida fue exitosa y se recibió la URL de la imagen
        if (isset($responseDecoded['data']['url'])) {
            // Recupera la URL de la imagen subida a ImgBB
            $imgUrl = $responseDecoded['data']['url'];
            // Prepara la consulta SQL para actualizar la foto de perfil del usuario en la base de datos
            $query = "UPDATE usuario SET foto_perfil='$imgUrl' WHERE correo_elec='$correo_elec'";
            // Ejecuta la consulta de actualización
            $ejecutar = mysqli_query($conexion, $query);
            // Si la actualización fue exitosa, redirige al dashboard
            if ($ejecutar) {
                echo "<script>window.location='dashboard.php';</script>";
                exit;
            }
        }
    }
}
// Si el proceso falla en cualquier punto, redirige igualmente al dashboard
header('Location: perfilconfigg.php');
exit();
?>