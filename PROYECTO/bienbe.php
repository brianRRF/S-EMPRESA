

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S-EMPRESA</title>
   <link rel="stylesheet" href="empresas.css">
    <link rel="icon" href="4-icono.ico">

    <style>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap');



:root {
    --color-barra-lateral:rgb(255,255,255);

    --color-texto:rgb(0,0,0);
    --color-texto-menu:rgb(134,136,144);

    --color-menu-hover:rgb(238,238,238);
    --color-menu-hover-texto:rgb(0,0,0);

    --color-boton:rgb(0,0,0);
    --color-boton-texto:rgb(255,255,255);

    --color-linea:rgb(241,241,241);

    --color-switch-base:rgb(201,202,206);
    --color-switch-circulo:rgb(241,241,241);

    --color-scroll:rgb(0,0,0);
    --color-scroll-hover:#2196f3;

    --calor-fondo:rgb(20, 50, 222);



    /*para el uso de el perfil*/
    --color-usuario-fondo: linear-gradient(to bottom, #2196f3, #feba0b);



}
.dark-mode{
    --color-barra-lateral:rgb(44,45,49);

    --color-texto:rgb(255,255,255);
    --color-texto-menu:rgb(110,110,117);

    --color-menu-hover:rgb(0,0,0);
    --color-menu-hover-texto:rgb(238,238,238);

    --color-boton:rgb(255,255,255);
    --color-boton-texto:rgb(0,0,0);

    --color-linea:rgb(71,70,78);

    --color-switch-base:rgb(15, 122, 252);
    --color-switch-circulo:rgb(0,0,0);

    --color-scroll:rgb(255,255,255);
    --color-scroll-hover:#2196f3;
    --color-fondo:rgb(0,0,0);

    --color-usuario-fondo: #000;

}

/*aqui cambia el tamaño del padre como tal de la pagina */
* {
margin: 0;
padding: 0;
box-sizing: border-box;
font-family: "Poppins", serif;
}

body {
    height: 100vh;/*que el color se muestre en todo lo largo de la pagina web*/
    width: 100%;
    background-color: var(--color-fondo);
    opacity: initial 5;
}

/*menu responsav*/
.menu {
    position: fixed;
    width: 50px;
    height: 50px;
    font-size: 30px;
    display: none;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    cursor: pointer;
    background-color: var(--color-boton);
    color: var(--color-boton-texto);
    right: 15px;
    top: 15px;
    z-index: 100;
}

.barra-lateral {
    position: fixed;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  width: 250px;
  height: 100%;
  overflow: hidden;
  padding: 20px 15px;
  background-color: var(--color-barra-lateral);
  transition: width 0.5s ease,background-color 0.3s ease,left 0.5s ease;
z-index: 50;
}
.mini-barra-lateral {
    width: 80px;/*que el menu lateral se encoja*/
}



.barra-lateral span {
    font-size: 18px;
    text-align: left;
    width: 100px;
    white-space: nowrap;
    opacity: 1;
    transition: opacity 0.5s ease,width 0.5s ease;
}
.barra-lateral span.oculto {
    opacity: 0;
    width: 0;
}


.barra-lateral .nombre-pag {
    width: 100%;
    height: 45px;
    color: var(--color-texto);
    margin-bottom: 40px;
    display: flex;
    align-items: center;
}

.barra-lateral .nombre-pag img {
   max-width: 55px; 
   cursor: pointer;
}
.barra-lateral .nombre-pag span{
    margin-left: 5px;
    font-size: 25px;
}


.barra-lateral .boton {
   width: 100%; 
   height: 45px;
   margin-bottom: 20px;
   cursor: pointer;
   display: flex;
   align-items: center;
   border: none;
   border-radius: 10px;
   background-color: var(--color-boton);
   color: var(--color-boton-texto);
   transition: all 0.5s ease;
}
.barra-lateral .boton ion-icon {
    min-width: 50px;
    font-size: 25px;
}



.barra-lateral .navegacion{
    height: 100%;
    overflow-y: auto;
    overflow-x: hidden;
}
.barra-lateral .navegacion::-webkit-scrollbar{
    width: 5px;
}
.barra-lateral .navegacion::-webkit-scrollbar-thumb{
    background-color: var(--color-scroll);
    border-radius: 5px;
}
.barra-lateral .navegacion::-webkit-scrollbar-thumb:hover{
    background-color: var(--color-scroll-hover);
}
.barra-lateral .navegacion li {
    list-style: none;
    display: flex;
    margin-bottom: 5px;
}
.barra-lateral .navegacion a {
    width: 100%;
    height: 45px;
    display: flex;
    align-items: center;
    text-decoration: none;
    border-radius: 10px;
    color: var(--color-texto-menu);
}
.barra-lateral .navegacion a:hover {
    background-color: var(--color-menu-hover);
    color: var(--color-menu-hover-texto);
}
.barra-lateral .navegacion ion-icon {
    min-width: 50px;
    font-size: 20px;
}
.barra-lateral .linea {
    width: 100%;
    height: 2px;
    background-color: var(--color-linea);
}
.barra-lateral .modo-obscuro {
   width: 100%; 
   margin-bottom: 80px;
   border-radius: 10px;
   display: flex;
   justify-content: space-between;
}
.barra-lateral .modo-obscuro .info{
    width: 150px;
    height: 45px;
    overflow: hidden;
    display: flex;
    align-items: center;
    color: var(--color-texto-menu);
}
.barra-lateral .modo-obscuro ion-icon{
    width: 50px;
    font-size: 20px;
}

.barra-lateral .modo-obscuro .switch{
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 50px;
    height: 45px;
    cursor: pointer;
}
.barra-lateral .modo-obscuro .base{
    position: relative;
    display: flex;
    align-items: center;
    width: 35px;
    height: 20px;
    background-color: var(--color-switch-base);
    border-radius: 50px;
}
.barra-lateral .modo-obscuro .circulo{
    position: absolute;
    width: 18px;
    height: 90%;
    background-color: var(--color-switch-circulo);
    border-radius: 50%;
    left: 2px;
    transition: left 0.5s ease;
}
.barra-lateral .modo-obscuro .circulo.prendido{
    left: 15px;
}


.barra-lateral .usuario{
    width: 100%;
    display: flex;
}
.barra-lateral .usuario img{
    width: 50px;
    min-width: 50px;
    border-radius: 50%;
}
.barra-lateral .usuario .info-usuario{
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color:var(--color-texto);
    overflow: hidden;
}
.barra-lateral .usuario .nombre-imail{
    width: 100%;
    display: flex;
    flex-direction: column;
    margin-left: 5px;
}
.barra-lateral .usuario .nombre{
    font-size: 15px;
    font-weight: 600;
}
.barra-lateral .usuario .imail{
    font-size: 9px;
}
.barra-lateral .usuario ion-icon{
    font-size: 20px;
    cursor: pointer;
}





#home{
    background-color: var(--color-menu-hover);
    color: var(--color-menu-hover-texto);
}
#docu {
    background-color: var(--color-menu-hover);
    color: var(--color-menu-hover-texto);
}

