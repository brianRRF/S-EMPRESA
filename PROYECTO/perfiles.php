<?php
session_start();
include 'conexion_be.php'; // Conexión a la base de datos

// Verificar si la cookie 'usuario' está establecida y restaurar sesión si es necesario
if (isset($_COOKIE['usuario']) && !isset($_SESSION['usuario'])) {
    $_SESSION['usuario'] = $_COOKIE['usuario'];
    $_SESSION['nombre_c'] = $_COOKIE['nombre_c'] ?? 'Usuario No Definido';
    $_SESSION['id_cargo'] = $_COOKIE['id_cargo'] ?? null;
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header("location: inicio.php");
    exit();
}

// Obtener datos del usuario logueado
$correo_elec = mysqli_real_escape_string($conexion, trim($_SESSION['usuario'] ?? ''));
$nombre_usuario = $_SESSION['nombre_c'] ?? 'Usuario No Definido'; // nombre_c es el nombre de usuario
$id_cargo = mysqli_real_escape_string($conexion, trim($_SESSION['id_cargo'] ?? null));

// Consultar la foto de perfil y el nombre completo del usuario logueado desde la base de datos
$query = "SELECT foto_perfil, nombre, nombre_c FROM usuario WHERE correo_elec='$correo_elec'";
$resultado = mysqli_query($conexion, $query);
$row = mysqli_fetch_assoc($resultado);

// Asignar valores predeterminados si no se encuentran en la base de datos
$foto_perfil = $row['foto_perfil'] ?? 'https://i.ibb.co/Wpcn6Dq9/R.png'; // Imagen predeterminada alojada en ImgBB
$nombre_completo = $row['nombre'] ?? 'Nombre Completo No Definido'; // nombre es el nombre completo
$nombre_usuario = $row['nombre_c'] ?? $nombre_usuario; // nombre_c es el nombre de usuario

// Consultar los usuarios según el id_cargo del usuario logueado
if ($id_cargo == 1) {
    // Si el usuario tiene id_cargo = 1, puede ver todos los usuarios
    $query_usuarios = "SELECT * FROM usuario";
} elseif ($id_cargo == 2) {
    // Si el usuario tiene id_cargo = 2, solo puede ver usuarios normales
    $query_usuarios = "SELECT * FROM usuario WHERE id_cargo IS NULL";
} else {
    // Si el usuario no tiene id_cargo, no puede ver usuarios con id_cargo
    $query_usuarios = "SELECT * FROM usuario WHERE id_cargo IS NULL";
}

$resultado_usuarios = mysqli_query($conexion, $query_usuarios);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile Cards</title>
  <link rel="icon" href="4-icono.ico">
  <script type="module" src="https://unpkg.com/ionicons@6.0.3/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@6.0.3/dist/ionicons/ionicons.js"></script>
  <link rel="stylesheet" href="elperron.css">

  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0..1,0" />

  <script>
    function toggleAddButton(button) {
      if (!button.classList.contains("sent")) {
        button.classList.add("sent");
        button.innerHTML = '<ion-icon name="checkmark-circle-outline"></ion-icon> Solicitud enviada';
      } else {
        button.classList.remove("sent");
        button.innerHTML = '<ion-icon name="person-add-outline"></ion-icon> Agregar usuario';
      }
    }
  </script>
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
                    <li class="menu-item menu-item-static">
                        <a href="casa.php" class="menu-link">
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
                            <li><a href="#" class="sub-menu-link">Todo</a></li>
                            <li><a href="#" class="sub-menu-link">Publicidad</a></li>
                            <li><a href="#" class="sub-menu-link">Empredimiento</a></li>
                        </ul>
                    </li>
        
                    <li class="menu-item menu-item-static active">
                        <a href="#" class="menu-link">
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
                    <img src="<?php echo htmlspecialchars($foto_perfil); ?>" alt="user">
                </div>
                <div class="user-data">
                    <span class="name"><?php echo htmlspecialchars($nombre_usuario); ?></span>
                    <span class="email"><?php echo htmlspecialchars($correo_elec); ?></span>
                </div>
                <div class="user-icon">
                    <i class='bx bx-exit' ></i>
                </div>
            </div>
        </div>
        
    </div>

    <main>
  <div class="profile-container">
  <?php while ($usuario = mysqli_fetch_assoc($resultado_usuarios)): ?>
    <?php if ($usuario['correo_elec'] === $correo_elec) continue; // Saltar si el usuario es el logueado ?>
    <!-- Carta Normal -->
    <div class="profile-card">
      <button class="add-button" onclick="toggleAddButton(this)">
        <ion-icon name="person-add-outline"></ion-icon> Agregar usuario
      </button>
      <div class="profile-header normal">
        <a href="normal-profile.html">
          <img src="<?php echo htmlspecialchars($usuario['foto_perfil'] ?? 'https://i.ibb.co/YdR6Rng/R.png'); ?>" alt="Profile Image" class="profile-image">
        </a>
      </div>
      <div class="profile-info">
        <h2><?php echo htmlspecialchars($usuario['nombre_c'] ?? 'Nombre Completo No Definido'); ?></h2>
        <h3>@<?php echo htmlspecialchars($usuario['nombre']); ?></h3>
        <div class="verified-badge">
          <ion-icon name="checkmark-circle-outline"></ion-icon> Verificado
        </div>
        <p>Nivel Normal</p>
        <div class="email-section">
          <strong>Correo:</strong> <br>
          <a href="mailto:<?php echo htmlspecialchars($usuario['correo_elec']); ?>" style="color: #007bff; text-decoration: none;"><?php echo htmlspecialchars($usuario['correo_elec']); ?></a>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
  </div>

  <script src="bienvenido.js"></script>

</main>
</body>
</html>