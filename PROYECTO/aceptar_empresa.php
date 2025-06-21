<?php
// Inicia la sesión PHP para poder usar variables de sesión (por ejemplo, para saber quién está logueado)
session_start();

// Incluye el archivo de conexión a la base de datos. Este archivo debe definir la variable $conexion.
include 'conexion_be.php';

// Importa las clases PHPMailer necesarias para enviar emails.
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Incluye el autoloader de Composer, que carga automáticamente las clases necesarias (incluyendo PHPMailer).
require 'vendor/autoload.php';

// Verifica si el usuario está logueado y si tiene el cargo correcto (por ejemplo, administrador)
// $_SESSION['usuario'] debe existir y $_SESSION['id_cargo'] debe ser igual a 2 para continuar
if (!isset($_SESSION['usuario']) || $_SESSION['id_cargo'] != 2) {
    // Si NO cumple los requisitos, se muestra un mensaje y se detiene el script
    echo "Acceso denegado.";
    exit();
}

// Obtiene el ID de la empresa a aceptar desde la URL con el método GET (ejemplo: aceptar_empresa.php?id=5)
$id_empresa = $_GET['id'];

// Prepara una consulta SQL para obtener los datos de la empresa y del usuario que la registró
// Se usa un JOIN para relacionar la empresa con el usuario usando el campo 'id'
$query = "SELECT e.*, u.correo_elec, u.nombre_c, u.id AS id_usuario
          FROM empresa e
          JOIN usuario u ON e.id = u.id
          WHERE e.id_empresa = $id_empresa";

// Ejecuta la consulta y guarda el resultado
$resultado = mysqli_query($conexion, $query);

// Extrae la información de la empresa y el usuario del resultado de la consulta, si existe
if ($empresa = mysqli_fetch_assoc($resultado)) {
    // Prepara una consulta SQL para actualizar el estado de la empresa a 'aceptada'
    $update_query = "UPDATE empresa SET estado = 'aceptada' WHERE id_empresa = $id_empresa";
    // Ejecuta la consulta de actualización
    if (mysqli_query($conexion, $update_query)) {
        // Si la actualización fue exitosa, continúa el proceso

        // Obtiene el ID del usuario dueño de la empresa desde el resultado anterior
        $id_usuario = $empresa['id_usuario'];
        // Define el título de la notificación
        $titulo = "Tu empresa ha sido aceptada";
        // Define el mensaje de la notificación, incluyendo el nombre legal de la empresa entre comillas
        $mensaje = "Tu empresa \"{$empresa['nombre_legal']}\" ha sido aceptada. Puedes editar y publicar.";

        // Prepara una consulta SQL para insertar una notificación en la tabla 'notificaciones'
        // Incluye el ID del usuario, el ID de la empresa, el título y el mensaje
        $notificacion_query = "INSERT INTO notificaciones (id_usuario, id_empresa, titulo, mensaje) 
                               VALUES ($id_usuario, $id_empresa, '$titulo', '$mensaje')";
        // Ejecuta la consulta para insertar la notificación
        mysqli_query($conexion, $notificacion_query);

        // --- Comienza el proceso de envío de correo electrónico al usuario ---

        // Crea una nueva instancia de PHPMailer (con manejo de excepciones)
        $mail = new PHPMailer(true);
        try {
            // Configura PHPMailer para usar el servidor SMTP de Gmail
            $mail->isSMTP(); // Indica que se usará SMTP
            $mail->Host = 'smtp.gmail.com'; // Define el host SMTP de Gmail
            $mail->SMTPAuth = true; // Habilita la autenticación SMTP
            $mail->Username = 's.empresa24@gmail.com'; // Usuario (correo remitente)
            $mail->Password = 'pufmdvvyblwtrcqf'; // Contraseña de aplicación de Google (NO es la contraseña normal de Gmail)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Usa cifrado SSL
            $mail->Port = 465; // Puerto para SMTP seguro de Gmail

            // Establece el remitente del correo y el nombre que verá el destinatario
            $mail->setFrom('s.empresa24@gmail.com', 'SERVI EMPRESA');
            // Añade el destinatario usando el correo y nombre obtenidos de la base de datos
            $mail->addAddress($empresa['correo_elec'], $empresa['nombre_c']);

            // Indica que el correo será en formato HTML
            $mail->isHTML(true);
            // Establece el asunto del correo
            $mail->Subject = '¡Tu empresa ha sido aceptada!';
            // Define el contenido/cuerpo del correo, usando el nombre del representante y de la empresa, además de un botón de acceso
            $mail->Body = "
                <h2>Felicidades, {$empresa['nombre_representante']}!</h2>
                <p>
                    Tu empresa <strong>{$empresa['nombre_legal']}</strong> ha sido aceptada por nuestro equipo.
                </p>
                <p>
                    Accede a tu cuenta para editar y publicar tu empresa en Servi Empresa.
                </p>
                <p>
                    <a href='http://localhost/Proyecto-Terminar/Proyectoinicio//mis_empresas.php?id_empresa=$id_empresa' style='background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Acceder a Mis Empresas</a>
                </p>
                <p>Gracias,<br>Equipo de SERVI EMPRESA</p>";

            // Envía el correo. Si hay algún problema, saltará a la sección catch
            $mail->send();

            // Si el correo fue enviado correctamente, muestra una alerta en el navegador y redirige a la página de gestión de empresas
            echo '<script>
                alert("Empresa aceptada y el usuario ha sido notificado.");
                window.location = "gestionar_empresas.php";
            </script>';
        } catch (Exception $e) {
            // Si ocurre un error al enviar el correo, muestra una alerta con el error y redirige igual
            echo '<script>
                alert("Error al enviar el correo: ' . $mail->ErrorInfo . '");
                window.location = "gestionar_empresas.php";
            </script>';
        }
    }
}
// Fin del script PHP
?>