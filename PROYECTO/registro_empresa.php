<?php
// Inicia o restaura la sesión
session_start();
include 'conexion_be.php'; // Conexión a la base de datos

// Restaurar sesión desde cookies si es necesario
if (isset($_COOKIE['usuario']) && !isset($_SESSION['usuario'])) {
    $_SESSION['usuario'] = $_COOKIE['usuario'];
    $_SESSION['nombre_usuario'] = $_COOKIE['nombre_usuario'];
    $_SESSION['id_cargo'] = $_COOKIE['id_cargo'];
    $_SESSION['id'] = $_COOKIE['id']; // Restaurar el ID desde la cookie
}

// Verifica que el usuario esté logueado
if (!isset($_SESSION['usuario']) || !isset($_SESSION['id'])) {
    echo '<script>
        alert("Debe iniciar sesión para registrar una empresa.");
        window.location = "login.php";
    </script>';
    exit();
}

// Obtener datos del formulario
$id_usuario = $_SESSION['id'];
$nombre_legal = mysqli_real_escape_string($conexion, $_POST['nombre_legal']);
$nombre_comercial = mysqli_real_escape_string($conexion, $_POST['nombre_comercial']);
$identificacion_fiscal = mysqli_real_escape_string($conexion, $_POST['identificacion_fiscal']);
$telefono_empresa = mysqli_real_escape_string($conexion, $_POST['telefono_empresa']);
$direccion_empresa = mysqli_real_escape_string($conexion, $_POST['direccion_empresa']);
$empresa_latitud = mysqli_real_escape_string($conexion, $_POST['empresa_latitud']);
$empresa_longitud = mysqli_real_escape_string($conexion, $_POST['empresa_longitud']);
$nombre_representante = mysqli_real_escape_string($conexion, $_POST['nombre_representante']);
$documento_identidad = mysqli_real_escape_string($conexion, $_POST['documento_identidad']);
$cargo_empresa = mysqli_real_escape_string($conexion, $_POST['cargo_empresa']);
$descripcion_negocio = mysqli_real_escape_string($conexion, $_POST['descripcion_negocio']);
$pagina_web = mysqli_real_escape_string($conexion, $_POST['pagina_web']);
$dominio_hosting = mysqli_real_escape_string($conexion, $_POST['dominio_hosting']);
$certificado_ssl = mysqli_real_escape_string($conexion, $_POST['certificado_ssl']);
$plataforma_cms = mysqli_real_escape_string($conexion, $_POST['plataforma_cms']);

// Manejo de archivos subidos
$uploads_dir = 'uploads';
if (!is_dir($uploads_dir)) {
    mkdir($uploads_dir, 0777, true);
}

$files = ['certificado_constitucion', 'registro_mercantil', 'licencias_permisos', 'documento_identidad_representante'];
$file_paths = [];
foreach ($files as $file) {
    if (isset($_FILES[$file]) && $_FILES[$file]['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES[$file]['tmp_name'];
        $name = basename($_FILES[$file]['name']);
        $path = "$uploads_dir/$name";
        if (move_uploaded_file($tmp_name, $path)) {
            $file_paths[$file] = $path;
        } else {
            echo '<script>
                alert("Error al subir el archivo ' . $file . '.");
                window.history.back();
            </script>';
            exit();
        }
    }
}

// Inserción en la tabla de empresa
$query = "INSERT INTO empresa (
    id,
    nombre_legal,
    nombre_comercial,
    identificacion_fiscal,
    telefono_empresa,
    direccion_empresa,
    empresa_latitud,
    empresa_longitud,
    nombre_representante,
    documento_identidad,
    cargo_empresa,
    descripcion_negocio,
    pagina_web,
    dominio_hosting,
    certificado_ssl,
    plataforma_cms,
    certificado_constitucion,
    registro_mercantil,
    licencias_permisos,
    documento_identidad_representante,
    estado
) VALUES (
    '$id_usuario',
    '$nombre_legal',
    '$nombre_comercial',
    '$identificacion_fiscal',
    '$telefono_empresa',
    '$direccion_empresa',
    '$empresa_latitud',
    '$empresa_longitud',
    '$nombre_representante',
    '$documento_identidad',
    '$cargo_empresa',
    '$descripcion_negocio',
    '$pagina_web',
    '$dominio_hosting',
    '$certificado_ssl',
    '$plataforma_cms',
    '{$file_paths['certificado_constitucion']}',
    '{$file_paths['registro_mercantil']}',
    '{$file_paths['licencias_permisos']}',
    '{$file_paths['documento_identidad_representante']}',
    'pendiente'
)";

if (mysqli_query($conexion, $query)) {
    // Obtener el ID de la empresa recién registrada
    $id_empresa = mysqli_insert_id($conexion);

    // Obtener los usuarios que son administradores
    $admin_query = "SELECT id FROM usuario WHERE id_cargo = 2";
    $admins = mysqli_query($conexion, $admin_query);

    // Insertar notificaciones para cada administrador
    $titulo = "Nueva empresa registrada";
    $mensaje = "La empresa \"$nombre_legal\" ha sido registrada y está pendiente de revisión.";
    while ($admin = mysqli_fetch_assoc($admins)) {
        $id_admin = $admin['id'];
        $notificacion_query = "INSERT INTO notificaciones_admin (id_usuario, id_empresa, titulo, mensaje) 
                               VALUES ($id_admin, $id_empresa, '$titulo', '$mensaje')";
        mysqli_query($conexion, $notificacion_query);
    }

    // Mostrar mensaje de éxito y redirigir al usuario a la página de bienvenida
    echo '<script>
        alert("Empresa registrada exitosamente. Los administradores han sido notificados.");
        window.location = "bienbenido.php";
    </script>';
} else {
    // Mostrar mensaje de error si ocurre un problema con la base de datos
    echo '<script>
        alert("Error al registrar la empresa: ' . mysqli_error($conexion) . '");
        window.history.back();
    </script>';
}
?>