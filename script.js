// Hamburger Menu 
document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');

    if (hamburger && navMenu) {
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
    }
});

// Language management
let currentLang = 'en';
const langButtons = document.querySelectorAll('.lang-btn');
const floatingLangButtons = document.querySelectorAll('.language-switcher .lang-btn');

// Set language
function setLanguage(lang) {
    currentLang = lang;
    
    // Update all translatable elements
    document.querySelectorAll('[data-en], [data-sw]').forEach(element => {
        if (element.dataset[lang]) {
            if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
                element.placeholder = element.dataset[lang];
            } else {
                element.textContent = element.dataset[lang];
            }
        }
    });
    
    // Update button states
    langButtons.forEach(btn => {
        btn.classList.toggle('active', btn.id === `lang-${lang}`);
    });
    
    floatingLangButtons.forEach(btn => {
        btn.classList.toggle('active', btn.dataset.lang === lang);
    });
    
    // Save to localStorage
    localStorage.setItem('preferredLanguage', lang);
}

// Event listeners for language buttons
langButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        const lang = btn.id.split('-')[1];
        setLanguage(lang);
    });
});

floatingLangButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        const lang = btn.dataset.lang;
        setLanguage(lang);
    });
});

// Initialize with saved language or default to English
const savedLang = localStorage.getItem('preferredLanguage') || 'en';
setLanguage(savedLang);

// Navigation functionality
document.querySelectorAll('.nav-link, .nav-button').forEach(item => {
    item.addEventListener('click', function(e) {
        e.preventDefault();
        
        const target = this.getAttribute('data-target');
        const scrollTo = this.getAttribute('data-scroll');
        
        // Hide all sections
        document.querySelectorAll('section').forEach(section => {
            section.classList.remove('active');
        });
        
        // Show target section
        if (target) {
            const section = document.getElementById(target);
            if (section) {
                section.classList.add('active');
                window.scrollTo(0, 0);
                
                // Handle scroll targets
                if (scrollTo) {
                    setTimeout(() => {
                        const element = document.getElementById(scrollTo);
                        if (element) element.scrollIntoView({ behavior: 'smooth' });
                    }, 100);
                }
            }
        }
        
        // Close mobile menu if open
        const navMenu = document.querySelector('.nav-menu');
        const hamburger = document.querySelector('.hamburger');
        if (navMenu) navMenu.classList.remove('active');
        if (hamburger) hamburger.classList.remove('active');
    });
});

// Carousel functionality
const carouselInner = document.querySelector('.carousel-inner');
const carouselItems = document.querySelectorAll('.carousel-item');
const prevBtn = document.querySelector('.carousel-control.prev');
const nextBtn = document.querySelector('.carousel-control.next');
const indicators = document.querySelectorAll('.carousel-indicator');

if (carouselInner && carouselItems.length > 0) {
    let currentIndex = 0;
    const totalItems = carouselItems.length;
    
    function updateCarousel() {
        carouselInner.style.transform = `translateX(-${currentIndex * 100}%)`;
        
        // Update indicators
        indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === currentIndex);
        });
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + totalItems) % totalItems;
            updateCarousel();
        });
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % totalItems;
            updateCarousel();
        });
    }
    
    // Add click handlers to indicators
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            currentIndex = index;
            updateCarousel();
        });
    });
    
    // Auto-rotate carousel
    setInterval(() => {
        currentIndex = (currentIndex + 1) % totalItems;
        updateCarousel();
    }, 8000);
}

// Tab functionality for Vision section
document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', () => {
        const tab = button.getAttribute('data-tab');
        
        // Remove active class from all buttons and contents
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });
        
        // Add active class to clicked button and corresponding content
        button.classList.add('active');
        const contentElement = document.getElementById(`${tab}-content`);
        if (contentElement) contentElement.classList.add('active');
    });
});

// Chart functionality
let growthChart; 

// Update chart labels when language changes
document.addEventListener('languageChange', () => {
    if (growthChart) {
        growthChart.data.datasets[0].label = currentLang === 'en' ? 
            'Projected Production (tons)' : 'Uzalishaji Unaotarajiwa (tani)';
        growthChart.data.datasets[1].label = currentLang === 'en' ? 
            'Projected Sales (millions TZS)' : 'Mauzo Yanayotarajiwa (milioni TZS)';
        growthChart.options.plugins.title.text = currentLang === 'en' ? 
            'Five-Year Growth Projection' : 'Makadirio ya Ukuaji wa Miaka Mitano';
        growthChart.update();
    }
});

// Create custom event for language change
function dispatchLanguageChange() {
    const event = new Event('languageChange');
    document.dispatchEvent(event);
}

// Update the setLanguage function to dispatch the event
const originalSetLanguage = setLanguage;
setLanguage = function(lang) {
    originalSetLanguage(lang);
    dispatchLanguageChange();
};
