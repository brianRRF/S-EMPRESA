// ========== Dashboard con facial, avatar, bio, teléfono, temas, mapa, edad ==========

const MODELS_URL = "https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js@0.22.2/weights";
let videoStream = null, isModelLoaded = false, facialLoop = null;
let nombreUsuarioTemporal = '';

// --- INICIALIZACIÓN ---
document.addEventListener("DOMContentLoaded", () => {
  inicializarFacialCard();
  renderThemes();
  asociarEventosDashboard();
});

// ========== FACIAL ==========

function updateStatus(message, type = 'info', showSpinner = false) {
  const el = document.getElementById('facialStatus') || document.getElementById('status');
  if (!el) return;
  let color = "";
  if(type==='success') color="text-success";
  if(type==='danger'||type==='error') color="text-danger";
  if(type==='info') color="text-info";
  if(type==='loading') color="text-primary";
  el.className = "status " + color;
  el.innerHTML = (showSpinner?'<span class="spinner"></span> ':'') + message;
}

function inicializarFacialCard() {
  fetch('verifica_facial.php')
    .then(resp => resp.json())
    .then(data => {
      if (data.success && data.tieneFacial) {
        mostrarQuitarFacial();
      } else {
        mostrarRegistrarFacial();
      }
    })
    .catch(() => {
      mostrarRegistrarFacial();
    });
}

function mostrarRegistrarFacial() {
  const facialCtn = document.getElementById('facialCtn');
  facialCtn.innerHTML = `
    <div class="circle"></div>
    <div class="main-content-ctn">
      <span class="main-icon"><ion-icon name="happy-outline"></ion-icon></span>
      <h4 class="mb-2">¡Activa el inicio de sesión facial!</h4>
      <p class="mb-3 text-secondary">
        Registra tu rostro para que puedas acceder usando reconocimiento facial. Más seguro y rápido.
      </p>
      <button class="btn btn-primary px-4 fw-semibold" type="button" id="btnFacialRegistro">
        <ion-icon name="scan-circle-outline"></ion-icon>
        Registrar mi cara
      </button>
      <div class="status" id="facialStatus"></div>
    </div>
  `;
  document.getElementById('btnFacialRegistro').onclick = mostrarRegistroFacial;
}

function mostrarQuitarFacial() {
  const facialCtn = document.getElementById('facialCtn');
  facialCtn.innerHTML = `
    <div class="circle"></div>
    <div class="main-content-ctn">
      <span class="main-icon"><ion-icon name="happy-outline"></ion-icon></span>
      <h4 class="mb-2">Reconocimiento facial activo</h4>
      <p class="mb-3 text-secondary">
        Ya tienes el acceso con reconocimiento facial activo.<br>
        Si deseas desactivarlo, puedes hacerlo aquí.
      </p>
      <button class="btn btn-danger px-4 fw-semibold" type="button" id="btnQuitarFacial">
        <ion-icon name="close-circle-outline"></ion-icon>
        Quitar inicio facial
      </button>
      <div class="status" id="facialStatus"></div>
    </div>
  `;
  document.getElementById('btnQuitarFacial').onclick = quitarFacial;
}

function mostrarRegistroFacial() {
  nombreUsuarioTemporal = document.getElementById('nombreCompleto')
    ? document.getElementById('nombreCompleto').value
    : '';
  const seccion = document.getElementById("seccionDerecha");
  if (!seccion.dataset.dashboardHtml) {
    seccion.dataset.dashboardHtml = seccion.innerHTML;
  }
  seccion.innerHTML = `
    <section id="facialRegistroSeccion" class="fade-section">
      <h3 class="mb-2"><ion-icon name="scan-circle-outline"></ion-icon> Registrar rostro</h3>
      <div class="status text-info" id="facialStatus"></div>
      <div class="facial-canvas-ctn">
        <video id="videoRegistro" autoplay muted playsinline></video>
        <canvas id="canvasOverlay"></canvas>
      </div>
      <div class="text-center mt-3">
        <button class="btn-facial" id="btnCapturar" disabled>
          <ion-icon name="camera-outline"></ion-icon> Capturar rostro
        </button>
        <button class="btn-facial" id="btnCancelarFacial">
          <ion-icon name="arrow-back-outline"></ion-icon> Cancelar
        </button>
      </div>
    </section>
  `;
  iniciarRegistroFacial();
}

