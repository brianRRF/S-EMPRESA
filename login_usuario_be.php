<body>
    <head>
        
    </head>
</body>
<?php
// Inicia una nueva sesión o reanuda la existente
session_start();

// Incluye el archivo de conexión a la base de datos
include 'conexion_be.php';

// Obtiene el correo enviado desde el formulario POST
$correo = $_POST['correo_elec'];

// Obtiene la contraseña enviada desde el formulario POST
$contrasena = $_POST['contrasena'];

// Encripta la contraseña usando el algoritmo SHA-512
$contrasena = hash('sha512', $contrasena);

// Consulta a la base de datos para buscar un usuario con ese correo y contraseña
$validar_login = mysqli_query($conexion, "SELECT * FROM usuario WHERE correo_elec='$correo' and contrasena='$contrasena'");

// Si se encontró al menos un resultado (usuario válido)
if (mysqli_num_rows($validar_login) > 0) {
    // Obtiene los datos del usuario como un array asociativo
    $row = mysqli_fetch_assoc($validar_login);

    // Guarda el ID del usuario en la sesión
    $_SESSION['id'] = $row['id']; // Aquí se almacena el ID del usuario

    // Guarda el correo electrónico en la sesión
    $_SESSION['usuario'] = $correo;

    // Guarda el nombre de usuario en la sesión
    $_SESSION['nombre_usuario'] = $row['nombre_c'];

    // Guarda el ID del cargo (rol del usuario) en la sesión
    $_SESSION['id_cargo'] = $row['id_cargo'];

    // Crea cookies para mantener la sesión incluso después de cerrar el navegador (duran 1 año)
    setcookie('id', $row['id'], time() + (365 * 24 * 60 * 60), "/"); // 1 año
    setcookie('usuario', $correo, time() + (365 * 24 * 60 * 60), "/"); // 1 año
    setcookie('nombre_usuario', $row['nombre_c'], time() + (365 * 24 * 60 * 60), "/");
    setcookie('id_cargo', $row['id_cargo'], time() + (365 * 24 * 60 * 60), "/");

    // Redirige al usuario según su rol (id_cargo)
    switch ($row['id_cargo']) {
        case NULL: // Usuario común sin cargo asignado
            header("location: casa.php"); // Redirige a tu página de bienvenida
            break;

        case 1: // Servidor
            header("location: usuarios.php");
            break;

        case 2: // Administrador
            header("location: secciones.php");
            break;

        default: // Si el rol es desconocido o no válido
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
            Swal.fire({
                title: "¡Error!",
                text: "No tienes un rol asignado válido.",
                icon: "error",
                confirmButtonText: "Ok"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = "inicio.php";
                }
            });
            </script>';
            break;
    }

    // Finaliza la ejecución del script
    exit();

} else {
    // Si no se encontró ningún usuario con ese correo y contraseña
    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    Swal.fire({
       title: "Datos incorrectos",
       text: "Los datos introducidos son incorrectos, intenta de nuevo.",
       icon: "error",
       confirmButtonText: "Ok"
    }).then((result) => {
       if (result.isConfirmed) {
          window.location = "inicio.php";
       }
    });
    </script>
    ';
    // Finaliza la ejecución del script
    exit();
}
?>