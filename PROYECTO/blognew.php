<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New blog</title>
    <link rel="stylesheet" href="blognew.css">
    <link rel="icon" href="4-icono.ico">
</head>
<body>


    <header id="header">
        <div class="container_header">
            <div class="logo">
                <img src="4-icono.ico" alt="">
            </div>
        <div class="container_nav">
            <nav id="nav">
                <ul>
                    <li><a href="#" class="select">Inicio</a></li>
                    <li><a href="#">Perfil</a></li>
                    <li><a href="#">Contacto</a></li>
                    <li><a href="#">Otro</a></li>
                </ul>
            </nav>
            <div class="btn_menu" id="btn_menu"><i class="fa-solid fa-bars"></i></div>
        </div>
        </div>
        </header>
        

        





    
    <!-- portada del blog -->

<section class="home" id="home">

    <div class="home-text container">
                <h1 class="home-title">Blog</h1>
                <span class="home-subtitle">Actualizaciones y Noticias</span>
    </div>

</section>

      <!-- menu-filter de la parte inferior -->

<div class="menu-filter container">
  <span class="filter-item active-filter" data-filter='all'>all</span>
  <span class="filter-item" data-filter='design'>Designado</span>
  <span class="filter-item" data-filter='tech'>Tecnologico</span>
  <span class="filter-item" data-filter='mobile'>Mobile</span>
</div>


    <!-- post texto informativo del blog -->

    <section class="post">
    <?php
include 'conexion_be.php'; // Asegurar la conexión a la base de datos



$sql = "SELECT * FROM posts ORDER BY fecha_creacion DESC";


while ($row = $result->fetch_assoc()):
?>
    <div class="post-box <?= htmlspecialchars($row['categoria']) ?>">
        <img src="<?= htmlspecialchars($row['imagen_url']) ?>" alt="" class="post-img">
        <h2 class="category"><?= htmlspecialchars($row['categoria']) ?></h2>
        <a href="blog-page.html" class="post-title"><?= htmlspecialchars($row['titulo']) ?></a>
        <span class="post-date"><?= date("d M Y", strtotime($row['fecha_creacion'])) ?></span>
        <p class="post-descripcion"><?= nl2br(htmlspecialchars($row['descripcion'])) ?></p>
        <div class="profile">
            <img src="<?= htmlspecialchars($row['imagen_url']) ?>" alt="" class="profile-img">
            <p class="profile-name">Marquee</p>
        </div>
    </div>
<?php endwhile; $conn->close(); ?>



    </section>






<!-- Footer-->




 <footer>
  <div class="container_footer">
      <div class="box_footer">
          <div class="logo">
              <img src="4-icono.ico" alt="">
          </div>
          <div class="terms">
              <p><b>terminos</b><br>Uso de apps inadecuadas seran baneadas o mal uso de la pagina y hacks seran baneados permanentemente de la pagina como usuarios invitados</p>
          </div>
      </div>
      <div class="box_footer">
          <h2>Soluciones</h2>
          <a href="soporte.html">Soporte</a>
          <a href="">Blog</a>
          <a href="PAGINA DE APOYO.html">Ayuda</a>
          <a href="">App Desarrollo</a>
      </div>
      <div class="box_footer">
          <h2>Proyecto</h2>
          <a href="">Trabajos</a>
          <a href="">Manual de usuario</a>
          <a href="PAGINA DE SERVICIOS.html">Servicios</a>
      </div>
      <div class="box_footer">
          <h2>Redes Sociales</h2>
          <a href=""><i class="fa-brands fa-facebook"></i> Facebook</a>
          <a href="https://www.tiktok.com/@s_empresa34?_t=8rVo9viE5uZ&_r=1"><i class="fa-brands fa-tiktok"></i> Tiktok</a>
          <a href="https://www.youtube.com/@S-Empresa"><i class="fa-brands fa-youtube"></i> Youtube</a>
          <a href="https://github.com/brianRRF/S-EMPRESA.git"><i class="fa-brands fa-github"></i> Github</a>

      </div>

      
      <div class="box_footer" data-lenguaje="es">
          <a href=""><i class="fa-solid fa-globe"></i> ES</a>>
      </div>
      

  </div>

  <div class="box_copyr">
      <hr>
      <p>Todos los derechos reservados ©2024 
       <b>S-EMPRESA</b></p>
  </div>


</footer>










<!-- jquary cdn-->

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

<!-- link de javascript-->
<script src="blognew.js"></script>











</body>
</html>