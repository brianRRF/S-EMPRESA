<head>
    <body>
        
    </body>
</head>
<?php
require 'conexion_be.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo_ingresado = $_POST['verification_code'] ?? ''; // Obtener el código de verificación enviado

    // Verificar que el código no esté vacío
    if (empty($codigo_ingresado)) {
        echo "Por favor ingresa el código de verificación.";
        exit();
    }

    // Verificar el código
    $sql = "SELECT * FROM usuarios_temporales WHERE codigo_verificacion='$codigo_ingresado'";
    $result = mysqli_query($conexion, $sql);

    if (mysqli_num_rows($result) > 0) {
        $usuario = mysqli_fetch_assoc($result);

        // Insertar en la tabla principal
        $sql = "INSERT INTO usuario (nombre, apellido, nombre_c, correo_elec, contrasena, foto_perfil, telefono, edad, fecha_nacimiento, sexo) 
                VALUES ('{$usuario['nombre']}', '{$usuario['apellido']}', '{$usuario['nombre_c']}', '{$usuario['correo_elec']}', '{$usuario['contrasena']}', '{$usuario['foto_perfil']}', '{$usuario['telefono']}', '{$usuario['edad']}', '{$usuario['fecha_nacimiento']}', '{$usuario['sexo']}')";
        mysqli_query($conexion, $sql);

        // Eliminar de la tabla temporal
        $sql = "DELETE FROM usuarios_temporales WHERE codigo_verificacion='$codigo_ingresado'";
        mysqli_query($conexion, $sql);

        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
            Swal.fire({
                title: "¡Registrado!",
                text: "Se ha enviado un código de verificación a tu correo.",
                icon: "success",
                confirmButtonText: "Ok"
            }).then(() => { window.location = "inicio.php" });
            </script>';
    } else {
        echo "Código incorrecto. Inténtalo de nuevo.";
    }
}
?>

<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet">
    <link href="registro.css" rel="stylesheet">
    <title>S-EMPRESA</title>
    <link rel="icon" href="4-icono.ico">
  </head>
  <body>
   <section class="contact-box">
       <div class="row no-gutters bg-light">
           <div class="col-xl-5 col-lg-12 register-bg bg-light">
            <div class="position-absolute testiomonial p-4">
                <h3 class="font-weight-bold text-light">S-EMPRESA</h3>
                <p class="lead text-light">El mejor servicio</p>
            </div>
           </div>
           <div class="col-xl-7 col-lg-12 d-flex">
                <div class="container align-self-center p-6">
                    <h1 class="font-weight-bold mb-3 text-dark">REGISTRANDO...</h1>
                    <p class="text-muted mb-5 text-dark">Ingresa el código</p>

                    <form method="post">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-dark">Código de verificación<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="verification_code" placeholder="Ingresa tu código de verificación" required>
                        </div>
                       
                        <button class="btn btn-primary width-100">VERIFICAR</button>
                    </form>
                    <small class="d-inline-block text-muted mt-5">Todos los derechos reservados A | S-EMPRESA</small>
                </div>
           </div>
       </div>
   </section>
  </body>
</html>