main {
    margin-left: 250px;
    padding: 20px;
    transition: margin-left 0.5s ease;
}
main.min-main{
    margin-left: 80px;
}

@media (max-height: 660px) {
.barra-lateral .nombre-pag {
    margin-bottom: 5px;
}
.barra-lateral .modo-obscuro{
    margin-bottom: 3px;
}
}

@media (max-width: 600px) {
    .barra-lateral {
        position: fixed;
        left: -250px;
    }
    .max-barra-lateral{
        left: 0;
    }
    .menu{
        display: flex;
    }
    .menu ion-icon:nth-child(2){
        display: none;
    }
    main{
        margin-left: 0;
    }
    main.min-main{
        margin-left: 0;
    }
}


/*para el main de la pagina*/
.container {
    width: 95%;
    margin: 0 auto;
}

.section {
    background-color: #fff;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.section h2 {
    margin-top: 0;
}

form {
    display: flex;
    flex-direction: column;
}

input[type="text"] {
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.options {
    display: flex;
    gap: 10px;
}

.section button {
    padding: 10px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.section button:hover {
    background-color: #0056b3;
}

.section ul {
    list-style-type: none;
    padding: 0;
}

.section ul li {
    padding: 10px 0;
    border-bottom: 1px solid #ccc;
}

.section ul li:last-child {
    border-bottom: none;
}
.titulocasa a {
    display: flex;
    align-items: center; 
    font-size: 30px; 
    text-decoration: none;
    color: var(--color-texto-menu);
    transition: all 0.3s ease;
}
.titulocasa a:hover {
    color: var(--color-menu-hover-texto);
}

.titulocasa ion-icon {
    font-size: 30px; 
    margin-right: 10px; 
}

/*para el perfil de usuario*/
.main_bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    background: var(--color-usuario-fondo);
    background-position: left;
    z-index: -1;
    filter: blur(10px);
}
    </style>
</head>
<body>
<div class="menu">
<ion-icon name="reorder-four-outline"></ion-icon>
<ion-icon name="close-outline"></ion-icon>
</div>
<div class="barra-lateral">
    <div>
    <div class="nombre-pag">
        <img src="4-icono.ico" id="cloud">
        <span>S-EMPRESA</span>
    </div>
    <button class="boton">
        <ion-icon name="add-outline"></ion-icon>
        <span>Nuevo espacio</span>
    </button>
    </div>
    <nav class="navegacion">
       <ul>
        <li><a id="home" href="#"><ion-icon name="home-outline"></ion-icon>
        <span>Inicio</span></a></li>
        <li><a href="#"><ion-icon name="star-outline"></ion-icon>
        <span>Calificacion</span></a></li>
        <li><a href="#"><ion-icon name="business-outline"></ion-icon>
        <span>Trabajos</span></a></li>
        <li><a href="#"><ion-icon name="send-outline"></ion-icon>
        <span>Chat</span></a></li>
        <li><a id="docu" href="documentos.php"><ion-icon name="newspaper-outline"></ion-icon>
        <span>Documentos</span></a></li>
        <li><a href="#"><ion-icon name="planet-outline"></ion-icon>
        <span>Espacios</span></a></li>
        <li><a href="#"><ion-icon name="trash-bin-outline"></ion-icon>
        <span>Eliminados</span></a></li>
       </ul> 
    </nav>

    <div>
    <div class="linea"></div>
    <div class="modo-obscuro">
        <div class="info">
            <ion-icon name="moon-outline"></ion-icon>
            <span>obscuro</span>
        </div>
        <div class="switch">
            <div class="base">
                <div class="circulo">

                </div>
            </div>
        </div>
    </div>
<div class="usuario">
    <img src="<?php echo $foto_perfil; ?>" alt="">
    <div class="info-usuario">
        <div class="nombre-imail">
            <span class="nombre"><?php echo htmlspecialchars($nombre_c);?></span>
            <span class="imail"><?php echo htmlspecialchars($correo_elec);?></span>
        </div>
        <a href="perfil.php"><ion-icon name="ellipsis-vertical-outline"></ion-icon></a>
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
                                <span class="label-medium label">Empresas</span>

                                <select name="want-to" class="search-item-field body-medium">
                                    <option value="buy" selected>TODO</option>
                                    <option value="sell" >Micro empresas</option> 
                                    <option value="rent" >Empresas pequeñas</option>
                                    <option value="rent" >Empresas</option> 
                                    <option value="rent" >Empresas grandes</option> 
                                </select>


                                <span class="material-symbols-rounded" aria-hidden="true">real_estate_agent</span>
                            </label>

                            <label class="search-item">
                                <span class="label-medium label">Tipo de empresas</span>

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
                                <span class="label-medium label">Buscar</span>

                                <input type="text" name="location" placeholder="Nombre, tipo, Empresa..." class="search-item-field body-medium">


                                <span class="material-symbols-rounded" aria-hidden="true">location_on</span>
                            </label>

                            <button type="submit" class="search-btn">
                                <span class="material-symbols-rounded" aria-hidden="true">search</span>

                                <span class="label-medium">Buscar</span>
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

            <a href="registroempresa.html" class="btn btn-outline">
                <span class="label-medium">Explorar mas</span>

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


<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="bienvenido.js"></script>
</body>
</html>