<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700&display=swap" rel="stylesheet">
    <link href="registro.css" rel="stylesheet">

    <title>VERIFICAR CÓDIGO</title>
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
                    <h1 class="font-weight-bold mb-3 text-dark">VERIFICANDO</h1> 
                    <p class="text-muted mb-5 text-dark">CÓDIGO DE VERIFICACIÓN</p>

                    <form id="verificarForm">
                        <!-- Campo para el código de verificación -->
                        <div id="codigoField" class="form-group col-md-6 text-dark">
                            <label class="font-weight-bold">CÓDIGO<span class="text-danger">*</span></label>
                            <input type="text" name="codigo" id="codigo" class="form-control" placeholder="INGRESA TU CÓDIGO" required>
                        </div>
                        
                        <!-- Campos de nueva contraseña (ocultos inicialmente) -->
                        <div id="passwordFields" class="form-group mb-3 text-dark d-none">
                            <label class="font-weight-bold">NUEVA CONTRASEÑA<span class="text-danger">*</span></label>
                            <input type="password" name="nueva_contrasena" id="nueva_contrasena" class="form-control" placeholder="INGRESA TU NUEVA CONTRASEÑA">
                        </div>
                        <div id="confirmPasswordFields" class="form-group mb-3 text-dark d-none">
                            <label class="font-weight-bold">CONFIRMAR CONTRASEÑA<span class="text-danger">*</span></label>
                            <input type="password" name="confirmar_contrasena" id="confirmar_contrasena" class="form-control" placeholder="CONFIRMA TU NUEVA CONTRASEÑA">
                        </div>
                        
                        <!-- Botones -->
                        <div class="text-center mt-4">
                            <button type="button" id="verifyButton" class="btn btn-primary width-100">VERIFICAR CÓDIGO</button>
                            <button type="submit" id="changePasswordButton" class="btn btn-primary width-100 d-none">CAMBIAR CONTRASEÑA</button>
                        </div>
                    </form>

                    <small class="d-inline-block text-muted mt-5">S-EMPRESA</small>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.getElementById('verifyButton').addEventListener('click', function () {
            const codigo = document.getElementById('codigo').value;

            // Enviar el código al servidor para su validación
            fetch('procesar_codigo.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ codigo })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Ocultar el campo del código y el botón "Verificar Código"
                    document.getElementById('codigoField').classList.add('d-none');
                    this.classList.add('d-none');

                    // Mostrar los campos para la nueva contraseña y el botón "Cambiar Contraseña"
                    document.getElementById('passwordFields').classList.remove('d-none');
                    document.getElementById('confirmPasswordFields').classList.remove('d-none');
                    document.getElementById('changePasswordButton').classList.remove('d-none');
                } else {
                    alert(data.message || "El código ingresado es incorrecto.");
                }
            })
            .catch(error => {
                console.error('Error al validar el código:', error);
                alert("Hubo un error al procesar la solicitud. Inténtelo de nuevo.");
            });
        });

        document.getElementById('verificarForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const codigo = document.getElementById('codigo').value;
            const nuevaContrasena = document.getElementById('nueva_contrasena').value;
            const confirmarContrasena = document.getElementById('confirmar_contrasena').value;

            // Validar y enviar la nueva contraseña al servidor
            fetch('procesar_codigo.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ codigo, nueva_contrasena: nuevaContrasena, confirmar_contrasena: confirmarContrasena })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message || "Contraseña actualizada correctamente.");
                    window.location = "index.html"; // Redirige al inicio de sesión
                } else {
                    alert(data.message || "Hubo un error al cambiar la contraseña.");
                }
            })
            .catch(error => {
                console.error('Error al cambiar la contraseña:', error);
                alert("Hubo un error al procesar la solicitud. Inténtelo de nuevo.");
            });
        });
    </script>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  </body>
</html>