<?php
// Inicia la sesión para poder acceder a las variables de sesión del usuario
session_start();

// Indica que la respuesta del servidor será en formato JSON
header('Content-Type: application/json');

// Incluye el archivo de conexión a la base de datos (debe definir la variable $conexion)
include 'conexion_be.php';

// Verifica que la solicitud sea de tipo POST (por seguridad, solo se aceptan envíos de formularios)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica que exista la variable de sesión 'id' (usuario autenticado)
    if (!isset($_SESSION['id'])) {
        // Si el usuario no está autenticado, devuelve un error en formato JSON y termina el script
        echo json_encode(['status' => 'error', 'message' => 'Usuario no autenticado']);
        exit;
    }

    // Guarda el id del usuario autenticado en una variable
    $usuario_id = $_SESSION['id'];

    // Define los campos obligatorios que deben estar presentes en la solicitud POST
    $required_fields = ['nombre_legal', 'identificacion_fiscal', 'direccion_empresa'];
    // Recorre cada campo obligatorio y verifica que esté presente y no esté vacío
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            // Si falta algún campo, devuelve un error indicando el campo faltante y termina la ejecución
            echo json_encode(['status' => 'error', 'message' => "El campo $field es obligatorio"]);
            exit;
        }
    }

    // Define el directorio donde se guardarán los archivos subidos
    $uploads_dir = 'uploads/';
    // Si el directorio no existe, lo crea con permisos 0777 (lectura, escritura y ejecución para todos)
    if (!is_dir($uploads_dir)) {
        mkdir($uploads_dir, 0777, true);
    }

    // Obtiene el nombre del archivo 'certificado_constitucion' si fue subido, o null si no se envió
    $certificado_constitucion = $_FILES['certificado_constitucion']['name'] ?? null;
    // Obtiene el nombre del archivo 'registro_mercantil' si fue subido, o null si no se envió
    $registro_mercantil = $_FILES['registro_mercantil']['name'] ?? null;

    // Si se subió el archivo 'certificado_constitucion', lo mueve del directorio temporal al directorio final
    if ($certificado_constitucion) {
        move_uploaded_file($_FILES['certificado_constitucion']['tmp_name'], $uploads_dir . $certificado_constitucion);
    }

    // Si se subió el archivo 'registro_mercantil', lo mueve del directorio temporal al directorio final
    if ($registro_mercantil) {
        move_uploaded_file($_FILES['registro_mercantil']['tmp_name'], $uploads_dir . $registro_mercantil);
    }

    // Prepara la consulta SQL para insertar la nueva empresa, usando valores parametrizados para seguridad
    $query = "INSERT INTO empresa (id, nombre_legal, identificacion_fiscal, direccion_empresa) VALUES (?, ?, ?, ?)";
    // Prepara la sentencia SQL para evitar inyección SQL
    $stmt = $conexion->prepare($query);
    // Asocia las variables recibidas por POST y la de sesión a los parámetros de la consulta
    $stmt->bind_param('isss', $usuario_id, $_POST['nombre_legal'], $_POST['identificacion_fiscal'], $_POST['direccion_empresa']);

    // Ejecuta la consulta: si es exitosa, responde con OK; si falla, responde con error
    if ($stmt->execute()) {
        echo json_encode(['status' => 'ok', 'message' => 'Registro exitoso']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al registrar la empresa']);
    }
}
?>