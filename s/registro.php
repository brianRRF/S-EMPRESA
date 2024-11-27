<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar</title>
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
            <li><a href="inicio.php">Iniciar sesion</a></li>
            <li><a href="#">Soporte</a></li>
        </ul>
    </nav>
    <div class="btn_menu" id="btn_menu"><i class="fa-solid fa-bars"></i></div>
</div>
</div>

</header>
<div class="container_all" id="container_all"></div>







    <div class="container">
        <div class="container-form">
            <form action="registro_usuario_be.php" method="POST" class="sing-up">
                <h2>Registrarse</h2>
                <div class="social-networks">
                    <ion-icon name="logo-whatsapp"></ion-icon>
                    <ion-icon name="logo-twitter"></ion-icon>
                    <ion-icon name="logo-youtube"></ion-icon>
                    <ion-icon name="logo-tiktok"></ion-icon>
                   </div>
                   <span>CORREO ELECTRONICO PARA REGISTRARSE</span>
                   <div class="container-input">
                    <ion-icon name="person-circle-outline"></ion-icon>
                    <input type="text" placeholder="Nombre" name="nombre_c" required>
                   </div>

                   <div class="container-input">
                    <ion-icon name="mail-outline"></ion-icon>
                    <input type="text" placeholder="Email" name="correo_elec" required>
                   </div>
    
                   <div class="container-input">
                    <ion-icon name="lock-closed-outline"></ion-icon>
                 <input type="password" placeholder="Password" name="contrasena" required>
                   </div>
                   
                <button class="botton">REGISTRARSE</button>
            </form>

        </div>
    </div>
    <script src="login.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>
</html>