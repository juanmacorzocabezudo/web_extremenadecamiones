// ===================================
// Page Loader
// ===================================
window.addEventListener('load', () => {
    const loader = document.getElementById('page-loader');
    if (loader) {
        // Mantener el loader visible por 2 segundos para que se aprecie la animación
        setTimeout(() => {
            loader.classList.add('hidden');
            // Eliminar del DOM después de la animación de desvanecimiento
            setTimeout(() => {
                loader.style.display = 'none';
            }, 500);
        }, 2000);
    }
});

// ===================================
// Mobile Menu Toggle
// ===================================
// Mobile Menu
// ===================================
const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
const navMenu = document.querySelector('.nav-menu');

if (mobileMenuToggle) {
    mobileMenuToggle.addEventListener('click', () => {
        const isActive = navMenu.classList.toggle('active');
        mobileMenuToggle.classList.toggle('active');
        
        // Bloquear/desbloquear scroll del body
        if (isActive) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    });
    
    // Cerrar menú al hacer clic en un enlace
    const menuLinks = navMenu.querySelectorAll('a');
    menuLinks.forEach(link => {
        link.addEventListener('click', () => {
            navMenu.classList.remove('active');
            mobileMenuToggle.classList.remove('active');
            document.body.style.overflow = '';
        });
    });
    
    // Cerrar menú al hacer clic fuera
    document.addEventListener('click', (e) => {
        if (!navMenu.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
            if (navMenu.classList.contains('active')) {
                navMenu.classList.remove('active');
                mobileMenuToggle.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
    });
}

// ===================================
// Hero Slider
// ===================================
class HeroSlider {
    constructor() {
        this.slides = document.querySelectorAll('.hero-slide');
        this.prevBtn = document.querySelector('.hero-prev');
        this.nextBtn = document.querySelector('.hero-next');
        this.dotsContainer = document.querySelector('.hero-dots');
        this.currentSlide = 0;
        this.slideInterval = null;

        if (this.slides.length > 0) {
            this.init();
        }
    }

    init() {
        this.createDots();
        this.startAutoSlide();
        this.addEventListeners();
    }

    createDots() {
        this.slides.forEach((_, index) => {
            const dot = document.createElement('div');
            dot.classList.add('hero-dot');
            if (index === 0) dot.classList.add('active');
            dot.addEventListener('click', () => this.goToSlide(index));
            this.dotsContainer.appendChild(dot);
        });
        this.dots = document.querySelectorAll('.hero-dot');
    }

    goToSlide(n) {
        this.slides[this.currentSlide].classList.remove('active');
        this.dots[this.currentSlide].classList.remove('active');
        
        this.currentSlide = (n + this.slides.length) % this.slides.length;
        
        this.slides[this.currentSlide].classList.add('active');
        this.dots[this.currentSlide].classList.add('active');
    }

    nextSlide() {
        this.goToSlide(this.currentSlide + 1);
    }

    prevSlide() {
        this.goToSlide(this.currentSlide - 1);
    }

    startAutoSlide() {
        this.slideInterval = setInterval(() => this.nextSlide(), 5000);
    }

    stopAutoSlide() {
        clearInterval(this.slideInterval);
    }

    addEventListeners() {
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => {
                this.prevSlide();
                this.stopAutoSlide();
                this.startAutoSlide();
            });
        }

        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => {
                this.nextSlide();
                this.stopAutoSlide();
                this.startAutoSlide();
            });
        }
    }
}

// Initialize slider
document.addEventListener('DOMContentLoaded', () => {
    new HeroSlider();
});

// ===================================
// Smooth Scroll
// ===================================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// ===================================
// Scroll Animation
// ===================================
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -100px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

document.querySelectorAll('.feature-card, .brand-card').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(el);
});

// ===================================
// Form Validation
// ===================================
function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], textarea[required]');
    let isValid = true;

    inputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            input.classList.add('error');
        } else {
            input.classList.remove('error');
        }
    });

    // Validate email
    const emailInput = form.querySelector('input[type="email"]');
    if (emailInput && emailInput.value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailInput.value)) {
            isValid = false;
            emailInput.classList.add('error');
        }
    }

    // Validate phone
    const phoneInput = form.querySelector('input[type="tel"]');
    if (phoneInput && phoneInput.value) {
        const phoneRegex = /^[0-9]{9,}$/;
        if (!phoneRegex.test(phoneInput.value.replace(/\s/g, ''))) {
            isValid = false;
            phoneInput.classList.add('error');
        }
    }

    return isValid;
}

// ===================================
// Contact Form Handler
// ===================================
const contactForm = document.querySelector('#contactForm');
if (contactForm) {
    contactForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!validateForm(contactForm)) {
            alert('Por favor, complete todos los campos correctamente.');
            return;
        }

        const formData = new FormData(contactForm);
        const submitBtn = contactForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        submitBtn.disabled = true;
        submitBtn.textContent = 'Enviando...';

        try {
            const response = await fetch('php/contact.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                alert('¡Mensaje enviado correctamente! Nos pondremos en contacto con usted pronto.');
                contactForm.reset();
            } else {
                alert('Error al enviar el mensaje. Por favor, inténtelo de nuevo.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al enviar el mensaje. Por favor, inténtelo de nuevo.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });
}

// ===================================
// Vehicle Filter (for ocasion page)
// ===================================
class VehicleFilter {
    constructor() {
        this.filterButtons = document.querySelectorAll('.filter-btn');
        this.vehicles = [];
        
        if (this.filterButtons.length > 0) {
            this.init();
            this.refreshVehicles();
            
            // Escuchar cuando se carguen nuevos vehículos
            document.addEventListener('vehiclesLoaded', () => {
                this.refreshVehicles();
            });
        }
    }

    refreshVehicles() {
        this.vehicles = document.querySelectorAll('.vehicle-card');
    }

    init() {
        this.filterButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const filter = btn.dataset.filter;
                this.filterVehicles(filter);
                
                this.filterButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            });
        });
    }

    filterVehicles(filter) {
        this.vehicles.forEach(vehicle => {
            if (filter === 'all' || vehicle.dataset.category === filter) {
                vehicle.style.display = 'block';
                setTimeout(() => {
                    vehicle.style.opacity = '1';
                    vehicle.style.transform = 'scale(1)';
                }, 10);
            } else {
                vehicle.style.opacity = '0';
                vehicle.style.transform = 'scale(0.9)';
                setTimeout(() => {
                    vehicle.style.display = 'none';
                }, 300);
            }
        });
    }
}

// Initialize vehicle filter if on ocasion page
document.addEventListener('DOMContentLoaded', () => {
    new VehicleFilter();
});
