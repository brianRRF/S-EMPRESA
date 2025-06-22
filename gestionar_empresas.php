<?php
// Inicia la sesión para poder usar variables de sesión
session_start();

// Incluye el archivo de conexión a la base de datos
include 'conexion_be.php'; // Conexión a la base de datos

// --- Restaurar sesión desde cookie si es necesario ---
// Si existe la cookie 'usuario' pero no hay sesión, restaura los datos de sesión desde la cookie
if (isset($_COOKIE['usuario']) && !isset($_SESSION['usuario'])) {
    $_SESSION['usuario'] = $_COOKIE['usuario'];
    $_SESSION['nombre_usuario'] = $_COOKIE['nombre_usuario'];
    $_SESSION['id_cargo'] = $_COOKIE['id_cargo'];
}

// --- Verifica que el usuario esté logueado y tenga permisos de administrador ---
// Si no está logueado o no es administrador (id_cargo != 2), muestra mensaje y termina el script
if (!isset($_SESSION['usuario']) || $_SESSION['id_cargo'] != 2) {
    echo "Acceso denegado.";
    exit();
}

// --- Obtener datos del usuario autenticado ---
$correo_elec = $_SESSION['usuario'];
$nombre_c = $_SESSION['nombre_usuario'];

// --- Consultar la foto de perfil desde la base de datos ---
$queryUser = "SELECT foto_perfil FROM usuario WHERE correo_elec='$correo_elec'";
$resultUser = mysqli_query($conexion, $queryUser);
$rowUser = mysqli_fetch_assoc($resultUser);

// Usa la foto de perfil de la base de datos si existe, si no, usa una imagen predeterminada
$foto_perfil = $rowUser && $rowUser['foto_perfil'] ? $rowUser['foto_perfil'] : 'https://i.ibb.co/Wpcn6Dq9/R.png'; // Imagen predeterminada alojada en ImgBB

// --- Consultar empresas registradas, excluyendo las rechazadas ---
// Junta la tabla empresa con usuario para saber quién registró cada empresa
$queryEmpresas = "SELECT e.*, u.nombre_c AS usuario_registro 
                  FROM empresa e
                  JOIN usuario u ON e.id = u.id
                  WHERE e.estado NOT IN ('rechazada')";
$resultadoEmpresas = mysqli_query($conexion, $queryEmpresas);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empresas</title>
    <link rel="icon" href="4-icono.ico">


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="elperron.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
                        <a href="#" class="menu-link">
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
















    <main>
    <div class="container">
        <h1 class="mb-4">Gestión de Empresas</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Empresa</th>
                    <th>Representante</th>
                    <th>Usuario que Registró</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($resultadoEmpresas)): ?>
                    <tr>
                        <td><?php echo $row['nombre_legal']; ?></td>
                        <td><?php echo $row['nombre_representante']; ?></td>
                        <td><?php echo $row['usuario_registro']; ?></td>
                        <td><?php echo $row['estado'] ?? 'Pendiente'; ?></td>
                        <td>
                            <a href="ver_empresa.php?id=<?php echo $row['id_empresa']; ?>" class="btn btn-info">Ver Datos</a>
                            <a href="aceptar_empresa.php?id=<?php echo $row['id_empresa']; ?>" class="btn btn-success">Aceptar</a>
                            <a href="rechazar_empresa.php?id=<?php echo $row['id_empresa']; ?>" class="btn btn-danger">Rechazar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    </main>







    <script src="bienvenido.js"></script>
</body>
</html>