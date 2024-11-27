




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>recuperar</title>
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
            <li><a href="inicio.php">Atras</a></li>
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
            <form action="enviar.php" method="POST" class="sign-in">
               <h2>Recuperar contraseña</h2>
               <div class="social-networks">
                <ion-icon name="logo-whatsapp"></ion-icon>
                <ion-icon name="logo-twitter"></ion-icon>
                <ion-icon name="logo-youtube"></ion-icon>
                <ion-icon name="logo-tiktok"></ion-icon>
               </div>

               <span>correo para enviar codigo de Verificación</span>
               <div class="container-input">
                <ion-icon name="mail-outline"></ion-icon>
                <input type="text" placeholder="Email" name="correo_elec" required>
               </div>


            <button class="botton">ENVIAR</button>


            </div>
            
            </form>
        </div>
        <div>

        </div>
    </div>
    



    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>