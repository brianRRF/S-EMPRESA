<?php
session_start();
include 'conexion_be.php'; // Conexión a la base de datos
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

// Verifica que el usuario esté logueado como administrador
if (!isset($_SESSION['usuario']) || $_SESSION['id_cargo'] != 2) {
    echo "Acceso denegado.";
    exit();
}

// Obtiene los datos enviados desde el formulario
$id_empresa = $_POST['id_empresa'];
$id_usuario = $_POST['id_usuario'];
$correo_elec = $_POST['correo_elec'];
$nombre_c = $_POST['nombre_c'];
$motivo = mysqli_real_escape_string($conexion, trim($_POST['motivo']));

// Cambiar el estado de la empresa a "rechazada"
$update_query = "UPDATE empresa SET estado = 'rechazada' WHERE id_empresa = $id_empresa";
if (mysqli_query($conexion, $update_query)) {
    // Insertar la notificación en la base de datos
    $titulo = "Tu empresa ha sido rechazada";
    $mensaje = "Tu empresa ha sido rechazada por el siguiente motivo: \"$motivo\".";

    $notificacion_query = "INSERT INTO notificaciones (id_usuario, id_empresa, titulo, mensaje) 
                           VALUES ($id_usuario, $id_empresa, '$titulo', '$mensaje')";
    mysqli_query($conexion, $notificacion_query);

    // Enviar un correo al usuario
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 's.empresa24@gmail.com';
        $mail->Password = 'pufmdvvyblwtrcqf';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;
        $mail->setFrom('s.empresa24@gmail.com', 'SERVI EMPRESA');
        $mail->addAddress($correo_elec, $nombre_c);
        $mail->isHTML(true);
        $mail->Subject = 'Motivo de Rechazo - Servi Empresa';
        $mail->Body = "
            <h2>Hola, $nombre_c</h2>
            <p>Lamentamos informarte que tu empresa ha sido rechazada por el siguiente motivo:</p>
            <blockquote>$motivo</blockquote>
            <p>
                Te invitamos a corregir los problemas mencionados y volver a enviar tu solicitud.
            </p>
            <p>Gracias,<br>Equipo de SERVI EMPRESA</p>";
        $mail->send();

        echo '<script>
            alert("Empresa rechazada y el usuario ha sido notificado.");
            window.location = "gestionar_empresas.php";
        </script>';
    } catch (Exception $e) {
        echo '<script>
            alert("Empresa rechazada, pero ocurrió un error al enviar el correo: ' . $mail->ErrorInfo . '");
            window.location = "gestionar_empresas.php";
        </script>';
    }
} else {
    echo '<script>
        alert("Error al rechazar la empresa.");
        window.location = "gestionar_empresas.php";
    </script>';
}
?>