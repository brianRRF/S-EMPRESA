<?php
// Configuración de la base de datos
include 'conexion_be.php'; // Asegurar la conexión a la base de datos

// Clave API de ImgBB
$imgbbApiKey = '07156e3c5b2c0ccc35236dbcaae4b5a9'; // Reemplaza con tu clave API de ImgBB

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $categoria = $_POST['categoria'];

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
        // Procesar la imagen
        $fileTmpPath = $_FILES['imagen']['tmp_name'];
        $fileName = $_FILES['imagen']['name'];

        // Leer el contenido del archivo para enviarlo a ImgBB
        $fileData = base64_encode(file_get_contents($fileTmpPath));

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

            // Guardar los datos en la base de datos
            $query = "INSERT INTO posts (titulo, descripcion, categoria, imagen_url) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssss", $titulo, $descripcion, $categoria, $imgUrl);

            if ($stmt->execute()) {
                // Redirigir al archivo index.php después de guardar
                header("Location: index.php");
                exit();
            } else {
                echo '<script>
                alert("Error al agregar el post.");
                window.history.back();
                </script>';
            }
            $stmt->close();
        } else {
            echo '<script>
            alert("Error al subir la imagen. Intenta nuevamente.");
            window.history.back();
            </script>';
        }
    } else {
        echo '<script>
        alert("Error al subir la imagen. Selecciona un archivo válido.");
        window.history.back();
        </script>';
    }
}

$conn->close();
?>