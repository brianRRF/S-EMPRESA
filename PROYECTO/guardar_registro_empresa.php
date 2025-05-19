<?php
session_start();
header('Content-Type: application/json');

include 'conexion_be.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar que el usuario esté logueado
    if (!isset($_SESSION['id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Usuario no autenticado']);
        exit;
    }

    // Obtener el ID del usuario desde la sesión
    $usuario_id = $_SESSION['id'];

    // Validar los campos obligatorios
    $required_fields = ['nombre_legal', 'identificacion_fiscal', 'direccion_empresa'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            echo json_encode(['status' => 'error', 'message' => "El campo $field es obligatorio"]);
            exit;
        }
    }

    // Proceso para guardar archivos
    $uploads_dir = 'uploads/';
    if (!is_dir($uploads_dir)) {
        mkdir($uploads_dir, 0777, true);
    }

    $certificado_constitucion = $_FILES['certificado_constitucion']['name'] ?? null;
    $registro_mercantil = $_FILES['registro_mercantil']['name'] ?? null;

    if ($certificado_constitucion) {
        move_uploaded_file($_FILES['certificado_constitucion']['tmp_name'], $uploads_dir . $certificado_constitucion);
    }

    if ($registro_mercantil) {
        move_uploaded_file($_FILES['registro_mercantil']['tmp_name'], $uploads_dir . $registro_mercantil);
    }

    // Insertar datos en la base de datos
    $query = "INSERT INTO empresa (id, nombre_legal, identificacion_fiscal, direccion_empresa) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('isss', $usuario_id, $_POST['nombre_legal'], $_POST['identificacion_fiscal'], $_POST['direccion_empresa']);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'ok', 'message' => 'Registro exitoso']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al registrar la empresa']);
    }
}
?>