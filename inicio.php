<?php 
// Inicia la sesión PHP para poder utilizar variables de sesión
session_start();

// --- Restaurar sesión desde cookies (si el usuario no está autenticado) ---
if (!isset($_SESSION['usuario']) && isset($_COOKIE['usuario'])) {
    // Si no existe la variable de sesión 'usuario' pero sí la cookie 'usuario',
    // se restauran los datos de sesión con los valores guardados en las cookies.
    $_SESSION['usuario'] = $_COOKIE['usuario'];
    $_SESSION['nombre_usuario'] = $_COOKIE['nombre_usuario'];
    $_SESSION['id_cargo'] = $_COOKIE['id_cargo'];
}

// --- Si ya hay una sesión activa, redirige según el rol del usuario ---
if (isset($_SESSION['usuario'])) { // Si existe la variable de sesión 'usuario'
    switch ($_SESSION['id_cargo']) { // Según el valor del rol id_cargo
        case NULL: // Si el id_cargo es NULL, se asume que es un usuario normal
            header("Location: casa.php"); // Redirige a la página principal del usuario normal
            break;
        case 1: // Si el id_cargo es 1, es un usuario tipo "Servidor"
            header("Location: usuarios.php"); // Redirige a la página de usuarios
            break;
        case 2: // Si el id_cargo es 2, es un Administrador
            header("Location: secciones.php"); // Redirige a la página de administración/secciones
            break;
        default: // Si el rol no está definido en los casos anteriores
            header("Location: inicio.php"); // Redirige a la página de inicio por defecto
            break;
    }
    // Termina el script después de la redirección para evitar que se siga ejecutando código
    exit();
}
?>





<!doctype html>
<html lang="en">
  <head>
   
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700&display=swap" rel="stylesheet">
    
    <link href="registro.css" rel="stylesheet">

    <title>INICIAR SESION</title>

    <link rel="icon" href="4-icono.ico">
  </head>
  <body>

    <section class="contact-box">
        <div class="row no-gutters bg-light">
            <div class="col-xl-5 col-lg-12 register-bg bg-light">
              
             <div class="position-absolute testiomonial p-4">
                 <h2 class="font-weight-bold bg-servi">SERVIEMPRESA</h2>
                 <p class="lead text-light">El mejor servicio para tu empresa
                </p>
             </div>
            
            </div>
            <div class="col-xl-7 col-lg-12 d-flex">
             
             <div class="container align-self-center p-6">
                 <h1 class="font-weight-bold mb-3 text-dark">INICIA SESION</h1> 
                 
                     <p class="text-muted mb-5 text-dark">NO ESPACIOS Y LLENA TODOS LOS CAMPOS</p>
 
                     <form action="login_usuario_be.php" method="POST">
                         
                         <div class="form-group mb-3 text-dark">
                             <label class="font-weight-bold">CORREO ELECTRONICO<span class="text-danger">*</span></label>
                             <input type="email" name="correo_elec" class="form-control" placeholder="INGRESA TU CORREO" required>
                         </div>
                         <div class="form-group mb-3 text-dark">
                             <label class="font-weight-bold">CONTRASEÑA<span class="text-danger">*</span></label>
                             <input type="password" name="contrasena" id="password" class="form-control" placeholder="INGRESA TU CONTRASEÑA" oninput="validatePasswordInput(this)" required>
                         </div>
                        <a href="recuperar.php">¿Olvidaste tu contraseña?</a>
                         <div class="text-center mt-4">
                          <button class="btn btn-primary width-100">INICIAR SESION</button>
                          <button type="button" onclick="window.location='login_facial.html'">Iniciar sesión por reconocimiento facial</button>
                      </div>
                     </form>
                     <small class="d-inline-block text-muted mt-5">SI YA ERES MIEMBRO DE S-EMPRESA, INICIA SESION. Y SI NO <a href="registro.php">REGISTARTE</a></small>
                 </div>
            </div>
        </div>
    </section>
 
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
  </body>
</html>
