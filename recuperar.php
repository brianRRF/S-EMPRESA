<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700&display=swap" rel="stylesheet">
    <link href="registro.css" rel="stylesheet">

    <title>OLVIDE MI CONTRASEÑA</title>
    <link rel="icon" href="4-icono.ico">
  </head>
  <body>
    <section class="contact-box">
        <div class="row no-gutters bg-light">
            <div class="col-xl-5 col-lg-12 register-bg bg-light">
               <div class="position-absolute testiomonial p-4">
                   <h2 class="font-weight-bold bg-servi">SERVIEMPRESA</h2>
                   <p class="lead text-light">El mejor servicio para tu empresa</p>
               </div>
            </div>
            <div class="col-xl-7 col-lg-12 d-flex">
               <div class="container align-self-center p-6">
                   <h1 class="font-weight-bold mb-3 text-dark">¿OLVIDASTE TU CONTRASEÑA?</h1> 
                   <p class="text-muted mb-5 text-dark">ENVIARÁS UN CÓDIGO DE VERIFICACIÓN PARA RECUPERAR TU CONTRASEÑA</p>
                   <form id="recuperarForm" method="POST" action="procesar_recuperacion.php">
                       <div class="form-group mb-3 text-dark">
                           <label class="font-weight-bold">CORREO ELECTRÓNICO<span class="text-danger">*</span></label>
                           <input type="email" name="correo_elec" class="form-control" placeholder="INGRESA TU CORREO PARA ENVÍO DE CÓDIGO" required>
                       </div>
                       <div class="text-center mt-4">
                           <button type="submit" class="btn btn-primary width-100">ENVIAR CÓDIGO</button>
                       </div>
                   </form>
                   <small class="d-inline-block text-muted mt-5">SI YA ERES MIEMBRO DE S-EMPRESA, INICIA SESIÓN. Y SI NO <a href="registro.php">REGÍSTRATE</a></small>
               </div>
            </div>
        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  </body>
</html>