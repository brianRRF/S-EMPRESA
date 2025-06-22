<?php
session_start();
include 'conexion_be.php';

if (!isset($_SESSION['usuario'])) {
    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    Swal.fire({ title: "¡INICIA SESIÓN!",
    text: "Inicia sesión o regístrate si aún no lo has hecho.",
    icon: "error",
    confirmButtonText: "Ok" }).then((result) => {
        if (result.isConfirmed) {
            window.location = "inicio.php";
        }
    });
    </script> ';
    session_destroy();
    die();
}

$correo_elec = $_SESSION['usuario'];
$nombre_c = $_SESSION['nombre_usuario'];

// Consulta a la base de datos para obtener la URL de la foto de perfil
$query = "SELECT foto_perfil FROM usuario WHERE correo_elec='$correo_elec'";
$resultado = mysqli_query($conexion, $query);

if (!$resultado || mysqli_num_rows($resultado) == 0) {
    echo 'No se encontró el usuario.';
    exit();
}

$row = mysqli_fetch_assoc($resultado);
$foto_perfil = $row['foto_perfil'] ?? 'R.png';

// Verifica si el archivo de imagen existe usando la ruta relativa
if (!file_exists('C:/xampp/htdocs/Proyecto-Terminar/ProyectoInicio/' . $foto_perfil)) {
    $foto_perfil = 'R.png'; // Asegúrate de que esta ruta relativa sea correcta
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil</title>
  <link rel="icon" href="4-icono.ico">
  <link rel="stylesheet" href="elperron.css">
  <link rel="stylesheet" href="perfil2.css">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>
<body>
<span class="main_bg"></span>
<div class="container">
    <header class="header">
        <div class="brandLogo">
            <figure><img src="4-icono.ico" alt="logo" width="40px" height="40px"></figure>
            <span>S-EMPRESA</span>
        </div>
    </header>
    <section class="userProfile card">
        <div class="profile">
            <figure><img src="<?php echo $foto_perfil; ?>" alt="profile" width="250px" height="250px">
        <div class="icon">
        <ion-icon name="brush-outline"></ion-icon>
        </div>
        </figure>
        </div>
    </section>
    <section class="work_skills card">
        <div class="work">
            <h1 class="heading">Trabajo</h1>
            <div class="primary">
                <h1>Nuevos trabajos</h1>
                <span>Localización</span>
                <p>No definida</p>
            </div>
            <div class="secundary">
                <h1>País
                </h1><p>No definido</p>
                <h1> Municipio</h1>
                <span>Secundario</span>
                <p>No definido</p>
            </div>
        </div>
        <div class="skills">
            <h1 class="heading">Redes</h1>
            <ul>
                <li style="--i:0">Android</li>
                <li style="--i:1">Web designado</li>
                <li style="--i:2">Términos y condiciones</li>
                <li style="--i:3">Video</li>
            </ul>
        </div>
    </section>
    <section class="userDatails card">
        <div class="userName">
            <h1 class="name"><?php echo htmlspecialchars($nombre_c); ?></h1>
            <div class="map">
                <ion-icon name="locate-outline"></ion-icon>
                <span>No definido</span>
            </div>
            <p>Usuario</p>
        </div>
        <div class="rank">
            <h1 class="heading">Recomendado</h1>
            <span>8.6</span>
            <div class="rating">
                <ion-icon name="star-outline"></ion-icon>
                <ion-icon name="star-outline"></ion-icon>
                <ion-icon name="star-outline"></ion-icon>
                <ion-icon name="star-outline"></ion-icon>
                <ion-icon name="star-outline"></ion-icon>
                <ion-icon name="star-outline"></ion-icon>
            </div>
        </div>
        <div class="btns">
            <ul>
                <li class="sendmsg">
                <ion-icon name="close-outline"></ion-icon>
                    <a href="cerrar_sesion.php">Cerrar sesion</a>
                </li>
                <li class="sendmsg active">
                    <ion-icon name="checkmark-done-outline"></ion-icon>
                    <a href="">Contacto</a>
                </li>
                <li class="sendmsg">
                    <ion-icon name="alert-outline"></ion-icon>
                    <a href="cambiar_foto.php">Reportar usuario</a>
                </li>
            </ul>
        </div>
    </section>
    <section class="timeline_about card">
        <div class="tabs">
            <ul>
                <a href="#">
                <li class="timeline active">
                    <ion-icon name="brush-outline"></ion-icon>
                    <span >Editar</span>
                </li>
                </a>
                <a href="perfil.php">
                <li class="about">
                    <ion-icon name="body-outline"></ion-icon>
                    <span>Ver</span>
                </li>
                </a>
            </ul>
        </div>
        <div class="contact_info">
            <h1 class="heading">Información de contacto</h1>
            <ul>
                <li class="phone">
                    <h1 class="label">Teléfono: </h1>
                    <button onclick="openPhonePopup()">Elegir Número de Teléfono</button>
                </li>
                <li class="address">
                    <h1 class="label">Edad: </h1>
                    <span class="info">No definido</span>
                </li>
                <li class="email">
                    <h1 class="label">E-mail: </h1>
                    <span class="info"><?php echo htmlspecialchars($correo_elec); ?></span>
                </li>
                <li class="site">
                    <h1 class="label">Sitio: </h1>
                    


                </li>
            </ul>
        </div>
        <div class="basic_info">
            <ul>
                <li class="birthday">
                    <h1 class="label">Fecha de nacimiento:</h1>
                    <button onclick="openPopup()">Seleccionar Fecha de Nacimiento</button>
                </li>
                <li class="sexo">
                    <h1 class="label">Sexo:</h1>
                    <span class="info">No definido</span>
                </li>
            </ul>
        </div>
    </section>
</div>


<!-- aqui el apartado de seleccionar el numero de telefono -->

<div class="overlay" id="overlay"></div>
    <div class="popup" id="popup">
        <span class="close" onclick="closePhonePopup()">&times;</span>
        <h2>Introduce tu número de teléfono</h2>
        <div class="select-container">
            <select id="country-list">
                <option value="" disabled selected>Selecciona tu país</option>
                <option value="+1">Estados Unidos/Canadá (+1)</option>
                <option value="+52">México (+52)</option>
                <option value="+34">España (+34)</option>
                <option value="+44">Reino Unido (+44)</option>
            </select>
        </div>
        <input type="text" id="phone-number" placeholder="Número de teléfono" oninput="formatPhoneNumber(this)">
        <button onclick="submitNumber()">Enviar</button>
    </div>



    
<div class="overlay-new" id="overlay-new"></div>
    <div class="popup-new" id="popup-new">
        <span class="close-new" onclick="closePopup()">&times;</span>
        <h2>Introduce tu fecha de nacimiento</h2>
        <input type="date" id="birthdate-new" max="2017-12-31">
        <button onclick="submitDate()">Enviar</button>
    </div>






 <!-- Message Popups -->
   <div class="message-popup" id="message-popup">
        <p id="message-text"></p>
        <button onclick="closeMessagePopup()">OK</button>
    </div>

     <div class="message-popup-new" id="message-popup-new">
        <p id="message-text-new"></p>
        <button onclick="closeMessagePopup()">OK</button>
    </div>





    
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



   
    <script>
    let validationFailed = false;

    // Date of Birth Popup
    function openPopup() {
        if (document.getElementById('popup-new').style.display !== 'block') {
            closePhonePopup();
            document.getElementById('popup-new').style.display = 'block';
            document.getElementById('overlay-new').style.display = 'block';
            setTimeout(() => {
                document.getElementById('popup-new').classList.remove('hide');
                document.getElementById('overlay-new').classList.remove('hide');
            }, 10);
        }
    }

    function submitDate() {
        var birthdate = document.getElementById('birthdate-new').value;
        if (birthdate) {
            closePopup();
            showMessagePopup('Tu fecha de nacimiento es: ' + birthdate);
        } else {
            showMessagePopup('Por favor, ingresa una fecha válida.');
        }
    }

    function closePopup() {
        document.getElementById('popup-new').classList.add('hide');
        document.getElementById('overlay-new').classList.add('hide');
        setTimeout(() => {
            document.getElementById('popup-new').style.display = 'none';
            document.getElementById('overlay-new').style.display = 'none';
        }, 300);
    }

    // Phone Number Popup
    function openPhonePopup() {
        if (document.getElementById('popup').style.display !== 'block') {
            closePopup();
            document.getElementById('popup').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
            setTimeout(() => {
                document.getElementById('popup').classList.remove('hide');
                document.getElementById('overlay').classList.remove('hide');
            }, 10);
        }
    }

    function submitNumber() {
        var countryCode = document.getElementById('country-list').value;
        var phoneNumber = document.getElementById('phone-number').value;

        // Eliminar cualquier formato de los números (por ejemplo, guiones)
        var cleanedPhoneNumber = phoneNumber.replace(/\D/g, '');

        // Verificar si el número de teléfono tiene la longitud adecuada según el país seleccionado
        if (!countryCode || !cleanedPhoneNumber) {
            showMessagePopup('Por favor, completa todos los campos antes de enviar.');
            validationFailed = true;
        } else {
            // Validar la longitud del número de teléfono
            if ((countryCode === '+1' && cleanedPhoneNumber.length !== 10) || 
                (countryCode === '+52' && cleanedPhoneNumber.length !== 10) ||
                (countryCode === '+34' && cleanedPhoneNumber.length !== 9) || 
                (countryCode === '+44' && cleanedPhoneNumber.length !== 10)) {
                showMessagePopup('El número de teléfono no es válido. Verifica la longitud y formato.');
                validationFailed = true;
            } else {
                showMessagePopup('Tu número de teléfono es: ' + countryCode + ' ' + phoneNumber);
                validationFailed = false;
                closePhonePopup();
            }
        }
    }

    function formatPhoneNumber(input) {
        var cleaned = ('' + input.value).replace(/\D/g, '');
        var formatted = cleaned.match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
        input.value = !formatted[2] ? formatted[1] : formatted[1] + '-' + formatted[2] + (formatted[3] ? '-' + formatted[3] : '');
    }

    function closePhonePopup() {
        document.getElementById('popup').classList.add('hide');
        document.getElementById('overlay').classList.add('hide');
        setTimeout(() => {
            document.getElementById('popup').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }, 300);
    }

    // Message Popups (Sin la "X" de cierre)
    function showMessagePopup(message) {
        document.getElementById('message-text').textContent = message;
        document.getElementById('message-popup').style.display = 'block';
        setTimeout(() => {
            document.getElementById('message-popup').classList.remove('hide');
        }, 10);
    }

    function closeMessagePopup() {
        document.getElementById('message-popup').classList.add('hide');
        setTimeout(() => {
            document.getElementById('message-popup').style.display = 'none';
            if (validationFailed) {
                document.getElementById('popup').style.display = 'block';
                document.getElementById('popup').classList.remove('hide');
            } else {
                document.getElementById('overlay').classList.add('hide');
                setTimeout(() => {
                    document.getElementById('overlay').style.display = 'none';
                }, 300);
            }
        }, 300);
    }

    $(document).ready(function() {
        $('#country-list').select2({
            dropdownAutoWidth: true,
            width: '100%'
        });
    });
</script>
</body>
</html>
