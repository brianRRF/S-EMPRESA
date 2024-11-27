
<head>
<body>
        
</body>
</head>



<?php


// hacer que entre a la libreria que descargue 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// que me conecte a mi base de dotos 
include 'conexion_be.php';

// sacar el correo de mi base de datos 
$correo_elec = $_POST['correo_elec'];

// verificar si el correo está en la base de datos
$result = mysqli_query($conexion, "SELECT * FROM usuario WHERE correo_elec='$correo_elec'");
if (mysqli_num_rows($result) == 0) {
    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    Swal.fire({
        title: "¡Usuario o correo no registrado!",
        text: "Por favor, verifica tus datos e inténtalo de nuevo.",
        icon: "error",
        confirmButtonText: "Ok"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = "registro.php";
        }
    });
    </script>
    ';
    exit();
}



// generar un código de verificación de 6 dígitos aleatorios
$codigo = rand(100000, 999999);
$fecha_pedido = date('Y-m-d H:i:s');
$fecha_caducidad = date('Y-m-d H:i:s', strtotime('+15 minutes'));

// que me inserte todo el contenido a mi base de datos nueva 
mysqli_query($conexion, "INSERT INTO codigos_verificacion (correo_elec, codigo, fecha_pedido, fecha_caducidad) VALUES ('$correo_elec', '$codigo', '$fecha_pedido', '$fecha_caducidad')");

// enviar el correo usando la libreria PHPMailer que descargue
$mail = new PHPMailer(true);
try {
    // configurar el servidor el servidor de el correo elec
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 's.empresa24@gmail.com';
    $mail->Password = 'pufmdvvyblwtrcqf';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // destinatarios
    $mail->setFrom('s.empresa24@gmail.com', 'SERVI EMPRESA');
    $mail->addAddress($correo_elec);

    // contenido
    $mail->isHTML(true);
    $mail->Subject = 'TU CODIGO DE VERIFICACION';
    $mail->Body    = 'TU CODIGO ES: ' . $codigo;

    $mail->send();

    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    Swal.fire({
        title: "¡Código enviado correctamente!",
        text: "Por favor, espera unos segundos a la entrega.",
        icon: "success",
        showConfirmButton: false,
        timer: 3000 // Espera 3 segundos antes de redirigir
    }).then(() => {
        window.location = "form.php?correo_elec=' . $correo_elec . '";
    });
    </script>
';

exit();
} catch (Exception $e) {
    echo "Error al enviar el correo: {$mail->ErrorInfo}";
}
?>
