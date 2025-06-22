<?php
// Inicia la sesión para acceder a variables de sesión (usuario logueado)
session_start();

// Incluye el archivo de conexión a la base de datos (asegura que $conexion está definido)
include 'conexion_be.php';

// Importa las clases necesarias de PHPMailer para enviar correos electrónicos
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Incluye el autoloader de Composer (para cargar PHPMailer y sus dependencias)
require 'vendor/autoload.php';

// Verifica que el usuario esté logueado Y que tenga cargo de administrador (id_cargo = 2)
if (!isset($_SESSION['usuario']) || $_SESSION['id_cargo'] != 2) {
    echo "Acceso denegado.";
    exit();
}

// Obtiene los datos enviados desde el formulario por POST
$id_empresa = $_POST['id_empresa']; // ID de la empresa a rechazar
$id_usuario = $_POST['id_usuario']; // ID del usuario dueño de la empresa
$correo_elec = $_POST['correo_elec']; // Correo electrónico del usuario
$nombre_c = $_POST['nombre_c']; // Nombre del usuario
$motivo = mysqli_real_escape_string($conexion, trim($_POST['motivo'])); // Motivo del rechazo (sanitizado)

// Cambia el estado de la empresa a "rechazada" en la base de datos
$update_query = "UPDATE empresa SET estado = 'rechazada' WHERE id_empresa = $id_empresa";
if (mysqli_query($conexion, $update_query)) {
    // Si el cambio fue exitoso:

    // 1. Inserta una notificación interna para el usuario dueño de la empresa
    $titulo = "Tu empresa ha sido rechazada";
    $mensaje = "Tu empresa ha sido rechazada por el siguiente motivo: \"$motivo\".";

    $notificacion_query = "INSERT INTO notificaciones (id_usuario, id_empresa, titulo, mensaje) 
                           VALUES ($id_usuario, $id_empresa, '$titulo', '$mensaje')";
    mysqli_query($conexion, $notificacion_query);

    // 2. Envía un correo electrónico al usuario explicando el motivo del rechazo
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP(); // Usar SMTP para el envío de correo
        $mail->Host = 'smtp.gmail.com'; // Servidor SMTP de Gmail
        $mail->SMTPAuth = true; // Habilitar autenticación SMTP
        $mail->Username = 's.empresa24@gmail.com'; // Usuario de Gmail que envía
        $mail->Password = 'pufmdvvyblwtrcqf'; // Contraseña de aplicación de Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Cifrado SSL
        $mail->Port = 465; // Puerto seguro de Gmail para SMTP

        $mail->setFrom('s.empresa24@gmail.com', 'SERVI EMPRESA'); // Remitente
        $mail->addAddress($correo_elec, $nombre_c); // Destinatario

        $mail->isHTML(true); // El correo será HTML
        $mail->Subject = 'Motivo de Rechazo - Servi Empresa'; // Asunto del correo

        // Cuerpo del correo HTML personalizado
        $mail->Body = "
            <h2>Hola, $nombre_c</h2>
            <p>Lamentamos informarte que tu empresa ha sido rechazada por el siguiente motivo:</p>
            <blockquote>$motivo</blockquote>
            <p>
                Te invitamos a corregir los problemas mencionados y volver a enviar tu solicitud.
            </p>
            <p>Gracias,<br>Equipo de SERVI EMPRESA</p>";

        $mail->send(); // Envía el correo

        // Si todo sale bien, muestra alerta y redirige al panel de gestión
        echo '<script>
            alert("Empresa rechazada y el usuario ha sido notificado.");
            window.location = "gestionar_empresas.php";
        </script>';
    } catch (Exception $e) {
        // Si hay error al enviar el correo, muestra alerta con el error y redirige
        echo '<script>
            alert("Empresa rechazada, pero ocurrió un error al enviar el correo: ' . $mail->ErrorInfo . '");
            window.location = "gestionar_empresas.php";
        </script>';
    }
} else {
    // Si falla la actualización del estado, muestra alerta y redirige
    echo '<script>
        alert("Error al rechazar la empresa.");
        window.location = "gestionar_empresas.php";
    </script>';
}
?>