<?php 
session_start();


if(isset($_SESSION['usuario'])){

header("location: bienbenido.php");


}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>inicio</title>
    <link rel="stylesheet" href="inicio.css">
    <link rel="icon" href="4-icono.ico">
</head>
<body>

<header id="header">
<div class="container_header">
    <div class="logo">
        <img src="4-icono.ico" alt="">
    </div>
<div class="container_nav">
    <nav id="nav">
        <ul>
            <li><a href="index.html" class="select">Inicio</a></li>
            <li><a href="registro.php">Registrarse</a></li>
            <li><a href="#">Soporte</a></li>
        </ul>
    </nav>
    <div class="btn_menu" id="btn_menu"><i class="fa-solid fa-bars"></i></div>
</div>
</div>

</header>
<div class="container_all" id="container_all">








    <div class="container">

        <div class="container-form">
            <form action="login_usuario_be.php" method="POST" class="sign-in">
               <h2>Iniciar sesion</h2>
               <div class="social-networks">
                <ion-icon name="logo-whatsapp"></ion-icon>
                <ion-icon name="logo-twitter"></ion-icon>
                <ion-icon name="logo-youtube"></ion-icon>
                <ion-icon name="logo-tiktok"></ion-icon>
               </div>

               <span>CORREO Y CONTRASEÑA</span>
               <div class="container-input">
                <ion-icon name="mail-outline"></ion-icon>
                <input type="text" placeholder="Email" name="correo_elec" required>
               </div>

               <div class="container-input">
                <ion-icon name="lock-closed-outline"></ion-icon>
             <input type="password" placeholder="Password" name="contrasena" required>
               </div>

               <a href="recuperar.php">¿Olvidaste tu contraseña?</a>
            <button class="botton">INICIAR SESION</button>


            </div>
            
            </form>
        </div>
        <div>

        </div>
    </div>
    
    </div>


    <script src="login.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>