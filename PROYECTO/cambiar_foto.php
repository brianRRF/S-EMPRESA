<?php
session_start();
include 'conexion_be.php';

if (!isset($_SESSION['usuario'])) {
    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    Swal.fire({ title: "¡INICIA SESIÓN!",
    text: "Inicia sesión o regístrate si aún no lo has hecho.",
    icon: "error",
    confirmButtonText: "Ok" }).then((result) => {
        if (result.isConfirmed) {
            window.location = "inicio.php";
        }
    });
    </script> ';
    session_destroy();
    die();
}

$correo_elec = $_SESSION['usuario'];

// Inicializar mensaje de estado
$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto_perfil'])) {
    // Verifica si el archivo fue subido correctamente
    if ($_FILES['foto_perfil']['error'] == UPLOAD_ERR_OK) {
        // Datos del archivo
        $fileTmpPath = $_FILES['foto_perfil']['tmp_name'];
        $fileName = $_FILES['foto_perfil']['name'];
        $fileType = $_FILES['foto_perfil']['type'];

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
            $imgUrl = $responseDecoded['data']['url'];

            // Guardar la URL en la base de datos
            $query = "UPDATE usuario SET foto_perfil='$imgUrl' WHERE correo_elec='$correo_elec'";
            $ejecutar = mysqli_query($conexion, $query);

            if ($ejecutar) {
                $message = '<script>
                Swal.fire({ title: "¡Foto Actualizada!",
                text: "Tu foto de perfil ha sido actualizada correctamente.",
                icon: "success",
                confirmButtonText: "Ok" });
                </script>';
            } else {
                $message = '<script>
                Swal.fire({ title: "¡Error!",
                text: "Hubo un problema al actualizar tu foto de perfil en la base de datos.",
                icon: "error",
                confirmButtonText: "Ok" });
                </script>';
            }
        } else {
            $message = '<script>
            Swal.fire({ title: "¡Error!",
            text: "No se pudo subir la imagen a ImgBB. Inténtalo nuevamente más tarde.",
            icon: "error",
            confirmButtonText: "Ok" });
            </script>';
        }
    } else {
        $message = '<script>
        Swal.fire({ title: "¡Error!",
        text: "Hubo un problema al subir tu foto de perfil. Asegúrate de seleccionar un archivo válido.",
        icon: "error",
        confirmButtonText: "Ok" });
        </script>';
    }
}

// Obtener la foto de perfil actual del usuario
$query = "SELECT foto_perfil FROM usuario WHERE correo_elec='$correo_elec'";
$resultado = mysqli_query($conexion, $query);
$row = mysqli_fetch_assoc($resultado);
$foto_perfil = $row['foto_perfil'] ?? 'https://i.ibb.co/YdR6Rng/R.png'; // Imagen predeterminada

mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cambiar Foto de Perfil</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div>
    <form action="cambiar_foto.php" method="POST" enctype="multipart/form-data">
        <label for="foto_perfil">Subir nueva foto de perfil:</label>
        <input type="file" name="foto_perfil" id="foto_perfil" accept="image/*" required>
        <button type="submit">Cambiar Foto de Perfil</button>
    </form>
</div>

<!-- Mostrar mensaje -->
<?php if ($message) echo $message; ?>
</body>
</html>