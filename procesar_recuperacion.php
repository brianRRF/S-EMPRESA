<?php
include 'conexion_be.php'; // Archivo de conexión a la base de datos

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = mysqli_real_escape_string($conexion, trim($_POST['correo_elec'] ?? ''));

    if (empty($correo)) {
        echo '<script>alert("El campo de correo es obligatorio."); window.location = "recuperar.php";</script>';
        exit();
    }

    // Verificar si el correo ya está registrado
    $consulta_usuario = "SELECT * FROM usuario WHERE correo_elec = '$correo'";
    $resultado_usuario = mysqli_query($conexion, $consulta_usuario);

    if (mysqli_num_rows($resultado_usuario) === 0) {
        echo '<script>alert("El correo no está registrado. Por favor, regístrese."); window.location = "registro.php";</script>';
        exit();
    }

    // Verificar si ya existe un código para el correo en los últimos 15 minutos
    $consulta_codigo = "SELECT * FROM recuperacion WHERE correo = '$correo' AND fecha_caducidad > NOW()";
    $resultado_codigo = mysqli_query($conexion, $consulta_codigo);

    if (mysqli_num_rows($resultado_codigo) > 0) {
        echo '<script>alert("Ya se ha enviado un código. Por favor, verifica tu correo."); window.location = "verificar_codigo.php";</script>';
        exit();
    }

    // Generar un nuevo código de 6 dígitos
    $codigo = rand(100000, 999999);
    $fecha_creacion = date('Y-m-d H:i:s');
    $fecha_caducidad = date('Y-m-d H:i:s', strtotime('+15 minutes'));

    // Insertar el nuevo código en la base de datos
    $insertar_codigo = "INSERT INTO recuperacion (correo, codigo, fecha_creacion, fecha_caducidad) 
                        VALUES ('$correo', '$codigo', '$fecha_creacion', '$fecha_caducidad')";

    if (mysqli_query($conexion, $insertar_codigo)) {
        // Enviar el correo con el código
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 's.empresa24@gmail.com';
            $mail->Password = 'pufmdvvyblwtrcqf'; // Contraseña de aplicación
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('s.empresa24@gmail.com', 'SERVI EMPRESA');
            $mail->addAddress($correo);

            $mail->isHTML(true);
            $mail->Subject = 'CODIGO DE RECUPERACION';
            $mail->Body = "HOLA, <strong>$correo</strong>:
             <br> <br> Hemos recibido tu solicitud para recuperar tu contraseña con un codigo de un solo uso
             <br> <strong>S-EMPRESA</strong>
             <br> <br> TU CODIGO DE RECUPERACION ES: <h2>$codigo</h2>
             <br> Escriba este codigo en el apartado de verificacion de registro en el sitio web oficial.
             <br> No lo comparta con nadie.
             <br> <br> Muchas gracias.
             <br> EQUIPO DE LAS CUENTAS DE S-EMPRESA";

            $mail->send();

            echo '<script>alert("Se ha enviado un código a tu correo."); window.location = "verificar_codigo.php";</script>';
        } catch (Exception $e) {
            echo '<script>alert("No se pudo enviar el correo. Por favor, inténtelo de nuevo."); window.location = "recuperar.php";</script>';
        }
    } else {
        echo '<script>alert("Error al generar el código. Por favor, inténtelo de nuevo."); window.location = "recuperar.php";</script>';
    }
}

mysqli_close($conexion);