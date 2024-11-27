</head>
<body>

</body>
</html>
<?php 


include 'conexion_be.php';

   $nombre_c = $_POST['nombre_c'];
   $correo_elec = $_POST['correo_elec'];
   $contrasena = $_POST['contrasena'];
   $contrasena = hash('sha512', $contrasena);

$query = "INSERT INTO usuario(nombre_c, correo_elec, contrasena)
             VALUES ('$nombre_c', '$correo_elec', '$contrasena')";




     $verifi_correo = mysqli_query($conexion, "SELECT * FROM usuario WHERE correo_elec='$correo_elec'");

    if (mysqli_num_rows($verifi_correo) > 0) {
      echo'
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
     Swal.fire({ title: "¡Correo existente!",
      text: "Este correo ya está registrado. Por favor, usa otro correo o inicia sesión.",
      icon: "error",
       confirmButtonText: "Ok" }).then((result) => 
       { if (result.isConfirmed)
         { window.location = "registro.php";
           } });
             </script> ';
    
    exit();
    }


   $verifi_user = mysqli_query($conexion, "SELECT * FROM usuario WHERE nombre_c='$nombre_c'");

   if (mysqli_num_rows($verifi_user) > 0) {
    echo'
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
     Swal.fire({ title: "!Este nombre ya existe!",
      text: "Este nombre ya está registrado. Por favor, usa otro nombre o inicia sesión.",
      icon: "error",
       confirmButtonText: "Ok" }).then((result) => 
       { if (result.isConfirmed)
         { window.location = "registro.php";
           } });
             </script> ';
    
    exit();
  }



 $ejecutar = mysqli_query($conexion, $query);
 if ($ejecutar) {
   echo '
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
     Swal.fire({ title: "REGISTRADO!!!",
      text: "Usuario registrado CORRECTAMENTE.",
      icon: "success",
       confirmButtonText: "Ok" }).then((result) => 
       { if (result.isConfirmed)
         { window.location = "inicio.php";
           } });
             </script>
   
   ';
 }else{
      echo '
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
     Swal.fire({ title: "¡ERROR!",
      text: "Usuario no registrado correctamente, intentalo de nuevo.",
      icon: "error",
       confirmButtonText: "Ok" }).then((result) => 
       { if (result.isConfirmed)
         { window.location = "registro.php";
           } });
             </script>
   ';
   }

   mysqli_close($conexion);

?>
