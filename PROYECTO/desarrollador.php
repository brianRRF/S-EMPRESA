<?php
// Inicia la sesión
session_start();

// Incluye el archivo con la configuración y conexión a la base de datos
include 'conexion_be.php';

// Obtiene el ID del cargo y el correo del usuario actual desde la sesión
$currentUserCargo = $_SESSION['id_cargo'];
$currentUserCorreo = $_SESSION['usuario'];

// Construye la consulta en función del cargo del usuario actual
if ($currentUserCargo == 1) {
    // Los usuarios con cargo 1 pueden ver a todos excepto a sí mismos
    $query = "SELECT * FROM usuario WHERE correo_elec != '$currentUserCorreo'";
} elseif ($currentUserCargo == 2) {
    // Los usuarios con cargo 2 solo pueden ver usuarios con cargo NULL
    $query = "SELECT * FROM usuario WHERE id_cargo IS NULL AND correo_elec != '$currentUserCorreo'";
} else {
    // Si el cargo no es reconocido, no se muestra nada
    $query = "SELECT * FROM usuario WHERE 1 = 0";
}

$result = mysqli_query($conexion, $query);
$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

// Manejo de acciones (convertir en administrador, quitar cargo, eliminar usuario)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'admin' && isset($_POST['id'])) {
            // Convertir usuario en administrador
            $id = intval($_POST['id']);
            $updateQuery = "UPDATE usuario SET id_cargo = 2 WHERE id = $id";
            mysqli_query($conexion, $updateQuery);
            echo json_encode(["status" => "success", "message" => "Usuario convertido en administrador."]);
            exit();
        } elseif ($action === 'remove_admin' && isset($_POST['id'])) {
            // Quitar cargo de administrador
            $id = intval($_POST['id']);
            $updateQuery = "UPDATE usuario SET id_cargo = NULL WHERE id = $id";
            mysqli_query($conexion, $updateQuery);
            echo json_encode(["status" => "success", "message" => "Cargo de administrador eliminado."]);
            exit();
        } elseif ($action === 'delete' && isset($_POST['id'])) {
            // Eliminar usuario
            $id = intval($_POST['id']);
            $deleteQuery = "DELETE FROM usuario WHERE id = $id";
            mysqli_query($conexion, $deleteQuery);
            echo json_encode(["status" => "success", "message" => "Usuario eliminado exitosamente."]);
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrador de Usuarios</title>
  <script type="module" src="https://unpkg.com/ionicons@7.1.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.2/dist/ionicons/ionicons.js"></script>
  <link rel="stylesheet" href="administra.css">
</head>
<body>
  <!-- Contador de usuarios -->
  <div class="user-counter">
    <ion-icon name="people-outline"></ion-icon>
    <span id="userCount"><?= count($users) ?></span> Usuarios Registrados
  </div>

  <div class="container">
    <h1>Administrador de Usuarios</h1>
    <div class="search-container">
      <input type="text" id="searchInput" placeholder="Buscar por nombre, correo...">
    </div>
    <table>
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Apellido</th>
          <th>Correo</th>
          <th>Teléfono</th>
          <th>Edad</th>
          <th>Fecha de Nacimiento</th>
          <th>Sexo</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody id="userList">
        <?php if (count($users) == 0): ?>
          <tr>
            <td colspan="8" style="text-align: center;">No hay usuarios registrados</td>
          </tr>
        <?php else: ?>
          <?php foreach ($users as $index => $user): ?>
          <tr id="user-row-<?= $user['id'] ?>">
            <td><?= htmlspecialchars($user['nombre']) ?></td>
            <td><?= htmlspecialchars($user['apellido']) ?></td>
            <td><?= htmlspecialchars($user['correo_elec']) ?></td>
            <td><?= htmlspecialchars($user['telefono']) ?></td>
            <td><?= htmlspecialchars($user['edad']) ?></td>
            <td><?= htmlspecialchars($user['fecha_nacimiento']) ?></td>
            <td><?= htmlspecialchars($user['sexo']) ?></td>
            <td>
              <div class="actions">
                <button class="edit" data-id="<?= $user['id'] ?>"><ion-icon name="pencil"></ion-icon>Editar</button>
                <button onclick="deleteUser(<?= $user['id'] ?>)"><ion-icon name="trash"></ion-icon>Eliminar</button>
                <?php if ($currentUserCargo == 1): ?>
                  <?php if ($user['id_cargo'] == 2): ?>
                    <button onclick="updateAdminStatus(<?= $user['id'] ?>, 'remove_admin')">
                      <ion-icon name="remove-circle"></ion-icon>Baja
                    </button>
                  <?php else: ?>
                    <button onclick="updateAdminStatus(<?= $user['id'] ?>, 'admin')">
                      <ion-icon name="medal"></ion-icon>Administrador
                    </button>
                  <?php endif; ?>
                <?php endif; ?>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <script>
    async function updateAdminStatus(userId, actionType) {
      const confirmationMessage = actionType === 'admin' 
        ? '¿Estás seguro de convertir a este usuario en administrador?' 
        : '¿Estás seguro de quitar el cargo de administrador?';

      if (!confirm(confirmationMessage)) {
        return;
      }

      const formData = new FormData();
      formData.append('id', userId);
      formData.append('action', actionType);

      try {
        const response = await fetch('', {
          method: 'POST',
          body: formData,
        });

        const result = await response.json();

        if (result.status === 'success') {
          alert(result.message);
          location.reload(); // Recargar la página para reflejar los cambios
        } else {
          alert('Ocurrió un error. Intenta de nuevo.');
        }
      } catch (error) {
        console.error('Error:', error);
        alert('Ocurrió un error al procesar la solicitud.');
      }
    }

    async function deleteUser(userId) {
      if (!confirm('¿Estás seguro de eliminar a este usuario?')) {
        return;
      }

      const formData = new FormData();
      formData.append('id', userId);
      formData.append('action', 'delete');

      try {
        const response = await fetch('', {
          method: 'POST',
          body: formData,
        });

        const result = await response.json();

        if (result.status === 'success') {
          alert(result.message);
          location.reload(); // Recargar la página para reflejar los cambios
        } else {
          alert('Ocurrió un error al eliminar al usuario. Intenta de nuevo.');
        }
      } catch (error) {
        console.error('Error:', error);
        alert('Ocurrió un error al procesar la solicitud.');
      }
    }
  </script>
</body>
</html>