async function iniciarRegistroFacial() {
  updateStatus('Cargando modelos...', 'loading', true);
  if(!isModelLoaded) {
    try {
      await faceapi.nets.tinyFaceDetector.loadFromUri(MODELS_URL);
      await faceapi.nets.faceLandmark68Net.loadFromUri(MODELS_URL);
      await faceapi.nets.faceRecognitionNet.loadFromUri(MODELS_URL);
      isModelLoaded = true;
    } catch (e) {
      updateStatus('Error al cargar modelos. Revisa tu conexión.', 'danger');
      return;
    }
  }
  updateStatus('Modelos cargados. Iniciando cámara...','loading',true);

  const video = document.getElementById('videoRegistro');
  const canvas = document.getElementById('canvasOverlay');
  video.width = 340; video.height = 255;
  canvas.width = 340; canvas.height = 255;

  try {
    videoStream = await navigator.mediaDevices.getUserMedia({video: {width:340, height:255, facingMode:'user'}});
    video.srcObject = videoStream;
    video.onloadedmetadata = () => {
      video.play();
      updateStatus('Alinea tu rostro y mira a la cámara. Haz clic en "Capturar rostro" cuando estés listo.','success');
      document.getElementById('btnCapturar').disabled = false;
    };
  } catch(e) {
    updateStatus('No se pudo acceder a la cámara.','danger');
    return;
  }

  document.getElementById('btnCapturar').onclick = () => analizarYCapturarRostro(video, canvas);
  document.getElementById('btnCancelarFacial').onclick = cerrarRegistroFacial;

  if (facialLoop) {
    cancelAnimationFrame(facialLoop);
    facialLoop = null;
  }
  facialLoop = requestAnimationFrame(function loop() {
    if(!videoStream) return;
    const ctx = canvas.getContext('2d');
    ctx.clearRect(0,0,canvas.width,canvas.height);
    if(isModelLoaded && !video.paused && !video.ended) {
      faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().then(detections => {
        if(detections) {
          faceapi.draw.drawDetections(canvas, [detections]);
          faceapi.draw.drawFaceLandmarks(canvas, [detections]);
        }
        if (videoStream) facialLoop = requestAnimationFrame(loop);
      });
    } else if (videoStream) {
      facialLoop = requestAnimationFrame(loop);
    }
  });
}

function cerrarRegistroFacial() {
  detenerCamara();
  restaurarDashboard();
}

function detenerCamara() {
  try {
    if(facialLoop) {
      cancelAnimationFrame(facialLoop);
      facialLoop = null;
    }
    if(videoStream) {
      videoStream.getTracks().forEach(track=>track.stop());
      videoStream = null;
    }
  } catch(e){}
}

