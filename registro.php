<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link rel="icon" href="4-icono.ico">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet">
    <link href="registro.css" rel="stylesheet">

    <title>S-EMPRESA</title>
    <style>
        input.text-uppercase {
            text-transform: uppercase;
        }
    </style>
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
                    <h1 class="font-weight-bold mb-3 text-dark">Crea tu cuenta gratis</h1>
                    <p class="text-muted mb-5 text-dark">Ingresa la siguiente información para registrarte.</p>

                    <form action="servidor.php" method="POST" id="registerForm">
                        <div class="form-row mb-2">
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold text-dark">Nombre <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" class="form-control text-uppercase" placeholder="Tu nombre" required 
                                    pattern="^[A-Za-zÁáÉéÍíÓóÚúÜüÑñ ]+$" 
                                    oninput="this.value = this.value.replace(/[^A-Za-zÁáÉéÍíÓóÚúÜüÑñ ]/g, '').toUpperCase()">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold text-dark">Apellido <span class="text-danger">*</span></label>
                                <input type="text" name="apellido" class="form-control text-uppercase" placeholder="Tu apellido" required 
                                    pattern="^[A-Za-zÁáÉéÍíÓóÚúÜüÑñ ]+$" 
                                    oninput="this.value = this.value.replace(/[^A-Za-zÁáÉéÍíÓóÚúÜüÑñ ]/g, '').toUpperCase()">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold text-dark">Nombre de usuario <span class="text-danger">*</span></label>
                                <input type="text" name="nombre_c" id="nombreUsuario" class="form-control" placeholder="Tu nombre de usuario" required maxlength="16"
                                    oninput="this.value = this.value.replace(/[^a-zA-Z0-9_]/g, '')">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-dark">Correo electrónico <span class="text-danger">*</span></label>
                            <input type="email" name="correo_elec" class="form-control" placeholder="Ingresa tu correo electrónico" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-dark">Contraseña <span class="text-danger">*</span></label>
                            <input type="password" name="contrasena" class="form-control" placeholder="Ingresa una contraseña" required>
                        </div>
                        <div class="form-group mb-5">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="termsCheck">
                                <label class="form-check-label text-dark">
                                    Al seleccionar esta casilla aceptas nuestro aviso de privacidad y los términos y condiciones
                                </label>
                            </div>
                        </div>
                        <button id="registerBtn" class="btn btn-primary width-100" disabled>Regístrate</button>
                    </form>
                    <small class="d-inline-block text-muted mt-5">Todos los derechos reservados A | S-EMPRESA</small>
                </div>
           </div>
       </div>
   </section>

    <!-- Scripts de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <!-- Validación personalizada -->
    <script>
        const termsCheck = document.getElementById('termsCheck');
        const registerBtn = document.getElementById('registerBtn');
        const nombreUsuario = document.getElementById('nombreUsuario');

        function validarFormulario() {
            const valorUsuario = nombreUsuario.value;
            const regexValido = /^[a-zA-Z0-9_]{4,16}$/; // letras, números y guión bajo, entre 4 y 16 caracteres
            const termsAceptados = termsCheck.checked;

            registerBtn.disabled = !(regexValido.test(valorUsuario) && termsAceptados);
        }

        termsCheck.addEventListener('change', validarFormulario);
        nombreUsuario.addEventListener('input', validarFormulario);
    </script>

  </body>
</html>
