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


$nombre_c = $_SESSION['nombre_usuario'];


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S-EMPRESA</title>
    <link rel="stylesheet" href="elperron.css">
    <link rel="icon" href="4-icono.ico">
</head>
<body>

<div class="barra-lateral">
    <div class="nombre-pag">
        <img src="4-icono.ico" id="cloud">
        <span>S-EMPRESA</span>
    </div>
    <button class="boton">
        <ion-icon name="add-outline"></ion-icon>
        <span>Nuevo espacio</span>
    </button>
    <nav class="navegacion">
       <ul>
        <li><a href="#"><ion-icon name="file-tray-outline"></ion-icon>
        <span>Buzon</span></a></li>
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
    <img src="R.png" alt="">
    <div class="info-usuario">
        <div class="nombre-imail">
            <span class="nombre">brayan</span>
            <span class="imail">dfnjhgdissijhi.com</span>
        </div>
        <ion-icon name="ellipsis-vertical-outline"></ion-icon>
    </div>
</div>

</div>




<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="script.js"></script>
</body>
</html>