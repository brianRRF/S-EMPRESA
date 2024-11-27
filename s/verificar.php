<head>
<body>
        
</body>
</head>




<?php
// que me conecte a mi base de datos 
include 'conexion_be.php';

// que me mustre los datos del formulario 
$correo_elec = $_POST['correo_elec'];
$codigo = $_POST['codigo'];
$nueva_contrasena = hash('sha512', $_POST['nueva_contrasena']);

// verificar si el código no se a caducado 
$result = mysqli_query($conexion, "SELECT * FROM codigos_verificacion WHERE correo_elec='$correo_elec' AND codigo='$codigo' AND usado=0 AND fecha_caducidad > NOW()");
if (mysqli_num_rows($result) == 0) {
    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    Swal.fire({
        title: "¡Código inválido o caducado!",
        text: "Intente de nuevo.",
        icon: "error",
        confirmButtonText: "Ok"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = "form.php";
        }
    });
    </script>
';
exit();

}

// marcar el código como que ya esta usado el puto
mysqli_query($conexion, "UPDATE codigos_verificacion SET usado=1 WHERE correo_elec='$correo_elec' AND codigo='$codigo'");

// para enviar el cmbio de la contraseaña 
mysqli_query($conexion, "UPDATE usuario SET contrasena='$nueva_contrasena' WHERE correo_elec='$correo_elec'");

echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    Swal.fire({
        title: "¡Contraseña cambiada correctamente!",
        text: "Tu contraseña ha sido actualizada con éxito.",
        icon: "success",
        confirmButtonText: "Ok"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = "inicio.php";
        }
    });
    </script>
';
exit();

?>
