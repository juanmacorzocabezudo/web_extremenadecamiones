class CookieConsent {
    constructor() {
        this.storageKey = 'extremena_cookie_consent';
        this.maxAge = 24 * 60 * 60 * 1000;
        this.init();
    }

    init() {
        const consent = this.getConsent();
        this.showManageButton();
        if (!consent) {
            this.showBanner();
        }
    }

    getConsent() {
        try {
            const stored = localStorage.getItem(this.storageKey);
            if (!stored) return null;
            const consent = JSON.parse(stored);
            if (!consent.timestamp || Date.now() - Date.parse(consent.timestamp) > this.maxAge) {
                localStorage.removeItem(this.storageKey);
                return null;
            }
            return consent;
        } catch (error) {
            localStorage.removeItem(this.storageKey);
            return null;
        }
    }

    saveConsent(preferences) {
        localStorage.setItem(this.storageKey, JSON.stringify({
            necessary: true,
            analytics: Boolean(preferences.analytics),
            advertising: Boolean(preferences.advertising),
            timestamp: new Date().toISOString(),
            version: '2.0'
        }));
        this.hideBanner();
        this.showManageButton();
    }

    showBanner() {
        if (document.getElementById('cookie-banner')) return;

        const banner = document.createElement('div');
        banner.id = 'cookie-banner';
        banner.className = 'cookie-banner';
        banner.innerHTML = `
            <div class="cookie-banner-content">
                <div class="cookie-banner-text">
                    <h3>Uso de cookies</h3>
                    <p>Utilizamos cookies propias y de terceros para el funcionamiento del sitio y, con su consentimiento, para analizar la navegación. Puede aceptar, rechazar o configurar sus preferencias.</p>
                    <div class="cookie-preferences" id="cookie-preferences">
                        <label class="cookie-preference-row"><span>Cookies necesarias</span><input type="checkbox" checked disabled></label>
                        <label class="cookie-preference-row"><span>Cookies analíticas</span><input id="cookie-analytics" type="checkbox"></label>
                        <label class="cookie-preference-row"><span>Cookies publicitarias</span><input id="cookie-advertising" type="checkbox"></label>
                    </div>
                </div>
                <div class="cookie-banner-buttons">
                    <button id="cookie-accept" class="cookie-btn cookie-btn-accept" type="button">Aceptar todas</button>
                    <button id="cookie-reject" class="cookie-btn cookie-btn-reject" type="button">Rechazar</button>
                    <button id="cookie-configure" class="cookie-btn cookie-btn-more" type="button">Configurar</button>
                    <a href="politica-cookies.html" class="cookie-btn cookie-btn-more">Más información</a>
                </div>
            </div>
        `;
        document.body.appendChild(banner);

        document.getElementById('cookie-accept').addEventListener('click', () => {
            this.saveConsent({ analytics: true, advertising: true });
        });
        document.getElementById('cookie-reject').addEventListener('click', () => {
            this.saveConsent({ analytics: false, advertising: false });
        });
        document.getElementById('cookie-configure').addEventListener('click', (event) => {
            const preferences = document.getElementById('cookie-preferences');
            if (!preferences.classList.contains('show')) {
                preferences.classList.add('show');
                event.target.textContent = 'Guardar preferencias';
                return;
            }
            this.saveConsent({
                analytics: document.getElementById('cookie-analytics').checked,
                advertising: document.getElementById('cookie-advertising').checked
            });
        });

        setTimeout(() => banner.classList.add('show'), 100);
    }

    showManageButton() {
        if (document.getElementById('cookie-manage')) return;
        const legalLinks = document.querySelector('.footer-legal');
        if (!legalLinks) return;
        const button = document.createElement('button');
        button.id = 'cookie-manage';
        button.className = 'cookie-manage';
        button.type = 'button';
        button.textContent = 'Preferencias de cookies';
        button.addEventListener('click', () => {
            this.showBanner();
            document.getElementById('cookie-banner').classList.add('show');
        });
        legalLinks.appendChild(button);
    }

    hideBanner() {
        const banner = document.getElementById('cookie-banner');
        if (banner) banner.remove();
    }

    resetConsent() {
        localStorage.removeItem(this.storageKey);
        location.reload();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    window.cookieConsent = new CookieConsent();
});
