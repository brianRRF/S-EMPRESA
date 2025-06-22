<?php
// Inicia o restaura la sesión del usuario
session_start();

// Incluye el archivo con la conexión a la base de datos
include 'conexion_be.php';

// --- RESTAURAR SESIÓN DESDE COOKIES SI ES NECESARIO ---
if (isset($_COOKIE['usuario']) && !isset($_SESSION['usuario'])) {
    // Si existe la cookie y no la sesión, recupera los datos de la cookie
    $_SESSION['usuario'] = $_COOKIE['usuario'];
    $_SESSION['nombre_usuario'] = $_COOKIE['nombre_usuario'];
    $_SESSION['id_cargo'] = $_COOKIE['id_cargo'];
    $_SESSION['id'] = $_COOKIE['id']; // También el ID único del usuario
}

// --- VERIFICACIÓN DE AUTENTICACIÓN ---
// Si el usuario no está logueado correctamente, muestra alerta y redirige al login
if (!isset($_SESSION['usuario']) || !isset($_SESSION['id'])) {
    echo '<script>
        alert("Debe iniciar sesión para registrar una empresa.");
        window.location = "login.php";
    </script>';
    exit();
}

// --- OBTENCIÓN Y SANITIZACIÓN DE DATOS DEL FORMULARIO ---
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

// --- MANEJO DE ARCHIVOS SUBIDOS ---
$uploads_dir = 'uploads'; // Carpeta donde se guardarán los archivos
if (!is_dir($uploads_dir)) {
    mkdir($uploads_dir, 0777, true); // Crea la carpeta si no existe
}

// Lista de archivos esperados en el formulario
$files = ['certificado_constitucion', 'registro_mercantil', 'licencias_permisos', 'documento_identidad_representante'];
$file_paths = [];
foreach ($files as $file) {
    // Si el archivo fue subido sin errores
    if (isset($_FILES[$file]) && $_FILES[$file]['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES[$file]['tmp_name']; // Ruta temporal
        $name = basename($_FILES[$file]['name']); // Nombre original del archivo
        $path = "$uploads_dir/$name"; // Ruta final
        if (move_uploaded_file($tmp_name, $path)) {
            $file_paths[$file] = $path; // Guarda la ruta en el arreglo
        } else {
            // Si hay error al mover, muestra alerta y regresa a la página anterior
            echo '<script>
                alert("Error al subir el archivo ' . $file . '.");
                window.history.back();
            </script>';
            exit();
        }
    }
}

// --- INSERCIÓN DE DATOS EN LA TABLA EMPRESA ---
// Prepara la consulta INSERT con todos los datos y rutas de archivos
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

// --- EJECUTAR LA INSERCIÓN Y NOTIFICAR A LOS ADMINISTRADORES ---
if (mysqli_query($conexion, $query)) {
    // Si la inserción fue exitosa, obtiene el id de la empresa registrada
    $id_empresa = mysqli_insert_id($conexion);

    // Busca todos los usuarios con rol de administrador (id_cargo = 2)
    $admin_query = "SELECT id FROM usuario WHERE id_cargo = 2";
    $admins = mysqli_query($conexion, $admin_query);

    // Prepara el título y el mensaje de notificación
    $titulo = "Nueva empresa registrada";
    $mensaje = "La empresa \"$nombre_legal\" ha sido registrada y está pendiente de revisión.";

    // Crea una notificación para cada administrador
    while ($admin = mysqli_fetch_assoc($admins)) {
        $id_admin = $admin['id'];
        $notificacion_query = "INSERT INTO notificaciones_admin (id_usuario, id_empresa, titulo, mensaje) 
                               VALUES ($id_admin, $id_empresa, '$titulo', '$mensaje')";
        mysqli_query($conexion, $notificacion_query);
    }

    // Muestra mensaje de éxito y redirige al usuario
    echo '<script>
        alert("Empresa registrada exitosamente. Los administradores han sido notificados.");
        window.location = "bienbenido.php";
    </script>';
} else {
    // Si hay error en la base de datos, muestra el error y regresa
    echo '<script>
        alert("Error al registrar la empresa: ' . mysqli_error($conexion) . '");
        window.history.back();
    </script>';
}
?>