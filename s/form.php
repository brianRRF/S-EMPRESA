<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
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
            <li><a href="#">Atras</a></li>
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
        <form action="verificar.php" method="POST" class="sign-in">
            <h2>Recuperar contraseña</h2>
            <div class="container-input">
                <label for="correo_elec">Correo Electrónico:</label>
                <input type="email" id="correo_elec" name="correo_elec" required>
            </div>
            <div class="container-input">
                <label for="codigo">Código de Verificación:</label>
                <input type="text" id="codigo" name="codigo" required>
            </div>
            <div class="container-input">
                <label for="nueva_contrasena">Nueva Contraseña:</label>
                <input type="password" id="nueva_contrasena" name="nueva_contrasena" required>
            </div>
            <button type="submit">Cambiar Contraseña</button>
        </form>
    </div>
</div>
</body>
</html>
