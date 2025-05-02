const languageData = {
    es: { flag: "https://flagcdn.com/w40/es.png", text: "Español" },
    en: { flag: "https://flagcdn.com/w40/gb.png", text: "English" },
    ru: { flag: "https://flagcdn.com/w40/ru.png", text: "Русский" },
    de: { flag: "https://flagcdn.com/w40/de.png", text: "Deutsch" },
    pt: { flag: "https://flagcdn.com/w40/br.png", text: "Português (BR)" },
    fr: { flag: "https://flagcdn.com/w40/fr.png", text: "Français" },
    it: { flag: "https://flagcdn.com/w40/it.png", text: "Italiano" },
    ja: { flag: "https://flagcdn.com/w40/jp.png", text: "日本語" },
    ar: { flag: "https://flagcdn.com/w40/sa.png", text: "العربية" },
    zh: { flag: "https://flagcdn.com/w40/cn.png", text: "中文" },
    ko: { flag: "https://flagcdn.com/w40/kr.png", text: "한국어" },
    hi: { flag: "https://flagcdn.com/w40/in.png", text: "हिन्दी" },
    tr: { flag: "https://flagcdn.com/w40/tr.png", text: "Türkçe" },
    pl: { flag: "https://flagcdn.com/w40/pl.png", text: "Polski" },
    nl: { flag: "https://flagcdn.com/w40/nl.png", text: "Nederlands" },
    sv: { flag: "https://flagcdn.com/w40/se.png", text: "Svenska" },
    he: { flag: "https://flagcdn.com/w40/il.png", text: "עברית" },
    uk: { flag: "https://flagcdn.com/w40/ua.png", text: "Українська" },
    el: { flag: "https://flagcdn.com/w40/gr.png", text: "Ελληνικά" },
    id: { flag: "https://flagcdn.com/w40/id.png", text: "Bahasa Indonesia" }
  };
  

  let translations = {};
  const selected = document.getElementById("selected-language");
  const optionsContainer = document.getElementById("language-options");
  const ini = document.getElementById("ini");
  const per = document.getElementById("per");
  const emp = document.getElementById("emp");
  const ser = document.getElementById("ser");
  
  


  const languageList = document.getElementById("language-list");
  const languageSearch = document.getElementById("language-search");
  

  // Cargar traducciones
  fetch("traducciones.json")
    .then(res => res.json())
    .then(data => {
      translations = data;
  
      // Usar idioma guardado o por defecto 'es'
      const savedLang = localStorage.getItem("selectedLang") || "es";
      changeLanguage(savedLang);
      renderLanguageOptions();
    });
  


  function renderLanguageOptions(filter = "") {
    languageList.innerHTML = "";
    for (const lang in languageData) {
      if (languageData[lang].text.toLowerCase().includes(filter.toLowerCase())) {
        const option = document.createElement("div");
        option.innerHTML = `
          <img src="${languageData[lang].flag}" alt="${languageData[lang].text}">
          <span>${languageData[lang].text}</span>
        `;
        option.onclick = () => {
          changeLanguage(lang);
          optionsContainer.classList.remove("show");
        };
        languageList.appendChild(option);
      }
    }
  }

  
  
  function changeLanguage(lang) {
    // Guardar idioma en localStorage
    localStorage.setItem("selectedLang", lang);
  
    selected.innerHTML = `
      <img src="${languageData[lang].flag}" alt="${languageData[lang].text}">
      <span>${languageData[lang].text}</span>
    `;
    ini.textContent = translations[lang]?.ini || "Traducción no disponible";
    per.textContent = translations[lang]?.per || "Traducción no disponible";
    blo.textContent = translations[lang]?.blo || "Traducción no disponible";
    emp.textContent = translations[lang]?.emp || "Traducción no disponible";
    ser.textContent = translations[lang]?.ser || "Traducción no disponible";
    
  }
  
  // Alternar menú
  selected.addEventListener("click", () => {
    optionsContainer.classList.toggle("show");
    languageSearch.focus();
  });
  
  // Cerrar menú fuera
  document.addEventListener("click", (e) => {
    if (!e.target.closest('.language-menu')) {
      optionsContainer.classList.remove("show");
    }
  });
  
  // Filtro de búsqueda
  languageSearch.addEventListener("input", (e) => {
    renderLanguageOptions(e.target.value);
  });
  