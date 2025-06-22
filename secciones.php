<?php 
session_start();
include 'conexion_be.php'; // Asegurar la conexión a la base de datos

// Verificar si la cookie 'usuario' está establecida y restaurar sesión si es necesario
if (isset($_COOKIE['usuario']) && !isset($_SESSION['usuario'])) {
    $_SESSION['usuario'] = $_COOKIE['usuario'];
    $_SESSION['nombre_usuario'] = $_COOKIE['nombre_usuario'];
    $_SESSION['id_cargo'] = $_COOKIE['id_cargo'];
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header("location: inicio.php");
    exit();
}

// Obtener datos del usuario
$correo_elec = $_SESSION['usuario'];
$nombre_c = $_SESSION['nombre_usuario'];

// Consultar la foto de perfil desde la base de datos
$query = "SELECT foto_perfil FROM usuario WHERE correo_elec='$correo_elec'";
$resultado = mysqli_query($conexion, $query);
$row = mysqli_fetch_assoc($resultado);

// Usar la URL pública de la imagen desde la base de datos o una imagen predeterminada en ImgBB
$foto_perfil = $row['foto_perfil'] ?? 'https://i.ibb.co/Wpcn6Dq9/R.png'; // Imagen predeterminada alojada en ImgBB
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="icon" href="4-icono.ico">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="elperron.css">
</head>
<body>
<div class="menu-btn sidebar-btn" id="sidebar-btn">
        <i class='bx bx-menu' ></i>
        <i class='bx bx-x' ></i>
    </div>
    <div class="sidebar" id="sidebar">
        <div class="header">
            <div class="menu-btn" id="menu-btn">
                <i class='bx bx-chevron-left'></i>
            </div>
            <div class="brand">
                <img src="4-icono.ico" alt="logo">
                <span>ADMINISTRA</span>
            </div>
            <div class="menu-container">
                <div class="search">
                    <i class='bx bx-search' ></i>
                    <input type="search" placeholder="buscar">
                </div>

                <ul class="menu">
                    <li class="menu-item menu-item-static active">
                        <a href="#" class="menu-link">
                        <i class='bx bx-home-alt-2' ></i>
                            <span>Casa</span>
                        </a>
                    </li>

                    <li class="menu-item menu-item-static">
                        <a href="#" class="menu-link">
                        <i class='bx bx-calendar'></i>
                            <span>calendario</span>
                        </a>
                    </li>

                    <li class="menu-item menu-item-static">
                        <a href="desarrollador.php" class="menu-link">
                            <i class='bx bxs-user-circle' ></i>
                            <span>Usuarios</span>
                        </a>
                    </li>
        
                    <li class="menu-item menu-item-dropdown">
                        <a class="menu-link">
                        <i class='bx bx-comment-add'></i>
                            <span>Solicitudes</span>
                            <i class='bx bx-chevron-down' ></i>
                        </a>
                        <ul class="sub-menu">
                            <li><a href="gestionar_empresas.php" class="sub-menu-link">TODO</a></li>
                            <li><a href="" class="sub-menu-link">Microempresas</a></li>
                            <li><a href="" class="sub-menu-link">Pequeñas empresas</a></li>
                            <li><a href="" class="sub-menu-link">Medianas empresas</a></li>
                            <li><a href="" class="sub-menu-link">Grandes empresas</a></li>
                        </ul>
                    </li>

                    <li class="menu-item menu-item-dropdown">
                        <a class="menu-link">
                            <i class='bx bx-file' ></i>
                            <span>Documentos</span>
                            <i class='bx bx-chevron-down' ></i>
                            
                        </a>
                        <ul class="sub-menu">
                            <li><a href="#" class="sub-menu-link">Terminos</a></li>
                            <li><a href="#" class="sub-menu-link">Pribacidad</a></li>
                        </ul>
                    </li>
        
                    
        

                    <li class="menu-item menu-item-dropdown">
                        <a class="menu-link">
                        <i class='bx bx-history' ></i>
                            <span>Historias</span>
                            <i class='bx bx-chevron-down' ></i>
                            
                        </a>
                        <ul class="sub-menu">
                            <li><a href="#" class="sub-menu-link">TODO</a></li>
                            <li><a href="#" class="sub-menu-link">Publicidad</a></li>
                            <li><a href="#" class="sub-menu-link">emprendimiento</a></li>
                        </ul>
                    </li>

                    <li class="menu-item menu-item-static">
                        <a href="paginitas.html" class="menu-link">
                        <i class='bx bx-book-content'></i>
                            <span>Contenido</span>
                        </a>
                    </li>

                    
                </ul>
        </div>
        </div>

        <div class="footer">
            <ul class="menu">
                <li class="menu-item menu-item-static">
                    <a href="notificaciones_admin.php" class="menu-link">
                    <i class='bx bx-user-voice'></i>
                        <span>Notificacion</span>
                    </a>
                </li>
    
                <li class="menu-item menu-item-static">
                    <a href="perfil.php" class="menu-link">
                        <i class='bx bx-cog' ></i>
                        <span>Configuracion</span>
                    </a>
                </li>

            </ul>

            <div class="user">
                <div class="user-img">
                    <img src="<?php echo $foto_perfil; ?>" alt="user">
                </div>
                <div class="user-data">
                    <span class="name"><?php echo htmlspecialchars($nombre_c);?></span>
                    <span class="email"><?php echo htmlspecialchars($correo_elec);?></span>
                </div>
                <div class="user-icon">
                    <i class='bx bx-exit' ></i>
                </div>
            </div>
        </div>
        
    </div>



    <script src="bienvenido.js"></script>

</body>
</html>