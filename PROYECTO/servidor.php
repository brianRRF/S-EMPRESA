<html>
    <head>
        
    </head>
</html>
<?php
// Incluye el archivo con la configuración y conexión a la base de datos
include 'conexion_be.php';

// Usa las clases PHPMailer necesarias para enviar correos
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Carga automática de clases mediante Composer
require 'vendor/autoload.php';

// Verifica si el método de solicitud es POST (envío de formulario)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Obtiene el valor del campo 'nombre', elimina espacios y lo escapa para seguridad
    $nombre = mysqli_real_escape_string($conexion, trim($_POST['nombre'] ?? ''));

    // Obtiene y sanitiza el campo 'apellido'
    $apellido = mysqli_real_escape_string($conexion, trim($_POST['apellido'] ?? ''));

    // Obtiene y sanitiza el campo 'nombre_c' (nombre de cuenta / usuario)
    $nombre_c = mysqli_real_escape_string($conexion, trim($_POST['nombre_c'] ?? ''));

    // Obtiene y sanitiza el correo electrónico
    $correo_elec = mysqli_real_escape_string($conexion, trim($_POST['correo_elec'] ?? ''));

    // Obtiene la contraseña tal cual del formulario (la encriptación es después)
    $contrasena = trim($_POST['contrasena'] ?? '');

    // Obtiene y sanitiza el número de teléfono, o asigna "NO DEFINIDO" si no se envió
    $telefono = mysqli_real_escape_string($conexion, trim($_POST['telefono'] ?? 'NO DEFINIDO'));

    // Obtiene y sanitiza la edad, o asigna "NO DEFINIDO" si no se envió
    $edad = mysqli_real_escape_string($conexion, trim($_POST['edad'] ?? 'NO DEFINIDO'));

    // Obtiene y sanitiza el sexo, o asigna "NO DEFINIDO" si no se envió
    $sexo = mysqli_real_escape_string($conexion, trim($_POST['sexo'] ?? 'NO DEFINIDO'));

    // Obtiene y sanitiza la fecha de nacimiento, o asigna "NO DEFINIDO" si no se envió
    $fecha_nacimiento = mysqli_real_escape_string($conexion, trim($_POST['fecha_nacimiento'] ?? 'NO DEFINIDO'));

    // Asigna una imagen de perfil por defecto
    $foto_perfil = 'https://i.ibb.co/Wpcn6Dq9/R.png';

    // Verifica si los campos obligatorios están vacíos
    if ($nombre === '' || $apellido === '' || $nombre_c === '' || $correo_elec === '' || $contrasena === '') {
        // Muestra un mensaje de error con SweetAlert2 si hay campos vacíos
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
        Swal.fire({
            title: "¡Error!",
            text: "Todos los campos son obligatorios.",
            icon: "error",
            confirmButtonText: "Ok"
        }).then(() => { window.location = "registro.html"; });
        </script>';
        // Termina la ejecución del script
        exit();
    }

    // Encripta la contraseña usando el algoritmo hash SHA-512
    $contrasena = hash('sha512', $contrasena);

    // Verifica si el correo ya existe en la tabla usuario
    $verifi_correo = mysqli_query($conexion, "SELECT * FROM usuario WHERE correo_elec='$correo_elec'");

    // Si el correo ya está registrado, muestra alerta y redirige
    if (mysqli_num_rows($verifi_correo) > 0) {
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
        Swal.fire({
            title: "¡Correo existente!",
            text: "Este correo ya está registrado. Usa otro correo o inicia sesión.",
            icon: "error",
            confirmButtonText: "Ok"
        }).then(() => { window.location = "index.html"; });
        </script>';
        exit();
    }

    // Verifica si el nombre y apellido ya están registrados
    $verifi_nombre = mysqli_query($conexion, "SELECT * FROM usuario WHERE nombre='$nombre' AND apellido='$apellido'");

    // Verifica si el nombre de cuenta ya está registrado
    $verifi_nombre_c = mysqli_query($conexion, "SELECT * FROM usuario WHERE nombre_c='$nombre_c'");

    // Si el nombre y apellido ya existen, muestra alerta
    if (mysqli_num_rows($verifi_nombre) > 0) {
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
        Swal.fire({
            title: "¡Nombre y Apellido en uso!",
            text: "El nombre y apellido ya están registrados. Usa otro nombre o inicia sesión.",
            icon: "error",
            confirmButtonText: "Ok"
        }).then(() => { window.location = "index.html"; });
        </script>';
        exit();
    }

    // Si el nombre de cuenta ya existe, muestra alerta
    if (mysqli_num_rows($verifi_nombre_c) > 0) {
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
        Swal.fire({
            title: "¡Nombre de usuario en uso!",
            text: "El nombre de usuario ya está registrado. Usa otro nombre de usuario o inicia sesión.",
            icon: "error",
            confirmButtonText: "Ok"
        }).then(() => { window.location = "index.html"; });
        </script>';
        exit();
    }

    // Genera un código de verificación de 6 dígitos aleatorio
    $codigo = rand(100000, 999999);

    // Obtiene la fecha y hora actual en formato 'Y-m-d H:i:s'
    $fecha_creacion = date('Y-m-d H:i:s');

    // Prepara la consulta SQL para insertar los datos en la tabla usuarios_temporales
    $query = "INSERT INTO usuarios_temporales 
              (nombre, apellido, nombre_c, correo_elec, contrasena, codigo_verificacion, fecha_creacion, foto_perfil, telefono, edad, fecha_nacimiento, sexo) 
              VALUES 
              ('$nombre', '$apellido', '$nombre_c', '$correo_elec', '$contrasena', '$codigo', '$fecha_creacion', '$foto_perfil', '$telefono', '$edad', '$fecha_nacimiento', '$sexo')";

    // Ejecuta la consulta y verifica si se insertó correctamente
    if (mysqli_query($conexion, $query)) {
        // Crea una nueva instancia de PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configura el uso de SMTP para enviar el correo
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Servidor SMTP de Gmail
            $mail->SMTPAuth = true; // Habilita autenticación SMTP
            $mail->Username = 's.empresa24@gmail.com'; // Correo remitente
            $mail->Password = 'pufmdvvyblwtrcqf'; // Contraseña de aplicación (no la contraseña personal)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Tipo de encriptación
            $mail->Port = 465; // Puerto para SMTPS

            // Configura el correo del remitente y el nombre
            $mail->setFrom('s.empresa24@gmail.com', 'SERVI EMPRESA');

            // Agrega el destinatario (correo y nombre)
            $mail->addAddress($correo_elec, $nombre_c);

            // Establece que el contenido será en formato HTML
            $mail->isHTML(true);
            $mail->Subject = 'CODIGO DE VERIFICACION'; // Asunto del correo

            // Contenido HTML del correo (cuerpo)
            $mail->Body = "HOLA, <strong>$nombre</strong>:
             <br> <br> Hemos recibido tu solicitud para registrarte con un codigo de un solo uso para activar tu cuenta de
             <br> <strong>S-EMPRESA</strong>
             <br> <br> TU CODIGO DE VERIFICACION ES: <h2>$codigo</h2>
             <br> Escriba este codigo en el apartado de verificacion de registro en el sitio web oficial.
             <br> No lo comparta con nadie.
             <br> <br> Muchas gracias.
             <br> EQUIPO DE LAS CUENTAS DE S-EMPRESA";

            // Envía el correo
            $mail->send();

            // Si el correo se envió correctamente, muestra un mensaje de éxito y redirige
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
            Swal.fire({
                title: "¡Registrado!",
                text: "Se ha enviado un código de verificación a tu correo.",
                icon: "success",
                confirmButtonText: "Ok"
            }).then(() => { window.location = "ingresar.php?correo=' . urlencode($correo_elec) . '"; });
            </script>';
            exit(); // Finaliza el script
        } catch (Exception $e) {
            // Si ocurre un error al enviar el correo, muestra un mensaje de error
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
            Swal.fire({
                title: "¡Error!",
                text: "Error al enviar el correo: ' . $mail->ErrorInfo . '",
                icon: "error",
                confirmButtonText: "Ok"
            }).then(() => { window.location = "index.html"; });
            </script>';
        }
    } else {
        // Si hubo un error al insertar los datos, muestra mensaje de error
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
        Swal.fire({
            title: "¡Error!",
            text: "Usuario no registrado correctamente, inténtalo de nuevo.",
            icon: "error",
            confirmButtonText: "Ok"
        }).then(() => { window.location = "index.html"; });
        </script>';
    }
}

// Cierra la conexión con la base de datos
mysqli_close($conexion);
?>