async function analizarYCapturarRostro(video, canvas) {
  updateStatus('Analizando rostro...', 'loading', true);
  try {
    if (!video || !video.videoWidth || !video.videoHeight) {
      updateStatus('Video no listo. Espera un momento y reintenta.', 'danger');
      return;
    }
    const detection = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptor();
    if(!detection) {
      updateStatus('❌ No se detectó rostro. Asegúrate de estar bien encuadrado y con buena luz.','danger');
      return;
    }
    if(detection.detection.score < 0.5) {
      updateStatus('❌ Detección de baja calidad. Intenta mejor iluminación o encuadre.','danger');
      return;
    }
    const box = detection.detection.box;
    const x = Math.max(0, Math.round(box.x));
    const y = Math.max(0, Math.round(box.y));
    const w = Math.max(10, Math.round(box.width));
    const h = Math.max(10, Math.round(box.height));
    const videoW = video.videoWidth;
    const videoH = video.videoHeight;
    const cropW = Math.min(w, videoW - x);
    const cropH = Math.min(h, videoH - y);
    if (!Number.isFinite(x) || !Number.isFinite(y) || !Number.isFinite(cropW) || !Number.isFinite(cropH)) {
      updateStatus("Coordenadas inválidas detectadas. Refresca la página.", "danger");
      return;
    }
    if (cropW < 10 || cropH < 10) {
      updateStatus("❌ Rostro fuera de cuadro o demasiado pequeño. Ajusta tu posición.", "danger");
      return;
    }
    const tempCanvas = document.createElement('canvas');
    tempCanvas.width = cropW; tempCanvas.height = cropH;
    tempCanvas.getContext('2d').drawImage(
      video, x, y, cropW, cropH, 0, 0, cropW, cropH
    );
    const imgBase64 = tempCanvas.toDataURL('image/jpeg',0.9);

    updateStatus('Guardando registro facial...','loading',true);
    const response = await fetch('guardar_facial_usuario.php', {
      method: 'POST',
      headers: { 'Content-Type':'application/json' },
      body: JSON.stringify({
        descriptor: Array.from(detection.descriptor),
        imagen: imgBase64
      })
    });
    const res = await response.json();
    if(res.success) {
      updateStatus("¡Rostro registrado correctamente!","success");
      detenerCamara();
      setTimeout(() => {
        restaurarDashboard();
      }, 1000);
    } else {
      updateStatus("Error: "+res.message,"danger");
    }
  } catch(e) {
    updateStatus("Error inesperado al analizar rostro: " + (e.message || e.toString()),"danger");
  }
}

function quitarFacial() {
  if (!confirm("¿Estás seguro de que deseas quitar el inicio facial?")) return;
  fetch('quitar_facial_usuario.php', {
    method: 'POST',
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({})
  })
    .then(resp => resp.json())
    .then(data => {
      if (data.success) {
        alert("Inicio facial eliminado correctamente.");
        mostrarRegistrarFacial();
      } else {
        alert("No se pudo eliminar el inicio facial.");
      }
    }).catch(() => {
      alert("Error de conexión al eliminar facial.");
    });
}

// ========== RESTAURAR DASHBOARD Y REASOCIAR EVENTOS ==========
function restaurarDashboard() {
  const seccion = document.getElementById("seccionDerecha");
  if (seccion.dataset.dashboardHtml) {
    seccion.innerHTML = seccion.dataset.dashboardHtml;
    inicializarFacialCard();
    renderThemes && renderThemes();
    asociarEventosDashboard();
  }
}

