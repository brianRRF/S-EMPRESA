<?php 
session_start();
include 'conexion_be.php'; // Asegurar la conexión a la base de datos

// Verificar si la cookie 'usuario' está establecida y restaurar sesión si es necesario
if (isset($_COOKIE['usuario']) && !isset($_SESSION['usuario'])) {
    $_SESSION['usuario'] = $_COOKIE['usuario'];
    $_SESSION['nombre_usuario'] = $_COOKIE['nombre_usuario'];
    $_SESSION['id_cargo'] = $_COOKIE['id_cargo'];
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header("location: inicio.php");
    exit();
}

// Obtener datos del usuario
$correo_elec = $_SESSION['usuario'];
$nombre_c = $_SESSION['nombre_usuario'];

// Consultar la foto de perfil desde la base de datos
$query = "SELECT foto_perfil FROM usuario WHERE correo_elec='$correo_elec'";
$resultado = mysqli_query($conexion, $query);
$row = mysqli_fetch_assoc($resultado);

// Usar la URL pública de la imagen desde la base de datos o una imagen predeterminada en ImgBB
$foto_perfil = $row['foto_perfil'] ?? 'https://i.ibb.co/Wpcn6Dq9/R.png'; // Imagen predeterminada alojada en ImgBB
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    
    <link rel="stylesheet" href="empresas.css">
    <link rel="stylesheet" href="elperron.css">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0..1,0" />
    
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
                    <li class="menu-item menu-item-static active">
                        <a href="#" class="menu-link">
                        <i class='bx bx-home-alt-2' ></i>
                            <span>Casa</span>
                        </a>
                    </li>
        
                    <li class="menu-item menu-item-static">
                        <a href="#" class="menu-link">
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
                            <li><a href="" class="sub-menu-link">Todo</a></li>
                            <li><a href="" class="sub-menu-link">Publicidad</a></li>
                            <li><a href="" class="sub-menu-link">Empredimiento</a></li>
                        </ul>
                    </li>
        
                    <li class="menu-item menu-item-static">
                        <a href="perfiles.php" class="menu-link">
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
                    <a href="desarrollador.php" class="menu-link">
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
                    <img src="<?php echo $foto_perfil; ?>" alt="user">
                </div>
                <div class="user-data">
                    <span class="name"><?php echo htmlspecialchars($nombre_c);?></span>
                    <span class="email"><?php echo htmlspecialchars($correo_elec);?></span>
                </div>
                <div class="user-icon">
                    <i class='bx bx-exit' ></i>
                </div>
            </div>
        </div>
        
    </div>


    <main>

    <div class="cover">


<div class="bg_color"></div>
<div class="weve w1"></div>
<div class="weve w2"></div>

<div class="container_cover">
<div class="container_info">
    <h1 id="ser">SERVI</h1>
    <h2>EMPRESA</h2>
    <!--<p id="text-content">Promoción de la Empresa: Cómo resaltar las características únicas y los valores de tu empresa.
        Calidad de los Servicios: La importancia de proporcionar servicios de alta calidad y cómo esto beneficia a tus clientes.</p>
-->


                        <form action="./" method="get" class="search-bar">
                            <label class="search-item">
                                <span class="label-medium label">Want to</span>

                                <select name="want-to" class="search-item-field body-medium">
                                    <option value="buy" selected>Buy</option>
                                    <option value="sell" >Sell</option> 
                                    <option value="rent" >Rent</option> 
                                </select>


                                <span class="material-symbols-rounded" aria-hidden="true">real_estate_agent</span>
                            </label>

                            <label class="search-item">
                                <span class="label-medium label">Property type</span>

                                <select name="property-type" class="search-item-field body-medium">
                                    <option value="any" selected>Any</option>
                                    <option value="houses" >Houses</option> 
                                    <option value="apartments" >Apartments</option> 
                                    <option value="villa" >Villa</option> 
                                    <option value="townhome" >Townhome</option> 
                                    <option value="bungalow" >Bungalow</option> 
                                    <option value="loft" >Loft</option>
                                </select>


                                <span class="material-symbols-rounded" aria-hidden="true">gite</span>
                            </label>


                            <label class="search-item">
                                <span class="label-medium label">Location</span>

                                <input type="text" name="location" placeholder="Street, City, Zip..." class="search-item-field body-medium">


                                <span class="material-symbols-rounded" aria-hidden="true">location_on</span>
                            </label>

                            <button type="submit" class="search-btn">
                                <span class="material-symbols-rounded" aria-hidden="true">search</span>

                                <span class="label-medium">Search</span>
                            </button>



                        </form>





                    
