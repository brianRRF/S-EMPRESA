<?php 



session_start();
include 'conexion_be.php';

if(!isset($_SESSION['usuario'])) {
    echo'
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <script>
      Swal.fire({ title: "¡INICIA SESION!",
       text: "Inicia sesion o registrate si aun no lo has echo.",
       icon: "error",
        confirmButtonText: "Ok" }).then((result) => 
        { if (result.isConfirmed)
          { window.location = "inicio.php";
            } });
              </script> ';
session_destroy();
die();

}

$correo_elec = $_SESSION['usuario'];
$nombre_c = $_SESSION['nombre_usuario'];

$query = "SELECT foto_perfil FROM usuario WHERE correo_elec='$correo_elec'";
$resultado = mysqli_query($conexion, $query);

$row = mysqli_fetch_assoc($resultado);
$foto_perfil = $row['foto_perfil'] ?? 'R.png';

// Verifica si el archivo de imagen existe usando la ruta relativa
if (!file_exists('C:/xampp/htdocs/ProyectoInicio/' . $foto_perfil)) {
    $foto_perfil = 'R.png'; // Asegúrate de que esta ruta relativa sea correcta
}



?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentos</title>
    <link rel="icon" href="4-icono.ico">
    <link rel="stylesheet" href="elperron.css">
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
        <li><a id="home" href="#"><ion-icon name="home-outline"></ion-icon>
        <span>Inicio</span></a></li>
        <li><a href="#"><ion-icon name="star-outline"></ion-icon>
        <span>Calificacion</span></a></li>
        <li><a href="#"><ion-icon name="business-outline"></ion-icon>
        <span>Trabajos</span></a></li>
        <li><a href="#"><ion-icon name="send-outline"></ion-icon>
        <span>Chat</span></a></li>
        <li><a href="#"><ion-icon name="newspaper-outline"></ion-icon>
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
            <span>obscuro</span>
        </div>
        <div class="switch">
            <div class="base">
                <div class="circulo">

                </div>
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
</div>
    


</main>


<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="bienvenido.js"></script>


</body>
</html>