<?php 
session_start();
include 'conexion_be.php'; // Asegurar la conexión a la base de datos

// Verificar si la cookie 'usuario' está establecida y restaurar sesión si es necesario
if (isset($_COOKIE['usuario']) && !isset($_SESSION['usuario'])) {
    $_SESSION['usuario'] = $_COOKIE['usuario'];
    $_SESSION['nombre_usuario'] = $_COOKIE['nombre_usuario'];
    $_SESSION['id_cargo'] = $_COOKIE['id_cargo'];
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header("location: inicio.php");
    exit();
}

// Obtener datos del usuario
$correo_elec = $_SESSION['usuario'];
$nombre_c = $_SESSION['nombre_usuario'];

// Consultar la foto de perfil desde la base de datos
$query = "SELECT foto_perfil FROM usuario WHERE correo_elec='$correo_elec'";
$resultado = mysqli_query($conexion, $query);
$row = mysqli_fetch_assoc($resultado);
$foto_perfil = $row['foto_perfil'] ?? 'R.png';





// Verificar si la imagen existe en la ruta relativa
if (!file_exists("imagenes_perfil/" . $foto_perfil)) {
    $foto_perfil = 'R.png'; // Imagen predeterminada
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S-EMPRESA</title>
    <link rel="stylesheet" href="elperron.css">
    <link rel="icon" href="4-icono.ico">

    <script src="bienvenido.js"></script>
</head>
<body>


<div class="menu">
<ion-icon name="reorder-four-outline"></ion-icon>
<ion-icon name="close-outline"></ion-icon>
</div>
<div class="barra-lateral">
    <div>
    <div class="nombre-pag">
        <img src="4-icono.ico" id="cloud">
        <span>S-EMPRESA</span>
    </div>
    <button class="boton">
        <ion-icon name="add-outline"></ion-icon>
        <span>Nuevo espacio</span>
    </button>
    </div>
    <nav class="navegacion">
       <ul>
        <li><a id="home" href="bienbenido.php"><ion-icon name="home-outline"></ion-icon>
        <span>Inicio</span></a></li>
        <li><a href="#"><ion-icon name="star-outline"></ion-icon>
        <span>Calificacion</span></a></li>
        <li><a href="#"><ion-icon name="business-outline"></ion-icon>
        <span>Trabajos</span></a></li>
        <li><a href="#"><ion-icon name="send-outline"></ion-icon>
        <span>Chat</span></a></li>
        <li><a id="docu" href="documentos.php"><ion-icon name="newspaper-outline"></ion-icon>
        <span>Documentos</span></a></li>
        <li><a href="#"><ion-icon name="planet-outline"></ion-icon>
        <span>Espacios</span></a></li>
        <li><a href="#"><ion-icon name="trash-bin-outline"></ion-icon>
        <span>Eliminados</span></a></li>
       </ul> 
    </nav>

    <div>
    <div class="linea"></div>
    
   
    <div class="modo-obscuro">
    <div class="info">
        <ion-icon name="moon-outline"></ion-icon>
        <span>Obscuro</span>
    </div>
    <div class="switch">
        <div class="base">
            <div class="circulo"></div>
        </div>
    </div>
</div>



  




<div class="usuario">
    <img src="<?php echo $foto_perfil; ?>" alt="">
    <div class="info-usuario">
        <div class="nombre-imail">
            <span class="nombre"><?php echo htmlspecialchars($nombre_c);?></span>
            <span class="imail"><?php echo htmlspecialchars($correo_elec);?></span>
        </div>
        <a href="perfil.php"><ion-icon name="ellipsis-vertical-outline"></ion-icon></a>
    </div>
</div>
    </div>

</div>

<main>

<div class="titulocasa">
    <a href=""><ion-icon name="home-outline"></ion-icon>Inicio</a>

</main>


    
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>
</html>