</div>
<div class="container_vector">
    <img src="Removal-homal.png" alt="">
</div>
</div>
</div>


<section class="section property" aria-labelledby="property-label">
    <div class="container">

        <div class="title-wrapper">
            <div>
                <h2 class="section-title headline-small">Empresas, Negocios y mas...</h2>

                <p class="section-text body-large">
                Promoción de la Empresa: Cómo resaltar las características únicas y los valores de tu empresa.
                </p>
            </div>

            <a href="#" class="btn btn-outline">
                <span class="label-medium">Explore more</span>

                <span class="material-symbols-rounded" aria-hidden="true">arrow_outward</span>
            </a>






        </div>

        <div class="property-list">
            <div class="card">
                <div class="card-banner">

                    <figure class="img-holder" style="--width: 585; --heigth: 390;">
                        <img src="imagenes/empresario.png" width="585" height="390" alt="COVA Home Realty" class="img-cover">
                    </figure>

                    <span class="badge label-medium">New</span>

                    <button class="icon-btn fav-btn" aria-label="add to favorite" data-toggle-btn>
                        <span class="material-symbols-rounded" aria-hidden="true">favorite</span>
                    </button>


                </div>

                <div class="card-content">
                    <span class="title-large">Tecnologica</span>

                    <h3>
                        <a href="#" class="title-small card-title">s-empresa</a>
                    </h3>

                    <address class="body-medium card-text">
                    Cómo resaltar las características únicas y los valores de tu empresa.
                    </address>

                    <div class="card-meta-list">
                        <div class="meta-item">

                            <span class="material-symbols-rounded meta-icon" aria-hidden="true">supervisor_account</span>

                            <span class="meta-text label-medium">Supervisado</span>
                        </div>

                        <div class="meta-item">

                            <span class="material-symbols-rounded meta-icon" aria-hidden="true">calendar_month</span>

                            <span class="meta-text label-medium">2 años</span>
                        </div>

                        <div class="meta-item">

                            <span class="material-symbols-rounded meta-icon" aria-hidden="true">check</span>

                            <span class="meta-text label-medium">Verificado</span>
                        </div>




                    </div>



                </div>




            </div>

            <div class="card">
                <div class="card-banner">

                    <figure class="img-holder" style="--width: 585; --heigth: 390;">
                        <img src="imagenes/empresario.png" width="585" height="390" alt="COVA Home Realty" class="img-cover">
                    </figure>

                    <span class="badge label-medium">New</span>

                    <button class="icon-btn fav-btn" aria-label="add to favorite" data-toggle-btn>
                        <span class="material-symbols-rounded" aria-hidden="true">favorite</span>
                    </button>


                </div>

                <div class="card-content">
                    <span class="title-large">Tecnologica</span>

                    <h3>
                        <a href="#" class="title-small card-title">s-empresa</a>
                    </h3>

                    <address class="body-medium card-text">
                    Cómo resaltar las características únicas y los valores de tu empresa.
                    </address>

                    <div class="card-meta-list">
                        <div class="meta-item">

                            <span class="material-symbols-rounded meta-icon" aria-hidden="true">supervisor_account</span>

                            <span class="meta-text label-medium">Supervisado</span>
                        </div>

                        <div class="meta-item">

                            <span class="material-symbols-rounded meta-icon" aria-hidden="true">calendar_month</span>

                            <span class="meta-text label-medium">2 años</span>
                        </div>

                        <div class="meta-item">

                            <span class="material-symbols-rounded meta-icon" aria-hidden="true">check</span>

                            <span class="meta-text label-medium">Verificado</span>
                        </div>




                    </div>



                </div>




            </div>

            <div class="card">
                <div class="card-banner">

                    <figure class="img-holder" style="--width: 585; --heigth: 390;">
                        <img src="imagenes/empresario.png" width="585" height="390" alt="COVA Home Realty" class="img-cover">
                    </figure>

                    <span class="badge label-medium">New</span>

                    <button class="icon-btn fav-btn" aria-label="add to favorite" data-toggle-btn>
                        <span class="material-symbols-rounded" aria-hidden="true">favorite</span>
                    </button>


                </div>

                <div class="card-content">
                    <span class="title-large">Tecnologica</span>

                    <h3>
                        <a href="#" class="title-small card-title">s-empresa</a>
                    </h3>

                    <address class="body-medium card-text">
                    Cómo resaltar las características únicas y los valores de tu empresa.
                    </address>

                    <div class="card-meta-list">
                        <div class="meta-item">

                            <span class="material-symbols-rounded meta-icon" aria-hidden="true">supervisor_account</span>

                            <span class="meta-text label-medium">Supervisado</span>
                        </div>

                        <div class="meta-item">

                            <span class="material-symbols-rounded meta-icon" aria-hidden="true">calendar_month</span>

                            <span class="meta-text label-medium">2 años</span>
                        </div>

                        <div class="meta-item">

                            <span class="material-symbols-rounded meta-icon" aria-hidden="true">check</span>

                            <span class="meta-text label-medium">Verificado</span>
                        </div>




                    </div>



                </div>




            </div>

            <div class="card">
                <div class="card-banner">

                    <figure class="img-holder" style="--width: 585; --heigth: 390;">
                        <img src="imagenes/empresario.png" width="585" height="390" alt="COVA Home Realty" class="img-cover">
                    </figure>

                    

                    <button class="icon-btn fav-btn" aria-label="add to favorite" data-toggle-btn>
                        <span class="material-symbols-rounded" aria-hidden="true">favorite</span>
                    </button>


                </div>

                <div class="card-content">
                    <span class="title-large">Tecnologica</span>

                    <h3>
                        <a href="#" class="title-small card-title">s-empresa</a>
                    </h3>

                    <address class="body-medium card-text">
                    Cómo resaltar las características únicas y los valores de tu empresa.
                    </address>

                    <div class="card-meta-list">
                        <div class="meta-item">

                            <span class="material-symbols-rounded meta-icon" aria-hidden="true">supervisor_account</span>

                            <span class="meta-text label-medium">Supervisado</span>
                        </div>

                        <div class="meta-item">

                            <span class="material-symbols-rounded meta-icon" aria-hidden="true">calendar_month</span>

                            <span class="meta-text label-medium">2 años</span>
                        </div>

                        <div class="meta-item">

                            <span class="material-symbols-rounded meta-icon" aria-hidden="true">check</span>

                            <span class="meta-text label-medium">Verificado</span>
                        </div>




                    </div>



                </div>




            </div>

            <div class="card">
                <div class="card-banner">

                    <figure class="img-holder" style="--width: 585; --heigth: 390;">
                        <img src="imagenes/empresario.png" width="585" height="390" alt="COVA Home Realty" class="img-cover">
                    </figure>

                    

                    <button class="icon-btn fav-btn" aria-label="add to favorite" data-toggle-btn>
                        <span class="material-symbols-rounded" aria-hidden="true">favorite</span>
                    </button>


                </div>

                <div class="card-content">
                    <span class="title-large">Tecnologica</span>

                    <h3>
                        <a href="#" class="title-small card-title">s-empresa</a>
                    </h3>

                    <address class="body-medium card-text">
                    Cómo resaltar las características únicas y los valores de tu empresa.
                    </address>

                    <div class="card-meta-list">
                        <div class="meta-item">

                            <span class="material-symbols-rounded meta-icon" aria-hidden="true">supervisor_account</span>

                            <span class="meta-text label-medium">Supervisado</span>
                        </div>

                        <div class="meta-item">

                            <span class="material-symbols-rounded meta-icon" aria-hidden="true">calendar_month</span>

                            <span class="meta-text label-medium">2 años</span>
                        </div>

                        <div class="meta-item">

                            <span class="material-symbols-rounded meta-icon" aria-hidden="true">check</span>

                            <span class="meta-text label-medium">Verificado</span>
                        </div>




                    </div>



                </div>




            </div>

            <div class="card">
                <div class="card-banner">

                    <figure class="img-holder" style="--width: 585; --heigth: 390;">
                        <img src="imagenes/empresario.png" width="585" height="390" alt="COVA Home Realty" class="img-cover">
                    </figure>

                    

                    <button class="icon-btn fav-btn" aria-label="add to favorite" data-toggle-btn>
                        <span class="material-symbols-rounded" aria-hidden="true">favorite</span>
                    </button>


                </div>

                <div class="card-content">
                    <span class="title-large">Tecnologica</span>

                    <h3>
                        <a href="#" class="title-small card-title">s-empresa</a>
                    </h3>

                    <address class="body-medium card-text">
                    Cómo resaltar las características únicas y los valores de tu empresa.
                    </address>

                    <div class="card-meta-list">
                        <div class="meta-item">

                            <span class="material-symbols-rounded meta-icon" aria-hidden="true">supervisor_account</span>

                            <span class="meta-text label-medium">Supervisado</span>
                        </div>

                        <div class="meta-item">

                            <span class="material-symbols-rounded meta-icon" aria-hidden="true">calendar_month</span>

                            <span class="meta-text label-medium">2 años</span>
                        </div>

                        <div class="meta-item">

                            <span class="material-symbols-rounded meta-icon" aria-hidden="true">check</span>

                            <span class="meta-text label-medium">Verificado</span>
                        </div>




                    </div>



                </div>




            </div>

            <div class="card">
                <div class="card-banner">

                    <figure class="img-holder" style="--width: 585; --heigth: 390;">
                        <img src="imagenes/empresario.png" width="585" height="390" alt="COVA Home Realty" class="img-cover">
                    </figure>

                    

                    <button class="icon-btn fav-btn" aria-label="add to favorite" data-toggle-btn>
                        <span class="material-symbols-rounded" aria-hidden="true">favorite</span>
                    </button>


                </div>

                <div class="card-content">
                    <span class="title-large">Tecnologica</span>

                    <h3>
                        <a href="#" class="title-small card-title">s-empresa</a>
                    </h3>

                    <address class="body-medium card-text">
                    Cómo resaltar las características únicas y los valores de tu empresa.
                    </address>

                    <div class="card-meta-list">
                        <div class="meta-item">

                            <span class="material-symbols-rounded meta-icon" aria-hidden="true">supervisor_account</span>

                            <span class="meta-text label-medium">Supervisado</span>
                        </div>

                        <div class="meta-item">

                            <span class="material-symbols-rounded meta-icon" aria-hidden="true">calendar_month</span>

                            <span class="meta-text label-medium">2 años</span>
                        </div>

                        <div class="meta-item">

                            <span class="material-symbols-rounded meta-icon" aria-hidden="true">check</span>

                            <span class="meta-text label-medium">Verificado</span>
                        </div>




                    </div>



                </div>




            </div>

            <div class="card">
                <div class="card-banner">

                    <figure class="img-holder" style="--width: 585; --heigth: 390;">
                        <img src="imagenes/empresario.png" width="585" height="390" alt="COVA Home Realty" class="img-cover">
                    </figure>

                    

                    <button class="icon-btn fav-btn" aria-label="add to favorite" data-toggle-btn>
                        <span class="material-symbols-rounded" aria-hidden="true">favorite</span>
                    </button>


                </div>

                <div class="card-content">
                    <span class="title-large">Tecnologica</span>

                    <h3>
                        <a href="#" class="title-small card-title">s-empresa</a>
                    </h3>

                    <address class="body-medium card-text">
                    Cómo resaltar las características únicas y los valores de tu empresa.
                    </address>

                    <div class="card-meta-list">
                        <div class="meta-item">

                            <span class="material-symbols-rounded meta-icon" aria-hidden="true">supervisor_account</span>

                            <span class="meta-text label-medium">Supervisado</span>
                        </div>

                        <div class="meta-item">

                            <span class="material-symbols-rounded meta-icon" aria-hidden="true">calendar_month</span>

                            <span class="meta-text label-medium">2 años</span>
                        </div>

                        <div class="meta-item">

                            <span class="material-symbols-rounded meta-icon" aria-hidden="true">check</span>

                            <span class="meta-text label-medium">Verificado</span>
                        </div>




                    </div>



                </div>




            </div>


        </div>



    </div>


</section>


    </main>

    
<script>
    


const $toggleBtns = document.querySelectorAll("[data-toggle-btn]");

$toggleBtns.forEach($toggleBtn => {
    $toggleBtn.addEventListener("click", () => {
        $toggleBtn.classList.toggle("active");
    });
});
</script>

<script src="bienvenido.js"></script>


<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>