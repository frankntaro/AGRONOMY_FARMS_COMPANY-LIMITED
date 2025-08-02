document.addEventListener("DOMContentLoaded", () => {
  // --- Language Switcher Logic ---
  const langButtons = document.querySelectorAll('.lang-button');
  const translatableElements = document.querySelectorAll('[data-en], [data-sw]');
  const placeholderElements = document.querySelectorAll('[data-en-placeholder], [data-sw-placeholder]');
  const localStorageKey = 'selectedLanguage';

  function updateLanguage(lang) {
    translatableElements.forEach(element => {
      const translation = element.getAttribute(`data-${lang}`);
      if (translation) {
        element.textContent = translation;
      }
    });

    placeholderElements.forEach(element => {
      const placeholder = element.getAttribute(`data-${lang}-placeholder`);
      if (placeholder) {
        element.placeholder = placeholder;
      }
    });

    // Update datalist options
    const datalistOptions = document.querySelectorAll('datalist#regions option');
    datalistOptions.forEach(option => {
      const translation = option.getAttribute(`data-${lang}`);
      if (translation) {
        option.textContent = translation;
      }
    });

    // Update select options
    const selectOptions = document.querySelectorAll('select#cropType option');
    selectOptions.forEach(option => {
      const translation = option.getAttribute(`data-${lang}`);
      if (translation) {
        option.textContent = translation;
      }
    });

    langButtons.forEach(btn => btn.classList.remove('active'));
    document.querySelector(`.lang-button[data-lang="${lang}"]`).classList.add('active');
  }

  langButtons.forEach(button => {
    button.addEventListener('click', () => {
      const selectedLang = button.dataset.lang;
      localStorage.setItem(localStorageKey, selectedLang);
      updateLanguage(selectedLang);
    });
  });

  const initialLang = localStorage.getItem(localStorageKey) || 'en';
  updateLanguage(initialLang);
  // --- End Language Switcher Logic ---

  // --- District Datalist Logic ---
  const districtOptions = {
    "Arusha": ["Arusha City", "Arumeru", "Karatu", "Longido", "Monduli", "Ngorongoro"],
    "Dar es Salaam": ["Ilala", "Kinondoni", "Temeke", "Ubungo", "Kigamboni"],
    "Dodoma": ["Bahi", "Chamwino", "Chemba", "Dodoma Urban", "Kondoa", "Mpwapwa"],
    "Geita": ["Bukombe", "Chato", "Geita Town", "Mbogwe", "Nyang'hwale"],
    "Iringa": ["Iringa Rural", "Iringa Urban", "Kilolo", "Mafinga Town", "Mufindi"],
    "Kagera": ["Biharamulo", "Bukoba", "Bukoba Urban", "Karagwe", "Kyerwa", "Missenyi", "Muleba", "Ngara"],
    "Katavi": ["Mlele", "Mpanda", "Mpimbwe", "Tanganyika"],
    "Kigoma": ["Buhigwe", "Kakonko", "Kasulu", "Kasulu Town", "Kibondo", "Kigoma Rural", "Kigoma-Ujiji", "Uvinza"],
    "Kilimanjaro": ["Hai", "Moshi Rural", "Moshi Urban", "Mwanga", "Rombo", "Same", "Siha"],
    "Lindi": ["Kilwa", "Lindi Rural", "Lindi Urban", "Liwale", "Nachingwea", "Ruangwa"],
    "Manyara": ["Babati Rural", "Babati Urban", "Hanang", "Kiteto", "Mbulu", "Simanjiro"],
    "Mara": ["Bunda", "Butiama", "Musoma Rural", "Musoma Urban", "Rorya", "Serengeti", "Tarime"],
    "Mbeya": ["Busokelo", "Chunya", "Ileje", "Kyela", "Mbeya Rural", "Mbeya Urban", "Mbarali", "Rungwe"],
    "Morogoro": ["Gairo", "Ifakara Town", "Kilombero", "Kilosa", "Malinyi", "Morogoro Rural", "Morogoro Urban", "Mvomero", "Ulanga"],
    "Mtwara": ["Masasi Rural", "Masasi Town", "Mtwara Rural", "Mtwara Urban", "Nanyumbu", "Newala", "Tandahimba"],
    "Mwanza": ["Ilemela", "Kwimba", "Magu", "Misungwi", "Nyamagana", "Sengerema", "Ukerewe"],
    "Njombe": ["Ludewa", "Makambako Town", "Makete", "Njombe Rural", "Njombe Town", "Wanging'ombe"],
    "Pemba North": ["Micheweni", "Wete"],
    "Pemba South": ["Chake Chake", "Mkoani"],
    "Pwani": ["Bagamoyo", "Kibaha Rural", "Kibaha Town", "Kisarawe", "Mafia", "Mkuranga", "Rufiji"],
    "Rukwa": ["Kalambo", "Nkasi", "Sumbawanga Rural", "Sumbawanga Urban"],
    "Ruvuma": ["Mbinga", "Mbinga Town", "Namtumbo", "Nyasa", "Songea Rural", "Songea Urban", "Tunduru"],
    "Shinyanga": ["Kahama Rural", "Kahama Town", "Kishapu", "Shinyanga Rural", "Shinyanga Urban"],
    "Simiyu": ["Bariadi", "Busega", "Itilima", "Maswa", "Meatu"],
    "Singida": ["Ikungi", "Iramba", "Manyoni", "Mkalama", "Singida Rural", "Singida Urban"],
    "Tabora": ["Igunga", "Kaliua", "Nzega", "Nzega Town", "Sikonge", "Tabora Municipal", "Urambo", "Uyui"],
    "Tanga": ["Handeni", "Handeni Town", "Kilindi", "Korogwe", "Korogwe Town", "Lushoto", "Muheza", "Pangani", "Tanga City"],
    "Unguja North": ["Kaskazini A", "Kaskazini B"],
    "Unguja South": ["Kati", "Kusini"],
    "Zanzibar Urban/West": ["Mjini", "Magharibi"]
  };

  const regionInput = document.getElementById("region");
  const districtList = document.getElementById("districts");

  regionInput.addEventListener("input", () => {
    const selectedRegion = regionInput.value.trim();
    districtList.innerHTML = "";
    if (districtOptions[selectedRegion]) {
      districtOptions[selectedRegion].forEach(district => {
        const option = document.createElement("option");
        option.value = district;
        districtList.appendChild(option);
      });
    }
  });
  // --- End District Datalist Logic ---

  // --- Multi-Step Form Logic ---
  const forms = document.querySelectorAll("form");
  let currentForm = 0;

  function showForm(index) {
    forms.forEach((form, i) => {
      form.classList.toggle("active", i === index);
    });
  }

  showForm(currentForm);

  document.getElementById("next1").addEventListener("click", () => {
    if (forms[0].checkValidity()) {
      currentForm = 1;
      showForm(currentForm);
    } else {
      forms[0].reportValidity();
    }
  });

  document.getElementById("prev2").addEventListener("click", () => {
    currentForm = 0;
    showForm(currentForm);
  });

  document.getElementById("next2").addEventListener("click", () => {
    if (forms[1].checkValidity()) {
      currentForm = 2;
      showForm(currentForm);
    } else {
      forms[1].reportValidity();
    }
  });

  document.getElementById("prev3").addEventListener("click", () => {
    currentForm = 1;
    showForm(currentForm);
  });

  // --- Custom Alert and Confirmation Elements ---
  const confirmationMessage = document.getElementById("confirmationMessage");
  const alertMessage = document.getElementById("alertMessage");
  const closeAlertBtn = document.querySelector("#alertMessage .close-button");

  function showAlert() {
    alertMessage.classList.add("show");
  }

  function hideAlert() {
    alertMessage.classList.remove("show");
  }

  closeAlertBtn.addEventListener("click", hideAlert);

  // --- Submit Handler ---
  document.getElementById("submitBtn").addEventListener("click", async () => {
    const consent = document.getElementById("consent").checked;
    if (!consent) {
      showAlert();
      return;
    }

    // Collect all form data
    const data = {
      fullName: document.getElementById("fullName").value.trim(),
      phone: document.getElementById("phone").value.trim(),
      region: document.getElementById("region").value.trim(),
      district: document.getElementById("district").value.trim(),
      cropType: document.getElementById("cropType").value,
      quantity: parseInt(document.getElementById("quantity").value, 10),
      price: parseFloat(document.getElementById("price").value),
      consent: consent ? 1 : 0
    };

    // Frontend validation
    if (!data.fullName || !data.phone || !data.region || !data.district || !data.cropType || isNaN(data.quantity) || isNaN(data.price)) {
      alert("Please fill in all fields correctly.");
      return;
    }

    try {
      const response = await fetch("save_crop_sale.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      });

      const result = await response.json();

      if (result.success) {
        confirmationMessage.classList.add("show");
        forms.forEach(f => (f.style.display = "none"));
      } else {
        alert(result.message || "Submission failed. Please try again.");
      }
    } catch (error) {
      alert("Error submitting form: " + error.message);
    }
  });
});
