/**
 * Sistema de gestión de cookies y banner RGPD
 * Extremeña de Camiones - Cumplimiento legal España
 */

class CookieConsent {
    constructor() {
        this.cookieName = 'extremena_cookie_consent';
        this.cookieExpireDays = 365;
        this.init();
    }

    init() {
        // Verificar si ya existe consentimiento
        if (!this.hasConsent()) {
            this.showBanner();
        }
    }

    hasConsent() {
        return localStorage.getItem(this.cookieName) !== null;
    }

    getConsent() {
        const consent = localStorage.getItem(this.cookieName);
        return consent ? JSON.parse(consent) : null;
    }

    setConsent(accepted) {
        const consentData = {
            accepted: accepted,
            timestamp: new Date().toISOString(),
            version: '1.0'
        };
        localStorage.setItem(this.cookieName, JSON.stringify(consentData));
        
        // Si se aceptan, cargar scripts de terceros (Analytics, etc.)
        if (accepted) {
            this.loadAnalytics();
        }
    }

    showBanner() {
        // Crear el banner si no existe
        if (document.getElementById('cookie-banner')) return;

        const banner = document.createElement('div');
        banner.id = 'cookie-banner';
        banner.className = 'cookie-banner';
        banner.innerHTML = `
            <div class="cookie-banner-content">
                <div class="cookie-banner-text">
                    <h3>Uso de Cookies</h3>
                    <p>Utilizamos cookies propias y de terceros para mejorar nuestros servicios y mostrarle publicidad relacionada con sus preferencias mediante el análisis de sus hábitos de navegación. Si continúa navegando, consideramos que acepta su uso.</p>
                </div>
                <div class="cookie-banner-buttons">
                    <button id="cookie-accept" class="cookie-btn cookie-btn-accept">Aceptar</button>
                    <button id="cookie-reject" class="cookie-btn cookie-btn-reject">Rechazar</button>
                    <a href="politica-cookies.html" class="cookie-btn cookie-btn-more">Más información</a>
                </div>
            </div>
        `;

        document.body.appendChild(banner);

        // Event listeners
        document.getElementById('cookie-accept').addEventListener('click', () => {
            this.acceptCookies();
        });

        document.getElementById('cookie-reject').addEventListener('click', () => {
            this.rejectCookies();
        });

        // Mostrar el banner con animación
        setTimeout(() => {
            banner.classList.add('show');
        }, 500);
    }

    hideBanner() {
        const banner = document.getElementById('cookie-banner');
        if (banner) {
            banner.classList.remove('show');
            setTimeout(() => {
                banner.remove();
            }, 300);
        }
    }

    acceptCookies() {
        this.setConsent(true);
        this.hideBanner();
    }

    rejectCookies() {
        this.setConsent(false);
        this.hideBanner();
    }

    loadAnalytics() {
        // Aquí se cargarían scripts de Google Analytics u otros
        // Solo si el usuario ha aceptado
        console.log('Analytics cargado - Usuario aceptó cookies');
        
        // Ejemplo: Google Analytics
        // (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        // (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        // m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        // })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        // ga('create', 'UA-XXXXXXXX-X', 'auto');
        // ga('send', 'pageview');
    }

    // Método para resetear el consentimiento (útil para testing)
    resetConsent() {
        localStorage.removeItem(this.cookieName);
        location.reload();
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    window.cookieConsent = new CookieConsent();
});
