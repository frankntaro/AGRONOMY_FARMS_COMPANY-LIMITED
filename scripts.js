document.addEventListener("DOMContentLoaded", () => {
    // --- Language Switcher Logic ---
    const langButtons = document.querySelectorAll('.lang-button');
    const allTranslatableElements = document.querySelectorAll('[data-en], [data-sw], [data-en-placeholder], [data-sw-placeholder]');
    const localStorageKey = 'selectedLanguage';

    /**
     * Updates all elements on the page with the specified language.
     * @param {string} lang The language code ('en' or 'sw').
     */
    function updateLanguage(lang) {
        allTranslatableElements.forEach(element => {
            // Update text content for elements with a data-lang attribute
            const translation = element.getAttribute(`data-${lang}`);
            if (translation) {
                element.textContent = translation;
            }
            
            // Update placeholder for input elements
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

        // Update active state of language buttons
        langButtons.forEach(btn => btn.classList.remove('active'));
        document.querySelector(`.lang-button[data-lang="${lang}"]`).classList.add('active');
    }

    // Add click listeners to language buttons
    langButtons.forEach(button => {
        button.addEventListener('click', () => {
            const selectedLang = button.dataset.lang;
            localStorage.setItem(localStorageKey, selectedLang);
            updateLanguage(selectedLang);
        });
    });

    // Set the initial language from local storage or default to 'en'
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
    const districtInput = document.getElementById("district");
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
        // Clear district input when region changes to prevent invalid selection
        districtInput.value = "";
    });
    // --- End District Datalist Logic ---

    // --- Multi-Step Form & Custom Alert/Confirmation Logic ---
    const forms = document.querySelectorAll("form");
    const confirmationMessage = document.getElementById("confirmationMessage");
    const alertMessage = document.getElementById("alertMessage");
    const alertText = document.getElementById("alertText");
    const closeAlertBtn = document.querySelector("#alertMessage .close-button");

    let currentForm = 0;

    /**
     * Hides all forms and shows the one at the specified index.
     * @param {number} index The index of the form to show.
     */
    function showForm(index) {
        forms.forEach((form, i) => {
            form.classList.toggle("active", i === index);
        });
    }

    /**
     * Checks if all required fields in the current form are valid.
     * @returns {boolean} True if the form is valid, false otherwise.
     */
    function validateForm(formIndex) {
        const currentFormEl = forms[formIndex];
        const requiredInputs = currentFormEl.querySelectorAll('[required]');
        let isValid = true;
        let firstInvalidInput = null;

        console.log(`--- Starting validation for form step ${formIndex + 1} ---`);

        for (const input of requiredInputs) {
            console.log(`Checking field: ${input.name}, value: "${input.value}"`);

            // Check for empty value first
            if (input.value.trim() === '') {
                isValid = false;
                console.log(`⛔️ FAILED: ${input.name} is empty.`);
                if (!firstInvalidInput) firstInvalidInput = input;
            } 
            // Custom check for datalist inputs
            else if (input.tagName === 'INPUT' && input.getAttribute('list')) {
                const options = document.getElementById(input.getAttribute('list')).options;
                const optionExists = Array.from(options).some(opt => opt.value === input.value);
                if (!optionExists) {
                    isValid = false;
                    console.log(`⛔️ FAILED: ${input.name} value "${input.value}" does not exist in the datalist.`);
                    if (!firstInvalidInput) firstInvalidInput = input;
                } else {
                    console.log(`✅ PASSED: ${input.name} is valid.`);
                }
            } 
            // Generic check for all other inputs (e.g., email, number, pattern)
            else if (!input.checkValidity()) {
                isValid = false;
                console.log(`⛔️ FAILED: ${input.name} is invalid based on its input type or pattern.`);
                if (!firstInvalidInput) firstInvalidInput = input;
            } else {
                console.log(`✅ PASSED: ${input.name} is valid.`);
            }
        }

        console.log(`--- Validation result for form step ${formIndex + 1}: ${isValid ? 'PASSED' : 'FAILED'} ---`);

        if (!isValid && firstInvalidInput) {
            firstInvalidInput.reportValidity();
            return false;
        }

        return true;
    }

    // Show the first form on page load
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

    /**
     * Displays the custom alert box with a given message.
     * @param {string} message The message to display.
     */
    function showAlert(message) {
        alertText.textContent = message;
        alertMessage.classList.add("show");
    }

    /**
     * Hides the custom alert box.
     */
    function hideAlert() {
        alertMessage.classList.remove("show");
    }

    closeAlertBtn.addEventListener("click", hideAlert);

    // --- Submit Handler ---
    document.getElementById("submitBtn").addEventListener("click", async (e) => {
        e.preventDefault(); // Prevent default form submission

        // Final validation for the last step (Form 2)
        if (!validateForm(2)) {
            showAlert("Please fill in all required fields in the last step.");
            return;
        }

        const consent = document.getElementById("consent").checked;
        if (!consent) {
            showAlert("You must agree to the terms and give your consent.");
            return;
        }

        showAlert("Submitting your application..."); // Show a loading/processing message

        // Collect all form data using FormData
        const formData = new FormData();
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input, select');
            inputs.forEach(input => {
                if (input.type !== 'checkbox' || input.checked) {
                    formData.append(input.name, input.value);
                }
            });
        });
        
        // Add a submission timestamp
        formData.append('submission_date', new Date().toISOString());

        try {
            const response = await fetch("save_crop_sale.php", {
                method: "POST",
                body: formData,
            });

            const result = await response.json();

            if (result.success) {
                hideAlert(); // Hide the "submitting" message
                confirmationMessage.classList.add("show");
                // Optional: clear the forms or redirect
            } else {
                showAlert(result.message || "Submission failed. Please try again.");
            }
        } catch (error) {
            // Handle network or server errors
            console.error("Error submitting form:", error);
            showAlert("An error occurred while submitting. Please check your internet connection.");
        }
    });
});