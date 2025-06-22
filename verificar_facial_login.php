<?php
// Configura la cabecera para que la respuesta sea en formato JSON
header('Content-Type: application/json');

// Inicia la sesión para poder gestionar variables de sesión del usuario
session_start();

// Incluye el archivo de conexión a la base de datos (define la variable $conexion)
include 'conexion_be.php';

// Verifica que la petición sea POST, si no, devuelve error y termina el script
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success'=>false, 'message'=>'Método no permitido']);
    exit();
}

// Lee el cuerpo de la petición y lo decodifica como array asociativo desde JSON
$data = json_decode(file_get_contents("php://input"), true);

// Si no se recibió información válida o no se envió el descriptor facial, responde con error
if (!$data || !isset($data['descriptor'])) {
    echo json_encode(['success'=>false, 'message'=>'Descriptor no recibido']);
    exit();
}

// Guarda el descriptor facial recibido desde el frontend
$new_descriptor = $data['descriptor'];

// Consulta todos los usuarios con registro facial y sus datos completos
$res = mysqli_query($conexion, "SELECT uf.id_usuario, uf.facial_descriptor, u.* FROM usuario_facial uf INNER JOIN usuario u ON uf.id_usuario=u.id");

// Inicializa variables para la mejor coincidencia encontrada y la mejor distancia (cuanto menor, mejor)
$bestMatch = null;
$bestDistance = 1.0;

// Función para calcular la distancia euclidiana entre dos descriptores faciales (vectores numéricos)
function euclidean_distance($a, $b) {
    $dist = 0;
    for ($i = 0; $i < count($a); $i++) {
        $dist += pow($a[$i] - $b[$i], 2); // Suma el cuadrado de la diferencia entre cada elemento
    }
    return sqrt($dist); // Devuelve la raíz cuadrada de la suma, es decir, la distancia euclidiana
}

// Recorre todos los usuarios con rostro registrado
while($user = mysqli_fetch_assoc($res)){
    // Decodifica el descriptor facial guardado en la base de datos (JSON a array)
    $desc = json_decode($user['facial_descriptor']);
    if(!$desc) continue; // Si no hay descriptor válido, pasa al siguiente usuario

    // Calcula la distancia euclidiana entre el descriptor recibido y el almacenado
    $dist = euclidean_distance($desc, $new_descriptor);

    // Si la distancia es menor que la mejor encontrada hasta ahora, guarda este usuario como el mejor match
    if($dist < $bestDistance){
        $bestDistance = $dist;
        $bestMatch = $user;
    }
}

// Si encontró una coincidencia con distancia suficientemente baja (<0.55), inicia sesión
if($bestMatch && $bestDistance < 0.55) {
    // Guarda los datos del usuario encontrado en la sesión
    $_SESSION['id'] = $bestMatch['id'];
    $_SESSION['usuario'] = $bestMatch['correo_elec'];
    $_SESSION['nombre_usuario'] = $bestMatch['nombre_c'];
    $_SESSION['id_cargo'] = $bestMatch['id_cargo'];

    // También los guarda en cookies por 1 año para mantener la sesión persistente
    setcookie('id', $bestMatch['id'], time() + (365 * 24 * 60 * 60), "/");
    setcookie('usuario', $bestMatch['correo_elec'], time() + (365 * 24 * 60 * 60), "/");
    setcookie('nombre_usuario', $bestMatch['nombre_c'], time() + (365 * 24 * 60 * 60), "/");
    setcookie('id_cargo', $bestMatch['id_cargo'], time() + (365 * 24 * 60 * 60), "/");

    // Devuelve éxito y redirección a la página principal
    echo json_encode(['success'=>true, 'redirect'=>'casa.php']);
} else {
    // Si no se encontró coincidencia facial, responde con error
    echo json_encode(['success'=>false, 'message'=>'No coincide ningún rostro registrado.']);
}
?>