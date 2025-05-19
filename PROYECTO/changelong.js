








  
  const changelogData = [
    {
      target: "v030",
      version: "pre-alpha 0.3.0",
      changes: [
        { type: "subtitle", text: "NUEVAS FUNCIONES" },
        { type: "new", text: "Estructura base del proyecto iniciada." },
        { type: "new", text: "Primera interfaz HTML estática." },
        { type: "new", text: "Implementación de aviso de términos de privacidad y condiciones" },
        { type: "new", text: "Implementación de traductor" },
        { type: "new", text: "Nuevo menú lateral" },
        { type: "new", text: "Modo obscuro" },
        { type: "subtitle", text: "MEJORAS" },
        { type: "update", text: "Nuevo servidor “Codespace”" },
        { type: "update", text: "Servidor de imágenes" },
        { type: "update", text: "Más seguridad en inyecciones SQL" },
        { type: "update", text: "Mejor rendimiento" },
        { type: "update", text: "Restricciones en el formulario de registro" }
      ]
    },
    {
      target: "v042",
      version: "pre-alpha 0.4.2",
      changes: [
        { type: "subtitle", text: "NUEVAS FUNCIONES" },
        { type: "new", text: "Ver perfiles" },
        { type: "new", text: "Ver empresas" },
        { type: "new", text: "Buscador de empresas" },
        { type: "new", text: "Buscador en el menú lateral" },
        { type: "new", text: "Retroceso en la barra lateral" },
        { type: "subtitle", text: "MEJORAS" },
        { type: "update", text: "Diseño en la página principal" },
        { type: "update", text: "Corrección en el bug de 'ya hay usuario'" },
        { type: "update", text: "Corrección en el bug de diseño en el traductor" }
      ]
    },
    {
      target: "alpha",
      version: "Alpha 0.5.5",
      changes: [
        
      ]
    },
    {
      target: "v055",
      version: "Alpha 0.5.5",
      changes: [
        { type: "subtitle", text: "NUEVAS FUNCIONES" },
        { type: "new", text: "Implementación de modo oscuro." },
        { type: "new", text: "Vista avanzada del changelog en 4 columnas." },
        { type: "new", text: "Implementación de administración de usuarios" },
        { type: "new", text: "Implementación de administración de empresas" },
        { type: "new", text: "Implementación de registro de empresas" },
        { type: "new", text: "Implementación de verificación de empresa" },
        { type: "new", text: "Implementación de envío de actualización" },
        { type: "new", text: "Implementación de empresa dentro de la página" },
        { type: "new", text: "Implementación de diseño para historias (anuncios, productos, variedades)" },
        { type: "new", text: "Implementación de calendario interactivo para eventos (Solo para administradores)" },
        { type: "new", text: "Implementación de administración de desarrolladores" },
        { type: "subtitle", text: "MEJORAS" },
        { type: "update", text: "Menú para administradores" },
        { type: "update", text: "Diseño diferente en cómo se ven las empresas" },
        { type: "update", text: "Corrección de bugs en diseño" }
      ]
    }
  ];
  
  const icons = {
    new: "fa-plus-circle",
    fix: "fa-bug",
    update: "fa-sync-alt"
  };
  
  document.addEventListener("DOMContentLoaded", () => {
    changelogData.forEach(log => {
      const container = document.getElementById(log.target);
      const versionTitle = document.createElement("p");
      versionTitle.innerHTML = `<strong>${log.version}</strong>`;
      container.appendChild(versionTitle);
  
      log.changes.forEach(change => {
        if (change.type === "subtitle") {
          const subtitle = document.createElement("div");
          subtitle.className = "subtitle";
          subtitle.textContent = change.text;
          container.appendChild(subtitle);
        } else {
          const div = document.createElement("div");
          div.className = `change-item ${change.type} fade-in`;
          div.innerHTML = `<i class="fas ${icons[change.type]}"></i> ${change.text}`;
          container.appendChild(div);
        }
      });
    });
  
    window.addEventListener("scroll", revealOnScroll);
    revealOnScroll();
  });
  
  function revealOnScroll() {
    document.querySelectorAll('.fade-in').forEach(el => {
      const top = el.getBoundingClientRect().top;
      if (top < window.innerHeight - 50) {
        el.classList.add('visible');
      }
    });
  }
  
  document.getElementById("toggleTheme").addEventListener("click", () => {
    document.body.classList.toggle("dark");
    const icon = document.querySelector("#toggleTheme i");
    icon.classList.toggle("fa-moon");
    icon.classList.toggle("fa-sun");
  });
  