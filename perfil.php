<?php
session_start();
include 'conexion_be.php';

if (!isset($_SESSION['usuario'])) {
    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    Swal.fire({ title: "¡INICIA SESIÓN!",
    text: "Inicia sesión o regístrate si aún no lo has hecho.",
    icon: "error",
    confirmButtonText: "Ok" }).then((result) => {
        if (result.isConfirmed) {
            window.location = "inicio.php";
        }
    });
    </script> ';
    session_destroy();
    die();
}

$correo_elec = $_SESSION['usuario'];
$nombre_c = $_SESSION['nombre_usuario'];

// Consulta a la base de datos para obtener la URL de la foto de perfil y otros datos
$query = "SELECT foto_perfil, telefono, sexo, edad, fecha_nacimiento FROM usuario WHERE correo_elec='$correo_elec'";
$resultado = mysqli_query($conexion, $query);

if (!$resultado || mysqli_num_rows($resultado) == 0) {
    echo '<script>
    Swal.fire({ title: "¡Error!",
    text: "No se encontró el usuario en la base de datos.",
    icon: "error",
    confirmButtonText: "Ok" });
    </script>';
    exit();
}

$row = mysqli_fetch_assoc($resultado);

// Obtener los datos del usuario
$foto_perfil = $row['foto_perfil'] ?? 'https://i.ibb.co/Wpcn6Dq9/R.png'; // Imagen predeterminada en ImgBB
$telefono = $row['telefono'] ?? 'NO DEFINIDO';
$sexo = $row['sexo'] ?? 'NO DEFINIDO';
$edad = $row['edad'] ?? 'NO DEFINIDO';
$fecha_nacimiento = $row['fecha_nacimiento'] ?? 'NO DEFINIDO';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil</title>
  <link rel="icon" href="4-icono.ico">
  <link rel="stylesheet" href="elperron.css">
  <link rel="stylesheet" href="perfil.css">
</head>
<body>
<span class="main_bg"></span>
<div class="container">
    <header class="header">
        <div class="brandLogo">
            <figure><img src="4-icono.ico" alt="logo" width="40px" height="40px"></figure>
            <span>S-EMPRESA</span>
        </div>
    </header>
    <section class="userProfile card">
        <div class="profile">
            <figure><img src="<?php echo $foto_perfil; ?>" alt="profile" width="250px" height="250px"></figure>
        </div>
    </section>
    <section class="work_skills card">
        <div class="work">
            <h1 class="heading">Trabajo</h1>
            <div class="primary">
                <h1>Nuevos trabajos</h1>
                <span>Localización</span>
                <p>No definida</p>
            </div>
            <div class="secundary">
                <h1>País <br> Municipio</h1>
                <span>Secundario</span>
                <p>No definido</p>
            </div>
        </div>
        <div class="skills">
            <h1 class="heading">Redes</h1>
            <ul>
                <li style="--i:0">Android</li>
                <li style="--i:1">Web designado</li>
                <li style="--i:2">Términos y condiciones</li>
                <li style="--i:3">Video</li>
            </ul>
        </div>
    </section>
    <section class="userDatails card">
        <div class="userName">
            <h1 class="name"><?php echo htmlspecialchars($nombre_c); ?></h1>
            <div class="map">
                <ion-icon name="locate-outline"></ion-icon>
                <span>No definido</span>
            </div>
            <p>Usuario</p>
        </div>
        <div class="rank">
            <h1 class="heading">Recomendado</h1>
            <span>8.6</span>
            <div class="rating">
                <ion-icon name="star-outline"></ion-icon>
                <ion-icon name="star-outline"></ion-icon>
                <ion-icon name="star-outline"></ion-icon>
                <ion-icon name="star-outline"></ion-icon>
                <ion-icon name="star-outline"></ion-icon>
                <ion-icon name="star-outline"></ion-icon>
            </div>
        </div>
        <div class="btns">
            <ul>
                <li class="sendmsg">
                <ion-icon name="close-outline"></ion-icon>
                    <a href="cerrar_sesion.php">Cerrar sesion</a>
                </li>
                <li class="sendmsg active">
                    <ion-icon name="checkmark-done-outline"></ion-icon>
                    <a href="">Contacto</a>
                </li>
                <li class="sendmsg">
                    <ion-icon name="alert-outline"></ion-icon>
                    <a href="cambiar_foto.php">Reportar usuario</a>
                </li>
            </ul>
        </div>
    </section>
    <section class="timeline_about card">
        <div class="tabs">
            <ul>
                <a href="perfilactua.php">
                <li class="timeline">
                    <ion-icon name="brush-outline"></ion-icon>
                    <span >Editar</span>
                </li>
                </a>
                <li class="about active">
                    <ion-icon name="body-outline"></ion-icon>
                    <span>Ver</span>
                </li>
            </ul>
        </div>
        <div class="contact_info">
            <h1 class="heading">Información de contacto</h1>
            <ul>
                <li class="phone">
                    <h1 class="label">Teléfono: </h1>
                    <span class="info"><?php echo htmlspecialchars($telefono); ?></span>
                </li>
                <li class="address">
                    <h1 class="label">Edad: </h1>
                    <span class="info"><?php echo htmlspecialchars($edad); ?></span>
                </li>
                <li class="email">
                    <h1 class="label">E-mail: </h1>
                    <span class="info"><?php echo htmlspecialchars($correo_elec); ?></span>
                </li>
                <li class="site">
                    <h1 class="label">Sitio: </h1>
                    <span class="info"></span>
                </li>
            </ul>
        </div>
        <div class="basic_info">
            <ul>
                <li class="birthday">
                    <h1 class="label">Fecha de nacimiento:</h1>
                    <span class="info"><?php echo htmlspecialchars($fecha_nacimiento); ?></span>
                </li>
                <li class="sexo">
                    <h1 class="label">Sexo:</h1>
                    <span class="info"><?php echo htmlspecialchars($sexo); ?></span>
                </li>
            </ul>
        </div>
    </section>
</div>


<script src="redirigir.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>