// ========== ASOCIAR EVENTOS DASHBOARD ==========
function asociarEventosDashboard() {
  // Avatar
  const avatarInput = document.getElementById('avatarInput');
  if(avatarInput) {
    avatarInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) {
          document.getElementById('previewAvatar').src = ev.target.result;
        };
        reader.readAsDataURL(file);
      }
    });
  }

  // Guardar perfil
  const formPerfil = document.getElementById('formPerfil');
  if(formPerfil) {
    formPerfil.onsubmit = function(e) {
      e.preventDefault();
      const datos = {
        nombreCompleto: document.getElementById('nombreCompleto').value,
        sexo: document.getElementById('sexo').value,
        fechaNacimiento: document.getElementById('fechaNacimiento').value,
        edad: document.getElementById('edad').value,
        bio: document.getElementById('bio').value,
        telefono: document.getElementById('telefono').value,
        localidad: document.getElementById('localidad').value,
        password: document.getElementById('password').value,
        password2: document.getElementById('password2').value
      };
      fetch('actualizar_usuario.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(datos)
      })
      .then(resp=>resp.json())
      .then(res=>{
        if(res.success){
          Swal.fire('¡Perfil actualizado!','','success');
          if(datos.bio && document.getElementById('bioPerfil'))
            document.getElementById('bioPerfil').textContent = datos.bio;
        }else{
          Swal.fire('No se pudo actualizar', res.message, 'error');
        }
      });
    };
  }

  // Nombre
  const nombreCompletoEl = document.getElementById('nombreCompleto');
  if(nombreCompletoEl) {
    nombreCompletoEl.addEventListener('input', function (e) {
      let value = this.value
        .replace(/[^a-zA-ZÁÉÍÓÚÜÑáéíóúüñ\s]/g, '')
        .replace(/\s{2,}/g, ' ');
      this.value = value.toUpperCase();
    });
  }
  // Bio
  const bioEl = document.getElementById('bio');
  const bioCounter = document.getElementById('bioCounter');
  if (bioEl && bioCounter) {
    bioEl.addEventListener('input', function() {
      let value = bioEl.value.replace(/[^a-zA-ZÁÉÍÓÚÜÑáéíóúüñ\s]/g, '').replace(/\s{2,}/g, ' ');
      let letters = value.replace(/\s/g, '');
      if (letters.length > 120) {
        let count = 0, result = '';
        for (let char of value) {
          if (char.match(/[a-zA-ZÁÉÍÓÚÜÑáéíóúüñ]/)) count++;
          if (count > 120) break;
          result += char;
        }
        value = result;
        letters = value.replace(/\s/g, '');
      }
      bioEl.value = value;
      bioCounter.textContent = letters.length + ' / 120 letras';
    });
    // Inicializa contador
    bioEl.dispatchEvent(new Event('input'));
  }
  // Teléfono
  const telefonoEl = document.getElementById('telefono');
  if (telefonoEl) {
    telefonoEl.addEventListener('input', function(e) {
      let value = telefonoEl.value.replace(/\D/g, '').slice(0, 10);
      if (value.length > 6) {
        value = value.slice(0,3) + '-' + value.slice(3,6) + '-' + value.slice(6);
      } else if (value.length > 3) {
        value = value.slice(0,3) + '-' + value.slice(3);
      }
      telefonoEl.value = value;
    });
    if(!telefonoEl.value) telefonoEl.value = "600-123-4567";
  }
  // País
  const countryEl = document.getElementById('country');
  const phonePrefix = document.getElementById('phonePrefix');
  if(countryEl && phonePrefix) {
    countryEl.addEventListener('change', function() {
      phonePrefix.textContent = countryEl.value;
    });
  }

  // Edad (calcula automáticamente)
  const fechaNacimiento = document.getElementById('fechaNacimiento');
  const edadInput = document.getElementById('edad');
  if(fechaNacimiento && edadInput) {
    fechaNacimiento.addEventListener('input', function() {
      const val = this.value;
      if(val) {
        const nacimiento = new Date(val);
        const hoy = new Date();
        let edad = hoy.getFullYear() - nacimiento.getFullYear();
        const m = hoy.getMonth() - nacimiento.getMonth();
        if (m < 0 || (m === 0 && hoy.getDate() < nacimiento.getDate())) {
          edad--;
        }
        edadInput.value = edad >= 0 && edad <= 120 ? edad : '';
      }
    });
    edadInput.addEventListener('input', function() {
      let val = this.value.replace(/\D/g, '').slice(0, 3);
      this.value = val;
    });
  }

  // Mapa y localidad
  const confirmarLocalidadBtn = document.getElementById('confirmarLocalidad');
  if(confirmarLocalidadBtn) {
    confirmarLocalidadBtn.addEventListener('click', function() {
      if (window.currentDireccion && document.getElementById('localidad'))
        document.getElementById('localidad').value = window.currentDireccion;
      bootstrap.Modal.getInstance(document.getElementById('modalMapa')).hide();
    });
  }
  const modalMapa = document.getElementById('modalMapa');
  if(modalMapa) {
    modalMapa.addEventListener('hidden.bs.modal', () => {
      const direccionSeleccionada = document.getElementById('direccionSeleccionada');
      if(direccionSeleccionada)
        direccionSeleccionada.textContent = '';
      if(confirmarLocalidadBtn)
        confirmarLocalidadBtn.disabled = true;
    });
  }
}

