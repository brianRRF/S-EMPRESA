<?php
// Incluye el archivo de conexión a la base de datos (debe definir $conexion)
include 'conexion_be.php';

// Configura la cabecera para que la respuesta sea en formato JSON
header('Content-Type: application/json');

// Verifica que la petición sea de tipo POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtiene los datos enviados en el cuerpo de la petición (JSON)
    $input = json_decode(file_get_contents('php://input'), true);

    // --- VALIDACIÓN DE CÓDIGO ---
    // Si se envía solo el código (sin nueva contraseña), valida el código
    if (isset($input['codigo']) && !isset($input['nueva_contrasena'])) {
        // Limpia el código recibido para evitar inyecciones SQL
        $codigo = mysqli_real_escape_string($conexion, trim($input['codigo'] ?? ''));

        // Consulta si el código existe y no ha expirado
        $consulta_codigo = "SELECT * FROM recuperacion WHERE codigo = '$codigo' AND fecha_caducidad > NOW()";
        $resultado_codigo = mysqli_query($conexion, $consulta_codigo);

        // Si el código es válido, responde con éxito; si no, con error
        if (mysqli_num_rows($resultado_codigo) > 0) {
            echo json_encode(['success' => true, 'message' => 'Código válido.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'El código es incorrecto o ha expirado.']);
        }

        // Cierra la conexión y termina el script
        mysqli_close($conexion);
        exit();
    }

    // --- CAMBIO DE CONTRASEÑA ---
    // Si se envía el código junto con nueva contraseña y confirmación, procesa el cambio
    if (isset($input['nueva_contrasena']) && isset($input['confirmar_contrasena']) && isset($input['codigo'])) {
        // Limpia los valores recibidos
        $codigo = mysqli_real_escape_string($conexion, trim($input['codigo'] ?? ''));
        $nueva_contrasena = mysqli_real_escape_string($conexion, trim($input['nueva_contrasena'] ?? ''));
        $confirmar_contrasena = mysqli_real_escape_string($conexion, trim($input['confirmar_contrasena'] ?? ''));

        // Verifica que el código sea válido y no haya expirado
        $consulta_codigo = "SELECT * FROM recuperacion WHERE codigo = '$codigo' AND fecha_caducidad > NOW()";
        $resultado_codigo = mysqli_query($conexion, $consulta_codigo);

        // Si el código no es válido, responde con error y termina
        if (mysqli_num_rows($resultado_codigo) === 0) {
            echo json_encode(['success' => false, 'message' => 'El código es incorrecto o ha expirado.']);
            mysqli_close($conexion);
            exit();
        }

        // Verifica que ambas contraseñas coincidan
        if ($nueva_contrasena !== $confirmar_contrasena) {
            echo json_encode(['success' => false, 'message' => 'Las contraseñas no coinciden.']);
            mysqli_close($conexion);
            exit();
        }

        // Obtiene el correo asociado al código de recuperación
        $fila = mysqli_fetch_assoc($resultado_codigo);
        $correo = $fila['correo'];

        // Encripta la nueva contraseña usando SHA-512
        $contrasena_hash = hash('sha512', $nueva_contrasena);

        // Actualiza la contraseña en la tabla usuario para ese correo
        $actualizar_contrasena = "UPDATE usuario SET contrasena = '$contrasena_hash' WHERE correo_elec = '$correo'";
        if (mysqli_query($conexion, $actualizar_contrasena)) {
            // Si el cambio fue exitoso, elimina el código de recuperación usado
            $eliminar_codigo = "DELETE FROM recuperacion WHERE correo = '$correo'";
            mysqli_query($conexion, $eliminar_codigo);

            // Responde con éxito
            echo json_encode(['success' => true, 'message' => 'Contraseña actualizada correctamente.']);
        } else {
            // Si hubo error al actualizar, responde con mensaje de error
            echo json_encode(['success' => false, 'message' => 'Error al actualizar la contraseña.']);
        }

        // Cierra la conexión y termina
        mysqli_close($conexion);
        exit();
    }

    // --- PETICIÓN INVÁLIDA ---
    // Si no se cumplen las condiciones anteriores, responde con solicitud inválida
    echo json_encode(['success' => false, 'message' => 'Solicitud inválida.']);
    mysqli_close($conexion);
}
?>