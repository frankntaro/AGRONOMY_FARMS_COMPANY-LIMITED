document.addEventListener("DOMContentLoaded", () => {
    // --- Language Switcher Logic ---
    const langButtons = document.querySelectorAll('.lang-button');
    const allTranslatableElements = document.querySelectorAll('[data-en], [data-sw], [data-en-placeholder], [data-sw-placeholder]');
    const localStorageKey = 'selectedLanguage';

    function updateLanguage(lang) {
        allTranslatableElements.forEach(element => {
            const translation = element.getAttribute(`data-${lang}`);
            if (translation) {
                element.textContent = translation;
            }
            const placeholder = element.getAttribute(`data-${lang}-placeholder`);
            if (placeholder) {
                element.placeholder = placeholder;
            }
        });
        const datalistOptions = document.querySelectorAll('datalist#regions option');
        datalistOptions.forEach(option => {
            const translation = option.getAttribute(`data-${lang}`);
            if (translation) {
                option.textContent = translation;
            }
        });
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
    const districtInput = document.getElementById("district");
    const districtList = document.getElementById("districts");
    const regionsDatalist = document.getElementById("regions");

    Object.keys(districtOptions).forEach(region => {
        const option = document.createElement("option");
        option.value = region;
        regionsDatalist.appendChild(option);
    });

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
        districtInput.value = "";
    });

    // --- Multi-Step Form & Custom Alert/Confirmation Logic ---
    const forms = document.querySelectorAll("form");
    const confirmationMessage = document.getElementById("confirmationMessage");
    const alertMessage = document.getElementById("alertMessage");
    const alertText = document.getElementById("alertText");
    const closeAlertBtn = document.querySelector("#alertMessage .close-button");
    const submitBtn = document.getElementById("submitBtn");

    let currentForm = 0;

    function showForm(index) {
        forms.forEach((form, i) => {
            form.classList.toggle("active", i === index);
        });
    }

    function validateForm(formIndex) {
        const currentFormEl = forms[formIndex];
        const requiredInputs = currentFormEl.querySelectorAll('[required]');
        let isValid = true;
        let firstInvalidInput = null;
        for (const input of requiredInputs) {
            if (input.value.trim() === '') {
                isValid = false;
                if (!firstInvalidInput) firstInvalidInput = input;
            } else if (input.tagName === 'INPUT' && input.getAttribute('list')) {
                const options = document.getElementById(input.getAttribute('list')).options;
                const optionExists = Array.from(options).some(opt => opt.value === input.value);
                if (!optionExists) {
                    isValid = false;
                    if (!firstInvalidInput) firstInvalidInput = input;
                }
            } else if (!input.checkValidity()) {
                isValid = false;
                if (!firstInvalidInput) firstInvalidInput = input;
            }
        }
        if (!isValid && firstInvalidInput) {
            firstInvalidInput.reportValidity();
            return false;
        }
        return true;
    }

    showForm(currentForm);

    document.getElementById("next1").addEventListener("click", () => {
        if (validateForm(0)) {
            currentForm = 1;
            showForm(currentForm);
        } else {
            showAlert("Please fill in all required fields.");
        }
    });

    document.getElementById("prev2").addEventListener("click", () => {
        currentForm = 0;
        showForm(currentForm);
    });

    document.getElementById("next2").addEventListener("click", () => {
        if (validateForm(1)) {
            currentForm = 2;
            showForm(currentForm);
        } else {
            showAlert("Please fill in all required fields.");
        }
    });

    document.getElementById("prev3").addEventListener("click", () => {
        currentForm = 1;
        showForm(currentForm);
    });

    function showAlert(message) {
        alertText.textContent = message;
        alertMessage.classList.add("show");
    }

    function hideAlert() {
        alertMessage.classList.remove("show");
    }

    closeAlertBtn.addEventListener("click", hideAlert);

    // --- Submit Handler ---
    submitBtn.addEventListener("click", async (e) => {
        e.preventDefault();

        if (!validateForm(2)) {
            showAlert("Please fill in all required fields in the last step.");
            return;
        }

        const consent = document.getElementById("consent").checked;
        if (!consent) {
            showAlert("You must agree to the terms and give your consent.");
            return;
        }

        submitBtn.disabled = true;
        showAlert("Submitting your application...");

        const formData = new FormData();
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input, select');
            inputs.forEach(input => {
                 if (input.type !== 'checkbox' || input.checked) {
                    formData.append(input.name, input.value);
                }
            });
        });

        try {
            const response = await fetch("save_crop_sale.php", {
                method: "POST",
                body: formData,
            });

            const contentType = response.headers.get('content-type');
            if (contentType && contentType.indexOf('application/json') !== -1) {
                const result = await response.json();
                if (response.ok && result.success) {
                    hideAlert();
                    confirmationMessage.classList.add("show");
                    forms.forEach(form => form.reset());
                    showForm(0);
                } else {
                    showAlert(result.message || "Submission failed. Please try again.");
                }
            } else {
                const errorText = await response.text();
                console.error("Server Error:", errorText);
                showAlert(`Server Error: ${errorText}`);
            }
        } catch (error) {
            console.error("Error submitting form:", error);
            showAlert("An error occurred. Please check your internet connection.");
        } finally {
            submitBtn.disabled = false;
        }
    });
});