// ========== MAPA Y LOCALIDAD ==========
let map, marker;
window.currentLatLng = null;
window.currentDireccion = null;
function abrirMapa() {
  const modal = new bootstrap.Modal(document.getElementById('modalMapa'));
  modal.show();
  setTimeout(() => {
    if (!map) {
      map = L.map('map', { zoomControl: true }).setView([19.432608, -99.133209], 5);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
      }).addTo(map);
      marker = L.marker([19.432608, -99.133209], { draggable: true }).addTo(map);
      function updateDireccion(latlng) {
        const direccionSeleccionada = document.getElementById('direccionSeleccionada');
        const confirmarLocalidadBtn = document.getElementById('confirmarLocalidad');
        if(direccionSeleccionada)
          direccionSeleccionada.textContent = 'Buscando...';
        if(confirmarLocalidadBtn)
          confirmarLocalidadBtn.disabled = true;
        fetch(`https://nominatim.openstreetmap.org/reverse?lat=${latlng.lat}&lon=${latlng.lng}&format=json&accept-language=es`)
          .then(resp => resp.json())
          .then(data => {
            let ciudad = data.address.city || data.address.town || data.address.village || data.address.hamlet || '';
            let estado = data.address.state || '';
            let pais = data.address.country || '';
            let localidad = [ciudad, estado, pais].filter(Boolean).join(', ');
            if(direccionSeleccionada)
              direccionSeleccionada.textContent = localidad ? (`Seleccionado: ${localidad}`) : 'No se pudo determinar la localidad';
            window.currentDireccion = localidad;
            if(confirmarLocalidadBtn)
              confirmarLocalidadBtn.disabled = !localidad;
          })
          .catch(() => {
            if(direccionSeleccionada)
              direccionSeleccionada.textContent = 'No se pudo obtener la localidad';
            if(confirmarLocalidadBtn)
              confirmarLocalidadBtn.disabled = true;
          });
      }
      map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateDireccion(e.latlng);
        window.currentLatLng = e.latlng;
      });
      marker.on('dragend', function(e) {
        updateDireccion(marker.getLatLng());
        window.currentLatLng = marker.getLatLng();
      });
      updateDireccion(marker.getLatLng());
      window.currentLatLng = marker.getLatLng();
    } else {
      setTimeout(() => map.invalidateSize(), 200);
      map.setView(marker.getLatLng(), map.getZoom());
    }
  }, 300);
}

// ========== THEMES ==========
function renderThemes() {
  const themes = [
    {
      name: "Claro estándar",
      value: "default",
      previewClass: "theme-default",
      icon: "sunny-outline"
    },
    {
      name: "Oscuro",
      value: "dark",
      previewClass: "theme-dark",
      icon: "moon-outline"
    },
    {
      name: "Moderno",
      value: "modern",
      previewClass: "theme-modern",
      icon: "color-filter-outline"
    },
    {
      name: "Minimalista",
      value: "minimal",
      previewClass: "theme-minimal",
      icon: "ellipse-outline"
    },
    {
      name: "Pastel",
      value: "pastel",
      previewClass: "theme-pastel",
      icon: "color-palette-outline"
    },
    {
      name: "Alto contraste",
      value: "contrast",
      previewClass: "theme-contrast",
      icon: "contrast-outline"
    }
  ];
  let html = "";
  themes.forEach((theme, idx) => {
    html += `
      <div class="theme-box theme-${theme.value} theme-disabled" tabindex="-1" aria-disabled="true">
        <div class="theme-preview ${theme.previewClass}"></div>
        <span class="theme-name"><ion-icon name="${theme.icon}" style="font-size:1.15em;vertical-align:middle"></ion-icon> ${theme.name}</span>
      </div>
    `;
  });
  if(document.getElementById('themesExamples'))
    document.getElementById('themesExamples').innerHTML = html;
}

// ========== MODALES AVATAR ==========
function abrirModalAvatar() {
  const modal = new bootstrap.Modal(document.getElementById('modalAvatar'));
  modal.show();
}