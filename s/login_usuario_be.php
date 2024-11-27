<?php

session_start();

include 'conexion_be.php';

$correo = $_POST['correo_elec'];
$contrasena = $_POST['contrasena'];
$contrasena = hash('sha512', $contrasena);


$validar_login = mysqli_query($conexion, "SELECT * FROM usuario WHERE correo_elec='$correo' and contrasena='$contrasena'");

if (mysqli_num_rows($validar_login) > 0) { 
  $row = mysqli_fetch_assoc($validar_login); 
  $_SESSION['usuario'] = $correo; 
  $_SESSION['nombre_usuario'] = $row['nombre_c']; 
  header("location: bienbenido.php"); 
  exit();

}else{
  echo '
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
  Swal.fire({
     title: "Datos incorrectos",
     text: "Los datos introducidos son incorrectos, intenta de nuevo.",
     icon: "error",
     confirmButtonText: "Ok"
  }).then((result) => {
     if (result.isConfirmed) {
        window.location = "inicio.php";
     }
  });
  </script>
';
exit();
}
?>