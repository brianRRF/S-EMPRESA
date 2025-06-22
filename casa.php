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
    <link rel="stylesheet" href="elperron.css">
    <link rel="stylesheet" href="casa.css">
    <link rel="stylesheet" href="empresas.css">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Ionicons CDN -->
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

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
                <span>S-EMPRESA</span>
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
                        <a href="bienbenido.php" class="menu-link">
                            <i class='bx bx-briefcase-alt' ></i>
                            <span>Empresas</span>
                        </a>
                    </li>
        
                    <li class="menu-item menu-item-dropdown">
                        <a class="menu-link">
                            <i class='bx bx-history' ></i>
                            <span>Historias</span>
                            <i class='bx bx-chevron-down' ></i>
                        </a>
                        <ul class="sub-menu">
                            <li><a href="" class="sub-menu-link">Todo</a></li>
                            <li><a href="" class="sub-menu-link">Publicidad</a></li>
                            <li><a href="" class="sub-menu-link">Empredimiento</a></li>
                        </ul>
                    </li>
        
                    <li class="menu-item menu-item-static">
                        <a href="perfiles.php" class="menu-link">
                            <i class='bx bxs-user-circle' ></i>
                            <span>Usuarios</span>
                        </a>
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
                </ul>
        </div>
        </div>

        <div class="footer">
            <ul class="menu">
                <li class="menu-item menu-item-static">
                    <a href="notificaciones_usuario.php" class="menu-link">
                        <i class='bx bxs-notification' ></i>
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


<!--<div class="cover">


<div class="bg_color"></div>
<div class="weve w1"></div>
<div class="weve w2"></div>

<div class="container_cover">
<div class="container_info">
    
    <h1 id="ser">SERVI</h1>
    <h2>EMPRESA</h2>
    <p id="text-content">Promoción de la Empresa: Cómo resaltar las características únicas y los valores de tu empresa.
        Calidad de los Servicios: La importancia de proporcionar servicios de alta calidad y cómo esto beneficia a tus clientes.</p>



                        





                    
</div>
<div class="container_vector">
    <img src="Removal-homal.png" alt="">
</div>
</div>
</div>-->





<div class="container-fluid">
    <main class="main-content">
      <div class="layout-flex mb-4">
        <!-- Izquierda: anuncio, crear espacio y herramientas -->
        <div class="left-column">
          <!-- Anuncio imagen con badge ADD y X -->
          <div id="anuncioImgCard"
            class="anuncio-img-card"
            onclick="window.location.href='mi-archivo.html'"
            tabindex="0"
            role="link"
            aria-label="Ir al anuncio">
            <img src="imagenes/Anuncio.png" alt="Anuncio Integración Calendario">
            <span class="ad-badge"><ion-icon name="megaphone-outline"></ion-icon>ADD</span>
            <button class="ad-close" onclick="event.stopPropagation(); document.getElementById('anuncioImgCard').style.display='none'" title="Cerrar anuncio">
              <ion-icon name="close-outline"></ion-icon>
            </button>
          </div>
          <!-- Crear nuevo espacio -->
          <div class="crear-espacio-ctn">
            <div class="circle"></div>
            <div class="main-content-ctn">
              <span class="main-icon"><ion-icon name="add-circle-outline"></ion-icon></span>
              <h4 class="mb-2">Crear nuevo espacio</h4>
              <p class="mb-3 text-secondary">
                Crea tus <span style="color:#6366f1;font-weight:600;">espacios</span>, empresas fácilmente creando un espacio personalizado donde podrás gestionar tareas, miembros, recursos y mas.
              </p>
              <button class="btn btn-primary px-4 fw-semibold" onclick="crearEspacio()">
                <ion-icon name="add-outline"></ion-icon>
                Crear espacio
              </button>
            </div>
            <span class="space-bg-icon"><ion-icon name="grid-outline"></ion-icon></span>
          </div>
          <!-- Herramientas del mismo tamaño que crear espacio -->
          <div class="herramientas-ctn">
            <h3>Herramientas</h3>
            <div class="tools-box mt-2" id="toolsBox"></div>
          </div>
        </div>
        <!-- Derecha: sección de tutorial de el proyecto y tutoriales -->
        <div class="right-column">
          <div class="intro-section-title">
            <ion-icon name="school-outline"></ion-icon>
            Tutorial del proyecto
          </div>
          <div class="row" id="tutorialCards"></div>
          <a href="perfilconfigg.php" class="ver-mas-link mt-3">
            <ion-icon name="arrow-forward-circle-outline"></ion-icon>
            Ver más información
          </a>
          <!-- Nueva sección introducción tipo video + info -->
          <div class="seccion-introduccion">
            <div class="seccion-introduccion-titulo">
              <ion-icon name="play-circle-outline"></ion-icon>
              Introducción
            </div>
            <div class="intro-row">
              <!-- Card video -->
              <div
                class="intro-card-video"
                onclick="window.open('https://www.youtube.com/watch?v=dQw4w9WgXcQ','_blank')"
                tabindex="0"
                role="link"
                aria-label="Ver video de introducción en Youtube"
                style="cursor:pointer;"
              >
                <div style="position:relative;width:100%;">
                  <img class="video-img-thumb"
                    src="https://img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg"
                    alt="Video de introducción">
                  <span class="video-play-btn">
                    <ion-icon name="play-circle"></ion-icon>
                  </span>
                </div>
                <div class="video-text">
                  Mira este video para una introducción visual rápida sobre el uso de S-EMPRESA.
                </div>
              </div>
              <!-- Card info -->
              <div class="intro-card-info">
                <h5>¿De qué trata S-EMPRESA?</h5>
                <p>
                  S-EMPRESA está diseñado para ayudarte a organizar tus empresas y proyectos de forma eficiente. Descubre cómo puedes crear espacios, gestionar tareas, colaborar y aprovechar todas las herramientas disponibles.
                </p>
              </div>
            </div>
          </div>
          <!-- Fin sección introducción -->

          <!-- Nueva sección de actualización -->
          <div class="seccion-introduccion" style="margin-top:2rem;">
            <div class="actualizacion-titulo">
              <ion-icon name="refresh-circle-outline"></ion-icon>
              Actualización
            </div>
            <div style="display:flex;gap:1.5rem;flex-wrap:wrap;">
              <div class="actualizacion-card">
                <h5>Actualizacion: Alpha 0.5.5</h5>
                <p>
                  Nueva actualizacion para mejor menejo visual y registro de empresas con funciones de adicion 
                </p>
              </div>
            </div>
          </div>
          <!-- Fin sección actualización -->
        </div>
      </div>
    </main>
    <!-- Modal para crear espacio -->
    
  </div>


