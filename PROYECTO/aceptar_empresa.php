<?php
session_start();
include 'conexion_be.php'; // Conexión a la base de datos
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

if (!isset($_SESSION['usuario']) || $_SESSION['id_cargo'] != 2) {
    echo "Acceso denegado.";
    exit();
}

$id_empresa = $_GET['id'];
$query = "SELECT e.*, u.correo_elec, u.nombre_c, u.id AS id_usuario
          FROM empresa e
          JOIN usuario u ON e.id = u.id
          WHERE e.id_empresa = $id_empresa";
$resultado = mysqli_query($conexion, $query);

if ($empresa = mysqli_fetch_assoc($resultado)) {
    $update_query = "UPDATE empresa SET estado = 'aceptada' WHERE id_empresa = $id_empresa";
    if (mysqli_query($conexion, $update_query)) {
        // Insertar la notificación en la base de datos
        $id_usuario = $empresa['id_usuario'];
        $titulo = "Tu empresa ha sido aceptada";
        $mensaje = "Tu empresa \"{$empresa['nombre_legal']}\" ha sido aceptada. Puedes editar y publicar.";
        
        // Ahora incluye el `id_empresa` en la notificación
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
            $mail->addAddress($empresa['correo_elec'], $empresa['nombre_c']);
            $mail->isHTML(true);
            $mail->Subject = '¡Tu empresa ha sido aceptada!';
            $mail->Body = "
                <h2>¡Felicidades, {$empresa['nombre_representante']}!</h2>
                <p>
                    Tu empresa <strong>{$empresa['nombre_legal']}</strong> ha sido aceptada por nuestro equipo.
                </p>
                <p>
                    Accede a tu cuenta para editar y publicar tu empresa en Servi Empresa.
                </p>
                <p>
                    <a href='https://tu-sitio.com/mis_empresas.php?id_empresa=$id_empresa' style='background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Acceder a Mis Empresas</a>
                </p>
                <p>Gracias,<br>Equipo de SERVI EMPRESA</p>";
            $mail->send();
            echo '<script>
                alert("Empresa aceptada y el usuario ha sido notificado.");
                window.location = "gestionar_empresas.php";
            </script>';
        } catch (Exception $e) {
            echo '<script>
                alert("Error al enviar el correo: ' . $mail->ErrorInfo . '");
                window.location = "gestionar_empresas.php";
            </script>';
        }
    }
}
?>