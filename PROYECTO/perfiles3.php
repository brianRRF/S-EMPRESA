<?php
session_start();
include 'conexion_be.php'; // Conexión a la base de datos

// Verificar si la cookie 'usuario' está establecida y restaurar sesión si es necesario
if (isset($_COOKIE['usuario']) && !isset($_SESSION['usuario'])) {
    $_SESSION['usuario'] = $_COOKIE['usuario'];
    $_SESSION['nombre_c'] = $_COOKIE['nombre_c'] ?? 'Nombre Completo No Definido';
    $_SESSION['id_cargo'] = $_COOKIE['id_cargo'] ?? null;
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header("location: inicio.php");
    exit();
}

// Obtener datos del usuario logueado
$correo_elec = mysqli_real_escape_string($conexion, trim($_SESSION['usuario'] ?? ''));
$nombre_completo = $_SESSION['nombre_c'] ?? 'Nombre Completo No Definido';
$id_cargo = mysqli_real_escape_string($conexion, trim($_SESSION['id_cargo'] ?? null));

// Consultar la foto de perfil y el nombre de usuario del usuario logueado desde la base de datos
$query = "SELECT foto_perfil, nombre FROM usuario WHERE correo_elec='$correo_elec'";
$resultado = mysqli_query($conexion, $query);
$row = mysqli_fetch_assoc($resultado);

// Asignar valores predeterminados si no se encuentran en la base de datos
$foto_perfil = $row['foto_perfil'] ?? 'https://i.ibb.co/YdR6Rng/R.png'; // Imagen predeterminada alojada en ImgBB
$nombre_usuario = $row['nombre'] ?? 'Usuario Desconocido';

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
  <title>Usuarios</title>
  <link rel="icon" href="4-icono.ico">
  <script type="module" src="https://unpkg.com/ionicons@6.0.3/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@6.0.3/dist/ionicons/ionicons.js"></script>
  <link rel="stylesheet" href="elperron.css">
</head>
<body>
  <!-- Información del usuario logueado -->
  <div class="user">
      <div class="user-img">
          <img src="<?php echo htmlspecialchars($foto_perfil); ?>" alt="user">
      </div>
      <div class="user-data">
          <span class="name"><?php echo htmlspecialchars($nombre_usuario); ?></span>
          <span class="email"><?php echo htmlspecialchars($correo_elec); ?></span>
      </div>
  </div>

  <main>
    <div class="profile-container">
      <?php while ($usuario = mysqli_fetch_assoc($resultado_usuarios)): ?>
        <div class="profile-card">
          <div class="profile-header normal">
            <img src="<?php echo htmlspecialchars($usuario['foto_perfil'] ?? 'https://i.ibb.co/YdR6Rng/R.png'); ?>" alt="Profile Image" class="profile-image">
          </div>
          <div class="profile-info">
            <h2><?php echo htmlspecialchars($usuario['nombre_c'] ?? 'Nombre Completo No Definido'); ?></h2>
            <h3>@<?php echo htmlspecialchars($usuario['nombre']); ?></h3>
            <p>
              <?php 
                if ($usuario['id_cargo'] == 1) {
                  echo "Nivel Administrador";
                } elseif ($usuario['id_cargo'] == 2) {
                  echo "Nivel Supervisor";
                } else {
                  echo "Nivel Normal";
                }
              ?>
            </p>
            <div class="email-section">
              <strong>Correo:</strong> <br>
              <a href="mailto:<?php echo htmlspecialchars($usuario['correo_elec']); ?>" style="color: #007bff; text-decoration: none;">
                <?php echo htmlspecialchars($usuario['correo_elec']); ?>
              </a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </main>
</body>
</html>