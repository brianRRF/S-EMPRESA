<?php
// Incluye el archivo de conexión a la base de datos (debe definir la variable $conexion)
include 'conexion_be.php';

// Configura la cabecera para que la respuesta sea en formato JSON (esto es útil para solicitudes AJAX)
header('Content-Type: application/json');

// Verifica si la petición HTTP recibida es de tipo POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Lee el cuerpo de la petición y lo decodifica como un arreglo asociativo desde JSON
    $input = json_decode(file_get_contents('php://input'), true);
    
    // --- 1. VALIDACIÓN DEL CÓDIGO DE RECUPERACIÓN ---
    // Si se recibió un código y NO se recibió una nueva contraseña, solo se valida el código
    if (isset($input['codigo']) && !isset($input['nueva_contrasena'])) {
        // Limpia el código recibido para evitar inyecciones SQL
        $codigo = mysqli_real_escape_string($conexion, trim($input['codigo'] ?? ''));

        // Consulta la base de datos para verificar si el código existe y no ha expirado
        $consulta_codigo = "SELECT * FROM recuperacion WHERE codigo = '$codigo' AND fecha_caducidad > NOW()";
        $resultado_codigo = mysqli_query($conexion, $consulta_codigo);

        // Si el código existe y es válido
        if (mysqli_num_rows($resultado_codigo) > 0) {
            // Devuelve una respuesta JSON indicando que el código es válido
            echo json_encode([
                'success' => true,
                'message' => 'Código válido.'
            ]);
        } else {
            // Si el código no existe o ha expirado, devuelve un mensaje de error
            echo json_encode([
                'success' => false,
                'message' => 'El código es incorrecto o ha expirado.'
            ]);
        }

        // Cierra la conexión a la base de datos y termina el script
        mysqli_close($conexion);
        exit();
    }

    // --- 2. PROCESO DE CAMBIO DE CONTRASEÑA ---
    // Si se recibió el código, la nueva contraseña y la confirmación de la contraseña
    if (isset($input['nueva_contrasena']) && isset($input['confirmar_contrasena']) && isset($input['codigo'])) {
        // Limpia los valores recibidos para seguridad
        $codigo = mysqli_real_escape_string($conexion, trim($input['codigo'] ?? ''));
        $nueva_contrasena = mysqli_real_escape_string($conexion, trim($input['nueva_contrasena'] ?? ''));
        $confirmar_contrasena = mysqli_real_escape_string($conexion, trim($input['confirmar_contrasena'] ?? ''));

        // Verifica que el código existe y no ha expirado
        $consulta_codigo = "SELECT * FROM recuperacion WHERE codigo = '$codigo' AND fecha_caducidad > NOW()";
        $resultado_codigo = mysqli_query($conexion, $consulta_codigo);

        // Si el código no es válido, responde con error y termina
        if (mysqli_num_rows($resultado_codigo) === 0) {
            echo json_encode([
                'success' => false,
                'message' => 'El código es incorrecto o ha expirado.'
            ]);
            mysqli_close($conexion);
            exit();
        }

        // Verifica que las dos contraseñas coincidan
        if ($nueva_contrasena !== $confirmar_contrasena) {
            echo json_encode([
                'success' => false,
                'message' => 'Las contraseñas no coinciden.'
            ]);
            mysqli_close($conexion);
            exit();
        }

        // Obtiene el correo electrónico asociado a ese código de recuperación
        $fila = mysqli_fetch_assoc($resultado_codigo);
        $correo = $fila['correo'];

        // Encripta la nueva contraseña usando el algoritmo SHA-512
        $contrasena_hash = hash('sha512', $nueva_contrasena);

        // Prepara la consulta SQL para actualizar la contraseña del usuario
        $actualizar_contrasena = "UPDATE usuario SET contrasena = '$contrasena_hash' WHERE correo_elec = '$correo'";

        // Ejecuta la consulta para actualizar la contraseña
        if (mysqli_query($conexion, $actualizar_contrasena)) {
            // Si la actualización fue exitosa, elimina el código de recuperación para ese correo
            $eliminar_codigo = "DELETE FROM recuperacion WHERE correo = '$correo'";
            mysqli_query($conexion, $eliminar_codigo);

            // Devuelve mensaje de éxito en formato JSON
            echo json_encode([
                'success' => true,
                'message' => 'Contraseña actualizada correctamente.'
            ]);
        } else {
            // Si hubo un error al actualizar, responde con mensaje de error
            echo json_encode([
                'success' => false,
                'message' => 'Error al actualizar la contraseña. Inténtalo de nuevo.'
            ]);
        }

        // Cierra la conexión a la base de datos y termina el script
        mysqli_close($conexion);
        exit();
    }

    // --- 3. SOLICITUD INVÁLIDA ---
    // Si no se cumplen ninguno de los casos anteriores, responde con mensaje de solicitud inválida
    echo json_encode([
        'success' => false,
        'message' => 'Solicitud inválida.'
    ]);
    mysqli_close($conexion);
    exit();
}
?>