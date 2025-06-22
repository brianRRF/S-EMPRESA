<?php
session_start();
include 'conexion_be.php'; // Conexión a la base de datos

// Verifica que el usuario esté logueado y tenga permisos de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['id_cargo'] != 2) {
    echo "Acceso denegado.";
    exit();
}

// Obtiene el ID de la empresa desde la URL
$id_empresa = $_GET['id'];

// Consulta los datos de la empresa
$query = "SELECT * FROM empresa WHERE id_empresa = $id_empresa";
$resultado = mysqli_query($conexion, $query);

// Muestra los datos de la empresa si existe
if ($empresa = mysqli_fetch_assoc($resultado)) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Detalles de Empresa</title>
        <link rel="icon" href="4-icono.ico">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
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
                    <li class="menu-item menu-item-static">
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
        
                    <li class="menu-item menu-item-dropdown active">
                        <a class="menu-link">
                        <i class='bx bx-comment-add'></i>
                            <span>Solicitudes</span>
                            <i class='bx bx-chevron-down' ></i>
                        </a>
                        <ul class="sub-menu">
                            <li><a href="#" class="sub-menu-link">TODO</a></li>
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
        <div class="container mt-5">
            <h1 class="mb-4">Detalles de la Empresa</h1>
            <p><strong>Nombre Legal:</strong> <?php echo $empresa['nombre_legal']; ?></p>
            <p><strong>Nombre Comercial:</strong> <?php echo $empresa['nombre_comercial']; ?></p>
            <p><strong>Identificación Fiscal:</strong> <?php echo $empresa['identificacion_fiscal']; ?></p>
            <p><strong>Teléfono:</strong> <?php echo $empresa['telefono_empresa']; ?></p>
            <p><strong>Dirección:</strong> <?php echo $empresa['direccion_empresa']; ?></p>
            <p><strong>Representante Legal:</strong> <?php echo $empresa['nombre_representante']; ?></p>
            <p><strong>Descripción del Negocio:</strong> <?php echo $empresa['descripcion_negocio']; ?></p>
            <p><strong>Página Web:</strong> <a href="<?php echo $empresa['pagina_web']; ?>" target="_blank"><?php echo $empresa['pagina_web']; ?></a></p>

            <h3>Documentos</h3>
            <ul>
                <li><a href="<?php echo $empresa['certificado_constitucion']; ?>" target="_blank">Certificado de Constitución</a></li>
                <li><a href="<?php echo $empresa['registro_mercantil']; ?>" target="_blank">Registro Mercantil</a></li>
                <li><a href="<?php echo $empresa['licencias_permisos']; ?>" target="_blank">Licencias o Permisos</a></li>
                <li><a href="<?php echo $empresa['documento_identidad_representante']; ?>" target="_blank">Documento de Identidad del Representante</a></li>
            </ul>

            <a href="gestionar_empresas.php" class="btn btn-secondary">Volver</a>
        </div>
</main>
        <script src="bienvenido.js"></script>
    </body>
    </html>
    <?php
} else {
    echo "<p>No se encontró la empresa.</p>";
    echo '<a href="gestionar_empresas.php">Volver</a>';
}
?>