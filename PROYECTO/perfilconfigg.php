<?php
session_start();
include 'conexion_be.php';

if (!isset($_SESSION['usuario'])) {
    echo '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    Swal.fire({
        title: "¡INICIA SESIÓN!",
        text: "Inicia sesión o regístrate si aún no lo has hecho.",
        icon: "error",
        confirmButtonText: "Ok"
    }).then(() => { window.location = "inicio.php"; });
    </script>';
    session_destroy();
    die();
}

$correo_elec = $_SESSION['usuario'];
$query = "SELECT * FROM usuario WHERE correo_elec='$correo_elec'";
$result = mysqli_query($conexion, $query);
$user = mysqli_fetch_assoc($result);
$foto_perfil = $user['foto_perfil'] ?? 'https://i.ibb.co/YdR6Rng/R.png';

mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Perfil de Usuario</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Leaflet (mapa) -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
  <!-- Ionicons -->
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <!-- face-api.js -->
  <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body { background: #f6f8fa; }
    .main-content { padding: 2.2rem 1.1rem; }
    .layout-flex { display: flex; gap: 2.5rem; align-items: flex-start; }
    .left-column {
      flex: 0 0 290px;
      max-width: 330px;
      width: 100%;
      display: flex;
      flex-direction: column;
      align-items: stretch;
    }
    .right-column { flex: 1 1 0%; width: 100%; }
    .perfil-card {
      background: #fff;
      box-shadow: 0 1px 6px 0 rgba(36,36,36,0.09);
      border-radius: 1.1rem;
      padding: 1.7rem 1.2rem 1.2rem 1.2rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-bottom: 1.2rem;
      position: relative;
      overflow: hidden;
    }
    .perfil-card .avatar-ctn {
      position: relative;
      margin-bottom: 1rem;
    }
    .perfil-card .avatar-img {
      width: 90px;
      height: 90px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #6366f1;
      background: #e0e7ff;
      box-shadow: 0 1.5px 8px rgba(79,70,229,0.06);
    }
    .perfil-card .edit-avatar-btn {
      position: absolute;
      bottom: 0;
      right: 0;
      background: #fff;
      border-radius: 50%;
      border: 1.5px solid #6366f1;
      color: #6366f1;
      font-size: 1.15rem;
      width: 32px;
      height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      box-shadow: 0 1px 4px rgba(79,70,229,0.07);
      transition: background 0.12s, color 0.12s;
      z-index: 2;
    }
    .perfil-card .edit-avatar-btn:hover {
      background: #e0e7ff;
      color: #4f46e5;
    }
    .perfil-card .usuario {
      font-weight: 700;
      color: #383ab1;
      margin-bottom: 0.3rem;
      font-size: 1.2rem;
      letter-spacing: 0.4px;
      word-break: break-all;
    }
    .perfil-card .perfil-email {
      color: #555;
      font-size: 1rem;
      margin-bottom: 0.6rem;
    }
    .perfil-card .badge {
      background: #e0e7ff;
      color: #4f46e5;
      font-weight: 600;
      font-size: 0.85rem;
      border-radius: 0.5rem;
      padding: 0.3em 0.9em;
      margin-bottom: 0.8rem;
    }
    .perfil-card .bio {
      color: #555;
      font-size: 0.98rem;
      text-align: center;
      margin-bottom: 1rem;
    }
    .facial-ctn {
      background: linear-gradient(140deg, #e0e7ff 65%, #fff 100%);
      border: 2px solid #4f46e5;
      box-shadow: 0 3px 14px 0 rgba(79,70,229,0.09);
      border-radius: 1.1rem;
      margin-top: 1.2rem;
      padding: 1.7rem 1.2rem 1.2rem 1.2rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      position: relative;
      overflow: hidden;
    }
    .facial-ctn .circle {
      position: absolute;
      left: -35px;
      top: -35px;
      width: 70px;
      height: 70px;
      background: #6366f1;
      opacity: 0.13;
      border-radius: 50%;
      z-index: 0;
    }
    .facial-ctn .main-content-ctn {
      position: relative;
      z-index: 1;
      width: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .facial-ctn .main-icon {
      font-size: 2.6rem;
      color: #4f46e5;
      background: #e0e7ff;
      border-radius: 50%;
      padding: 12px;
      margin-bottom: 0.7rem;
      box-shadow: 0 1px 6px 0 rgba(36,36,36,0.07);
      border: 1.5px solid #6366f120;
      display: inline-block;
    }
    .facial-ctn h4 {
      font-weight: 700;
      color: #383ab1;
      margin-bottom: 0.45rem;
      text-align: center;
    }
    .facial-ctn p {
      color: #555;
      margin-bottom: 1.2rem;
      font-size: 0.99rem;
      text-align: center;
    }
    .facial-ctn .btn-primary {
      background: linear-gradient(90deg, #6366f1 70%, #4f46e5 100%);
      border: none;
      font-weight: 600;
      box-shadow: 0 2px 6px rgba(79,70,229,0.12);
      transition: background 0.2s, box-shadow 0.2s;
      letter-spacing: 0.2px;
      font-size: 1.04rem;
    }
    .facial-ctn .btn-primary:hover {
      background: linear-gradient(90deg, #4f46e5 70%, #6366f1 100%);
      box-shadow: 0 5px 15px rgba(79,70,229,0.13);
    }
    .section-title {
      display: flex;
      align-items: center;
      gap: 0.7rem;
      font-size: 1.22rem;
      font-weight: 700;
      color: #383ab1;
      margin-bottom: 1.2rem;
      letter-spacing: 0.3px;
      background: #e0e7ff;
      padding: 0.6rem 1.1rem;
      border-radius: 0.9rem;
      width: fit-content;
      box-shadow: 0 0.5px 2.5px 0 rgba(36,36,36,0.06);
    }
    .section-title ion-icon {
      color: #4f46e5;
      font-size: 1.5rem;
      vertical-align: bottom;
    }
    .perfil-form-card {
      background: #fff;
      border-radius: 1.1rem;
      box-shadow: 0 1px 6px 0 rgba(36,36,36,0.09);
      padding: 1.7rem 1.2rem 1.2rem 1.2rem;
      margin-bottom: 1.4rem;
    }
    .themes-card {
      background: #fff;
      border-radius: 1.1rem;
      box-shadow: 0 1px 6px 0 rgba(36,36,36,0.09);
      padding: 1.7rem 1.2rem 1.6rem 1.2rem;
      margin-bottom: 1.4rem;
      margin-top: 2rem;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }
    .themes-title {
      font-size: 1.19rem;
      font-weight: 700;
      color: #383ab1;
      margin-bottom: 1.2rem;
      letter-spacing: 0.3px;
      background: #e0e7ff;
      padding: 0.6rem 1.1rem;
      border-radius: 0.9rem;
      box-shadow: 0 0.5px 2.5px 0 rgba(36,36,36,0.06);
      display: flex;
      align-items: center;
      gap: 0.6em;
    }
    .themes-examples {
      display: flex;
      flex-wrap: wrap;
      gap: 1.2rem;
      margin-bottom: 1.2rem;
      width: 100%;
    }
    .theme-box {
      border-radius: 0.7rem;
      border: 2px solid #e0e7ff;
      background: #f0f3fd;
      width: 140px;
      padding: 0.7rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      transition: border 0.2s;
      cursor: not-allowed;
      position: relative;
      opacity: 0.85;
      user-select: none;
    }
    .theme-box.selected, .theme-box:active, .theme-box:hover {
      border: 2px solid #6366f1;
      opacity: 1;
      background: #e0e7ff;
    }
    .theme-name {
      font-size: 1.08rem;
      font-weight: 600;
      color: #4139a5;
      margin-top: 0.5em;
      margin-bottom: 0.25em;
      text-align: center;
    }
    .theme-preview {
      width: 100%;
      height: 28px;
      border-radius: 0.6em;
      margin-bottom: 0.2em;
    }
    .theme-default { background: linear-gradient(90deg,#f6f8fa 70%,#e0e7ff 100%); border: 1.5px solid #e0e7ff; }
    .theme-dark { background: linear-gradient(90deg,#23272e 70%,#595a6b 100%); border: 1.5px solid #333; }
    .theme-modern { background: linear-gradient(90deg,#fff 60%,#00d0ff 100%); border: 1.5px solid #00d0ff; }
    .theme-minimal { background: #fff; border: 1.5px dashed #aaa; }
    .theme-pastel { background: linear-gradient(90deg,#ffe2f2 60%,#bde0fe 100%); border: 1.5px solid #ffd6e0; }
    .theme-contrast { background: linear-gradient(90deg,#000 70%,#fff 100%); border: 1.5px solid #000; }
    .theme-disabled {
      pointer-events: none;
      opacity: 0.6;
    }
    .form-text {
      color: #757575;
      font-size: 0.96em;
    }
    .btn-map {
      margin-left: 8px;
      display: inline-flex;
      align-items: center;
      gap: 0.2em;
      font-size: 1rem;
      background: #e0e7ff;
      color: #4139a5;
      border-radius: 0.6em;
      padding: 0.35em 0.95em;
      border: 1px solid #6366f1;
      transition: background 0.12s, color 0.12s;
      box-shadow: 0 0.5px 2.5px 0 rgba(36,36,36,0.07);
    }
    .btn-map ion-icon {
      font-size: 1.15em;
      vertical-align: middle;
      margin-right: 0.18em;
    }
    .btn-map:hover, .btn-map:focus {
      background: #6366f1;
      color: #fff;
      border-color: #6366f1;
      text-decoration: none;
    }
    .phone-input-group {
      display: flex;
      gap: 0.5rem;
      align-items: center;
    }
    .phone-country-select {
      min-width: 105px;
      font-weight: 600;
      font-size: 1rem;
      background: #e0e7ff;
      color: #4f46e5;
      border: 1.5px solid #d1d5fa;
    }
    .phone-prefix {
      min-width: 54px;
      border-radius: 0.3rem;
      background: #e0e7ff;
      border: 1px solid #d1d5fa;
      color: #4f46e5;
      font-weight: 600;
      text-align: center;
      height: 38px;
      padding: 0.375rem 0.7rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.07rem;
    }
    .input-icon-group {
      position: relative;
      width: 100%;
      display: flex;
      align-items: center;
    }
    .input-icon {
      position: absolute;
      left: 10px;
      color: #6366f1;
      font-size: 1.25em;
      pointer-events: none;
    }
    .phone-number-input {
      padding-left: 2.3em;
    }
    #map {
      width: 100%;
      height: 350px;
      border-radius: 1rem;
      z-index: 1;
      margin-bottom: 12px;
    }
    @media (max-width: 1100px) {
      .left-column { flex-basis: 240px; max-width: 100%; }
    }
    @media (max-width: 992px) {
      .layout-flex { flex-direction: column; gap: 1.5rem; }
      .left-column, .right-column { max-width: 100%; }
      .themes-examples { gap: 0.6rem; }
      .theme-box { width: 110px; }
    }
    @media (max-width: 600px) {
      .themes-examples { flex-direction: column; }
      .phone-input-group { flex-direction: column; gap: 0.2rem; align-items: stretch; }
      .input-icon { left: 12px; }
      .phone-country-select { min-width: 90px; }
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <main class="main-content">
      <div class="layout-flex mb-4">
        <div class="left-column">
          <div class="perfil-card">
            <div class="avatar-ctn">
              <img id="avatarImg" class="avatar-img" src="<?php echo htmlspecialchars($foto_perfil); ?>" alt="Avatar usuario">
              <button class="edit-avatar-btn" type="button" onclick="abrirModalAvatar()" title="Cambiar avatar">
                <ion-icon name="camera-outline"></ion-icon>
              </button>
            </div>
            <div class="usuario" id="usuarioPerfil"><?php echo htmlspecialchars($user['nombre_c']); ?></div>
            <div class="perfil-email" id="emailPerfil"><?php echo htmlspecialchars($user['correo_elec']); ?></div>
            <div class="badge">Miembro S</div>
            <div class="bio" id="bioPerfil"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></div>
          </div>
          <div class="facial-ctn" id="facialCtn">
            <!-- El contenido se genera dinámicamente por JS -->
          </div>
        </div>
        <div class="right-column" id="seccionDerecha">
          <div id="formPerfilSeccion">
            <div class="section-title">
              <ion-icon name="person-circle-outline"></ion-icon>
              Editar Perfil de Usuario
            </div>
            <form class="perfil-form-card" id="formPerfil" autocomplete="off">
              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label for="nombreCompleto" class="form-label">Nombre completo</label>
                  <input type="text" class="form-control" id="nombreCompleto" name="nombreCompleto" value="<?php echo htmlspecialchars($user['nombre']); ?>">
                </div>
                <div class="col-md-3">
                  <label for="sexo" class="form-label">Sexo</label>
                  <select class="form-select" id="sexo" name="sexo">
                    <option value="" <?php if (!($user['sexo'] ?? '')) echo 'selected'; ?>>Selecciona...</option>
                    <option value="masculino" <?php if (($user['sexo'] ?? '') == 'masculino') echo 'selected'; ?>>Masculino</option>
                    <option value="femenino" <?php if (($user['sexo'] ?? '') == 'femenino') echo 'selected'; ?>>Femenino</option>
                    <option value="otro" <?php if (($user['sexo'] ?? '') == 'otro') echo 'selected'; ?>>Otro</option>
                    <option value="sin especificar" <?php if (($user['sexo'] ?? '') == 'sin especificar') echo 'selected'; ?>>Prefiero no decirlo</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label for="fechaNacimiento" class="form-label">Fecha de nacimiento</label>
                  <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" max="2025-12-31" value="<?php echo htmlspecialchars($user['fecha_nacimiento']); ?>">
                </div>
              </div>
              <div class="row g-3 mb-3">
                <div class="col-md-3">
                  <label for="edad" class="form-label">Edad</label>
                  <input type="number" class="form-control" id="edad" name="edad" value="<?php echo htmlspecialchars($user['edad'] ?? ''); ?>" min="0" max="120" inputmode="numeric" autocomplete="off">
                </div>
              </div>
              <div class="mb-3">
                <label for="bio" class="form-label">Biografía</label>
                <textarea class="form-control" id="bio" name="bio" rows="2" maxlength="120" placeholder="Máximo 120 letras"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                <span class="word-counter" id="bioCounter">0 / 120 letras</span>
              </div>
              <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <div class="phone-input-group align-items-center">
                  <select id="country" class="form-select phone-country-select" style="max-width:120px;">
                    <option value="+52" selected>🇲🇽 +52 MX</option>
                    <option value="+1">🇺🇸 +1 US</option>
                    <option value="+34">🇪🇸 +34 ES</option>
                    <option value="+57">🇨🇴 +57 CO</option>
                    <option value="+54">🇦🇷 +54 AR</option>
                    <option value="+51">🇵🇪 +51 PE</option>
                    <option value="+56">🇨🇱 +56 CL</option>
                  </select>
                  <span class="phone-prefix" id="phonePrefix">+52</span>
                  <div class="input-icon-group flex-grow-1">
                    <span class="input-icon"><ion-icon name="call-outline"></ion-icon></span>
                    <input type="text" class="form-control phone-number-input" id="telefono" name="telefono" maxlength="12" placeholder="000-000-0000" value="<?php echo htmlspecialchars($user['telefono'] ?? ''); ?>">
                  </div>
                </div>
                <div class="form-text">Solo números, formato: 000-000-0000</div>
              </div>
              <div class="mb-3">
                <label for="localidad" class="form-label">Localidad</label>
                <div class="input-group">
                  <input type="text" class="form-control" id="localidad" name="localidad" placeholder="Escribe tu localidad o selecciónala en el mapa" value="<?php echo htmlspecialchars($user['localidad'] ?? ''); ?>">
                  <button type="button" class="btn btn-map" onclick="abrirMapa()">
                    <ion-icon name="map-outline"></ion-icon>Elegir en mapa
                  </button>
                </div>
                <div class="form-text">Puedes escribirla o seleccionarla en el mapa.</div>
              </div>
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="password" class="form-label">Nueva contraseña</label>
                  <input type="password" class="form-control" id="password" placeholder="••••••••">
                </div>
                <div class="col-md-6">
                  <label for="password2" class="form-label">Repetir contraseña</label>
                  <input type="password" class="form-control" id="password2" placeholder="••••••••">
                </div>
              </div>
              <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary px-4">Guardar cambios</button>
              </div>
            </form>
            <div class="themes-card">
              <div class="themes-title">
                <ion-icon name="color-palette-outline"></ion-icon>
                Selecciona un tema o diseño
              </div>
              <div class="themes-examples" id="themesExamples"></div>
              <div class="form-text">
                Próximamente podrás personalizar el diseño de tu dashboard.
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- MODAL AVATAR -->
    <div class="modal fade" id="modalAvatar" tabindex="-1" aria-labelledby="modalAvatarLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <form id="formAvatar" class="modal-content" enctype="multipart/form-data" method="POST" action="actualizar_avatar.php">
          <div class="modal-header">
            <h5 class="modal-title" id="modalAvatarLabel">Cambiar avatar</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <div class="mb-4 text-center">
              <img id="previewAvatar" src="<?php echo htmlspecialchars($foto_perfil); ?>" class="rounded-circle" style="width: 90px; height: 90px;object-fit:cover;">
            </div>
            <div class="mb-3">
              <label for="avatarInput" class="form-label">Selecciona una nueva imagen</label>
              <input class="form-control" type="file" accept="image/*" id="avatarInput" name="foto_perfil">
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Actualizar avatar</button>
          </div>
        </form>
      </div>
    </div>

    <!-- MODAL MAPA -->
    <div class="modal fade" id="modalMapa" tabindex="-1" aria-labelledby="modalMapaLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalMapaLabel">Seleccionar localidad en el mapa</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <div id="map" style="height:350px; width:100%;"></div>
            <div id="direccionSeleccionada" class="mt-3"></div>
          </div>
          <div class="modal-footer">
            <button type="button" id="confirmarLocalidad" class="btn btn-success" disabled>Usar esta localidad</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="perfil_config.js"></script>
</body>
</html>