</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="bienvenido.js"></script>


    
  <script>
    // Datos simulados
    const tutorialLinks = [
      { title: "Terminos y condiciones", url: "#", icon: "rocket-outline", desc: "Aprende los conceptos básicos de nuestros derechos" },
      { title: "Cómo crear tu primer espacio", url: "#", icon: "add-circle-outline", desc: "Paso a paso para registro de tu empresa" },
      { title: "Que somos", url: "#", icon: "help-circle-outline", desc: "Responde tus dedas de quienes somos" },
    ];
    const tools = [
      { name: "Cal", icon: "calendar-outline" },
      { name: "Doc", icon: "document-outline" },
      { name: "Chat", icon: "chatbubble-ellipses-outline" },
    ];

    // Render tutoriales como cards con iconos
    document.getElementById('tutorialCards').innerHTML = tutorialLinks.map(link => `
      <div class="col-12 col-md-6 mb-3">
        <a href="${link.url}" class="text-decoration-none text-dark">
          <div class="card tutorial-card h-100 p-3 d-flex flex-row align-items-center gap-3">
            <span class="tutorial-icon-container"><span><ion-icon name="${link.icon}"></ion-icon></span></span>
            <div>
              <h5 class="card-title mb-1">${link.title}</h5>
              <p class="card-text text-secondary mb-0">${link.desc}</p>
            </div>
          </div>
        </a>
      </div>
    `).join('');

    // Render herramientas con ion-icon
    document.getElementById('toolsBox').innerHTML = tools.map(tool =>
      `<div class="tool-card">
        <span><ion-icon name="${tool.icon}"></ion-icon></span>
        <span class="fw-semibold">${tool.name}</span>
      </div>`
    ).join('');

    // Modal crear espacio
    function crearEspacio() {
      window.location.href = "registroempresa.html";
    }

    // Acción crear nuevo espacio
    document.getElementById('formNuevoEspacio').onsubmit = function(e) {
      e.preventDefault();
      const name = document.getElementById('nombreEspacio').value.trim();
      if (name) {
        // Aquí podrías agregar el nuevo espacio a una lista si lo deseas
        // Por simplicidad solo cierra el modal
      }
      bootstrap.Modal.getInstance(document.getElementById('modalCrearEspacio')).hide();
    }
  </script>
</body>
</html>