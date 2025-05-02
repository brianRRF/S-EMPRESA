<?php
include 'conexion_be.php'; // Archivo de conexión a la base de datos

// Configurar cabeceras para JSON
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = json_decode(file_get_contents('php://input'), true);

    // Validar el código
    if (isset($input['codigo']) && !isset($input['nueva_contrasena'])) {
        $codigo = mysqli_real_escape_string($conexion, trim($input['codigo'] ?? ''));

        // Verificar si el código es correcto y no ha expirado
        $consulta_codigo = "SELECT * FROM recuperacion WHERE codigo = '$codigo' AND fecha_caducidad > NOW()";
        $resultado_codigo = mysqli_query($conexion, $consulta_codigo);

        if (mysqli_num_rows($resultado_codigo) > 0) {
            echo json_encode(['success' => true, 'message' => 'Código válido.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'El código es incorrecto o ha expirado.']);
        }

        mysqli_close($conexion);
        exit();
    }

    // Procesar el cambio de contraseña
    if (isset($input['nueva_contrasena']) && isset($input['confirmar_contrasena']) && isset($input['codigo'])) {
        $codigo = mysqli_real_escape_string($conexion, trim($input['codigo'] ?? ''));
        $nueva_contrasena = mysqli_real_escape_string($conexion, trim($input['nueva_contrasena'] ?? ''));
        $confirmar_contrasena = mysqli_real_escape_string($conexion, trim($input['confirmar_contrasena'] ?? ''));

        // Verificar si el código es correcto
        $consulta_codigo = "SELECT * FROM recuperacion WHERE codigo = '$codigo' AND fecha_caducidad > NOW()";
        $resultado_codigo = mysqli_query($conexion, $consulta_codigo);

        if (mysqli_num_rows($resultado_codigo) === 0) {
            echo json_encode(['success' => false, 'message' => 'El código es incorrecto o ha expirado.']);
            mysqli_close($conexion);
            exit();
        }

        // Verificar si las contraseñas coinciden
        if ($nueva_contrasena !== $confirmar_contrasena) {
            echo json_encode(['success' => false, 'message' => 'Las contraseñas no coinciden.']);
            mysqli_close($conexion);
            exit();
        }

        // Obtener el correo asociado al código
        $fila = mysqli_fetch_assoc($resultado_codigo);
        $correo = $fila['correo'];

        // Encriptar la nueva contraseña
        $contrasena_hash = hash('sha512', $nueva_contrasena);

        // Actualizar la contraseña en la base de datos
        $actualizar_contrasena = "UPDATE usuario SET contrasena = '$contrasena_hash' WHERE correo_elec = '$correo'";
        if (mysqli_query($conexion, $actualizar_contrasena)) {
            // Eliminar el código de recuperación
            $eliminar_codigo = "DELETE FROM recuperacion WHERE correo = '$correo'";
            mysqli_query($conexion, $eliminar_codigo);

            echo json_encode(['success' => true, 'message' => 'Contraseña actualizada correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar la contraseña.']);
        }

        mysqli_close($conexion);
        exit();
    }

    // Solicitud inválida
    echo json_encode(['success' => false, 'message' => 'Solicitud inválida.']);
    mysqli_close($conexion);
}