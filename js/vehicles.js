// ===================================
// Load and Display Vehicles
// ===================================
console.log('vehicles.js cargado');

class VehicleManager {
    constructor() {
        this.vehiclesContainer = document.getElementById('vehiclesContainer');
        this.vehicles = [];
        this.modal = document.getElementById('vehicleModal');
        
        console.log('VehicleManager inicializado');
        console.log('Container:', this.vehiclesContainer);
        console.log('Modal:', this.modal);
        
        if (this.vehiclesContainer) {
            this.setupModal();
            this.loadVehicles();
        }
    }

    async loadVehicles() {
        // Datos embebidos como fallback
        const fallbackData = {
            "vehicles": [
                {
                    "id": 1,
                    "category": "camiones",
                    "title": "RENAULT T 520 4X2 Euro 6 Step E",
                    "brand": "Renault Trucks",
                    "image": "images/vehicles/renault-t-520.jpg",
                    "gallery": [
                        "images/vehicles/renault-t-520.jpg",
                        "images/camion1.jpeg",
                        "images/camion2.jpeg",
                        "images/renault-truck.jpg"
                    ],
                    "badge": "OFERTA",
                    "km": "576884",
                    "year": "2021",
                    "power": "520",
                    "euro": "Euro 6",
                    "tonnage": "26",
                    "priceOld": "54000",
                    "priceCurrent": "48000",
                    "commercial": "Departamento Comercial",
                    "phone": "924371718",
                    "description": "Camión tractora Renault T 520 en excelente estado, con todas las revisiones al día y listo para trabajar. Motor potente y fiable, cabina cómoda con todos los extras. Historial completo de mantenimiento disponible.",
                    "features": [
                        "Vehículo revisado y certificado",
                        "ITV en vigor hasta 2026",
                        "Garantía de 12 meses incluida",
                        "Cabina con litera",
                        "Control de crucero adaptativo",
                        "Sistema de frenado ABS + EBS",
                        "Aire acondicionado automático",
                        "Cámara de marcha atrás",
                        "Ordenador de a bordo",
                        "Posibilidad de financiación"
                    ],
                    "featured": true
                },
                {
                    "id": 2,
                    "category": "camiones",
                    "title": "RENAULT T High 480 4X2 Euro 6 Step E",
                    "brand": "Renault Trucks",
                    "image": "images/vehicles/renault-t-480.jpg",
                    "gallery": [
                        "images/vehicles/renault-t-480.jpg",
                        "images/renault-truck.jpg",
                        "images/camion1.jpeg",
                        "images/camion2.jpeg"
                    ],
                    "badge": "RESERVADO",
                    "km": "567000",
                    "year": "2022",
                    "power": "480",
                    "euro": "Euro 6",
                    "tonnage": "26",
                    "priceCurrent": "64000",
                    "commercial": "Departamento Comercial",
                    "phone": "924371718",
                    "description": "Vehículo reservado. Renault T High en perfectas condiciones, con mantenimiento oficial Renault Trucks. Cabina alta con todas las comodidades para rutas de larga distancia.",
                    "features": [
                        "Vehículo reservado",
                        "Cabina alta con litera XXL",
                        "Sistema Optiroll integrado",
                        "Nevera incorporada",
                        "Calefacción estacionaria",
                        "Sistema multimedia completo",
                        "Asientos con suspensión neumática",
                        "Luces LED de serie",
                        "Mantenimiento oficial",
                        "Próxima entrega programada"
                    ],
                    "featured": true
                },
                {
                    "id": 1784388774842,
                    "category": "camiones",
                    "title": "Camión Renault Premium 460",
                    "brand": "Renault Trucks",
                    "image": "images/camion1.jpeg",
                    "gallery": [
                        "images/camion1.jpeg",
                        "images/camion2.jpeg",
                        "images/renault-truck.jpg"
                    ],
                    "badge": "OFERTA",
                    "km": "156000",
                    "year": "2022",
                    "power": "500",
                    "euro": "Euro 6",
                    "tonnage": "18",
                    "priceOld": "60000",
                    "priceCurrent": "54000",
                    "commercial": "Departamento Comercial",
                    "phone": "924371718",
                    "description": "Vehículo de ocasión Renault en excelente estado. Ideal para transporte de larga distancia. Motor potente y económico, bajo consumo de combustible. Perfecto para profesionales exigentes.",
                    "features": [
                        "Revisión completa realizada",
                        "ITV pasada recientemente",
                        "Garantía de 6 meses",
                        "Bajo consumo de combustible",
                        "Cabina espaciosa",
                        "Climatizador automático",
                        "Sistema de navegación GPS",
                        "Bluetooth integrado",
                        "Mantenimiento al día",
                        "Financiación disponible"
                    ],
                    "featured": false
                }
            ]
        };

        try {
            // Intentar cargar desde PHP primero
            const response = await fetch('php/get_vehicles.php');
            
            if (response.ok) {
                const data = await response.json();
                if (data.success && data.vehicles) {
                    console.log('Vehículos cargados desde PHP');
                    this.vehicles = data.vehicles;
                    this.renderVehicles(this.vehicles);
                    return;
                }
            }
        } catch (error) {
            console.log('PHP no disponible:', error.message);
        }

        // Si PHP falla, intentar cargar el JSON directamente
        try {
            const response = await fetch('data/vehicles.json');
            
            if (response.ok) {
                const data = await response.json();
                if (data.vehicles) {
                    console.log('Vehículos cargados desde JSON');
                    this.vehicles = data.vehicles;
                    this.renderVehicles(this.vehicles);
                    return;
                }
            }
        } catch (error) {
            console.log('JSON no disponible:', error.message);
        }

        // Si todo falla, usar datos embebidos
        console.log('Usando datos embebidos (fallback)');
        this.vehicles = fallbackData.vehicles;
        this.renderVehicles(this.vehicles);
    }

    setupModal() {
        if (!this.modal) {
            console.error('Modal no encontrado en setupModal');
            return;
        }

        console.log('Configurando event listeners del modal');

        // Cerrar modal al hacer clic en overlay o botón cerrar
        const overlay = this.modal.querySelector('.vehicle-modal-overlay');
        const closeBtn = this.modal.querySelector('.vehicle-modal-close');
        
        if (overlay) {
            overlay.addEventListener('click', () => {
                console.log('Clic en overlay, cerrando modal');
                this.closeModal();
            });
        }
        
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                console.log('Clic en botón cerrar, cerrando modal');
                this.closeModal();
            });
        }
        
        // Cerrar con tecla ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.modal.classList.contains('active')) {
                console.log('Tecla ESC presionada, cerrando modal');
                this.closeModal();
            }
        });

        console.log('Modal configurado correctamente');
    }

    openModal(vehicle) {
        if (!this.modal) {
            console.error('El modal no existe en el DOM');
            return;
        }

        console.log('Abriendo modal con datos:', vehicle);

        // Cargar datos del vehículo en el modal
        document.getElementById('modalCategory').textContent = vehicle.category.toUpperCase();
        document.getElementById('modalTitle').textContent = vehicle.title;
        document.getElementById('modalDescription').textContent = vehicle.description || 'Sin descripción disponible';
        
        // Precio
        const priceOldEl = document.getElementById('modalPriceOld');
        const priceCurrentEl = document.getElementById('modalPriceCurrent');
        
        if (vehicle.priceOld) {
            priceOldEl.textContent = this.formatPrice(vehicle.priceOld);
            priceOldEl.style.display = 'inline';
        } else {
            priceOldEl.style.display = 'none';
        }
        priceCurrentEl.textContent = this.formatPrice(vehicle.priceCurrent);
        
        // Especificaciones
        const specsGrid = document.getElementById('modalSpecs');
        specsGrid.innerHTML = `
            <div class="spec-item">
                <strong>Kilómetros</strong>
                <span>${this.formatKm(vehicle.km)}</span>
            </div>
            <div class="spec-item">
                <strong>Año</strong>
                <span>${vehicle.year}</span>
            </div>
            <div class="spec-item">
                <strong>Potencia</strong>
                <span>${vehicle.power} CV</span>
            </div>
            <div class="spec-item">
                <strong>Normativa</strong>
                <span>${vehicle.euro}</span>
            </div>
            <div class="spec-item">
                <strong>Toneladas</strong>
                <span>${vehicle.tonnage || 'N/D'} t</span>
            </div>
            <div class="spec-item">
                <strong>Marca</strong>
                <span>${vehicle.brand}</span>
            </div>
            <div class="spec-item">
                <strong>Estado</strong>
                <span>${vehicle.badge || 'Disponible'}</span>
            </div>
            <div class="spec-item">
                <strong>Comercial</strong>
                <span>${vehicle.commercial || 'No especificado'}</span>
            </div>
            <div class="spec-item">
                <strong>Teléfono</strong>
                <span>${vehicle.phone || 'No especificado'}</span>
            </div>
        `;
        
        // Galería de imágenes
        const mainImage = document.getElementById('modalMainImage');
        const thumbnails = document.getElementById('modalThumbnails');
        
        // Crear array de imágenes (incluir la principal + imágenes adicionales si existen)
        const images = vehicle.gallery || [vehicle.image];
        if (!vehicle.gallery) {
            images.push(vehicle.image); // Duplicar para tener al menos 2 imágenes
        }
        
        // Cargar imagen principal
        mainImage.src = images[0];
        mainImage.alt = vehicle.title;
        
        // Cargar thumbnails
        thumbnails.innerHTML = images.map((img, index) => `
            <div class="vehicle-thumbnail ${index === 0 ? 'active' : ''}" data-image="${img}">
                <img src="${img}" alt="${vehicle.title} ${index + 1}">
            </div>
        `).join('');
        
        // Event listeners para thumbnails
        thumbnails.querySelectorAll('.vehicle-thumbnail').forEach(thumb => {
            thumb.addEventListener('click', () => {
                mainImage.src = thumb.dataset.image;
                thumbnails.querySelectorAll('.vehicle-thumbnail').forEach(t => t.classList.remove('active'));
                thumb.classList.add('active');
            });
        });
        
        // Características
        const featuresList = document.getElementById('modalFeaturesList');
        const features = vehicle.features || [
            'Vehículo revisado',
            'ITV al día',
            'Garantía incluida',
            'Posibilidad de financiación',
            'Entrega inmediata',
            'Servicio postventa'
        ];
        
        featuresList.innerHTML = features.map(f => `<li>${f}</li>`).join('');

        // Botones de contacto dinámicos según el teléfono del vehículo
        const callBtn = document.getElementById('modalCallBtn');
        const whatsappBtn = document.getElementById('modalWhatsappBtn');
        const phoneForTel = this.normalizePhoneForTel(vehicle.phone);
        const phoneForWhatsApp = this.normalizePhoneForWhatsApp(vehicle.phone);
        const message = encodeURIComponent(`Hola, estoy interesado en el vehículo ${vehicle.title}`);

        if (callBtn) {
            if (phoneForTel) {
                callBtn.href = `tel:${phoneForTel}`;
                callBtn.style.display = 'flex';
            } else {
                callBtn.href = 'tel:+34924371718';
                callBtn.style.display = 'flex';
            }
        }

        if (whatsappBtn) {
            if (phoneForWhatsApp) {
                whatsappBtn.href = `https://wa.me/${phoneForWhatsApp}?text=${message}`;
                whatsappBtn.style.display = 'flex';
            } else {
                whatsappBtn.style.display = 'none';
            }
        }
        
        // Mostrar modal
        console.log('Agregando clase active al modal');
        this.modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        console.log('Modal debería estar visible ahora');
    }

    closeModal() {
        console.log('Cerrando modal');
        this.modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    renderVehicles(vehicles) {
        if (!vehicles || vehicles.length === 0) {
            console.warn('No hay vehículos para renderizar');
            return;
        }

        console.log(`Renderizando ${vehicles.length} vehículos`);

        // Limpiar contenedor
        this.vehiclesContainer.innerHTML = '';

        // Renderizar cada vehículo
        vehicles.forEach(vehicle => {
            const vehicleCard = this.createVehicleCard(vehicle);
            this.vehiclesContainer.appendChild(vehicleCard);
        });

        console.log('Vehículos renderizados correctamente');

        // Notificar al sistema de filtros que hay nuevos vehículos
        const event = new CustomEvent('vehiclesLoaded');
        document.dispatchEvent(event);
    }

    createVehicleCard(vehicle) {
        const card = document.createElement('div');
        card.className = 'vehicle-card';
        card.setAttribute('data-category', vehicle.category);

        const commercial = vehicle.commercial || 'No especificado';
        const phone = vehicle.phone || 'No especificado';

        const priceOldHTML = vehicle.priceOld ? 
            `<span class="price-old">${this.formatPrice(vehicle.priceOld)}</span>` : '';

        const badgeHTML = vehicle.badge ? 
            `<div class="vehicle-badge">${vehicle.badge}</div>` : '';

        card.innerHTML = `
            <div class="vehicle-image">
                <img src="${vehicle.image}" alt="${vehicle.title}">
                ${badgeHTML}
            </div>
            <div class="vehicle-content">
                <div class="vehicle-category">${vehicle.category.toUpperCase()}</div>
                <h3 class="vehicle-title">${vehicle.title}</h3>
                <div class="vehicle-specs">
                    <div class="vehicle-spec">
                        <strong>Km:</strong>
                        <span>${this.formatKm(vehicle.km)}</span>
                    </div>
                    <div class="vehicle-spec">
                        <strong>Año:</strong>
                        <span>${vehicle.year}</span>
                    </div>
                    <div class="vehicle-spec">
                        <strong>Potencia:</strong>
                        <span>${vehicle.power} CV</span>
                    </div>
                    <div class="vehicle-spec">
                        <strong>Toneladas:</strong>
                        <span>${vehicle.tonnage || 'N/D'} t</span>
                    </div>
                    <div class="vehicle-spec">
                        <strong>Comercial:</strong>
                        <span>${commercial}</span>
                    </div>
                    <div class="vehicle-spec">
                        <strong>Teléfono:</strong>
                        <span>${phone}</span>
                    </div>
                </div>
                <div class="vehicle-price">
                    ${priceOldHTML}
                    <span class="price-current">${this.formatPrice(vehicle.priceCurrent)}</span>
                </div>
                <button class="btn btn-primary vehicle-info-btn">Más información</button>
            </div>
        `;

        // Agregar event listener al botón
        const infoBtn = card.querySelector('.vehicle-info-btn');
        infoBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            console.log('Abriendo modal para:', vehicle.title);
            this.openModal(vehicle);
        });

        // También hacer clic en toda la tarjeta
        card.style.cursor = 'pointer';
        card.addEventListener('click', (e) => {
            // No abrir si se hizo clic en el botón (ya se maneja arriba)
            if (!e.target.classList.contains('vehicle-info-btn') && 
                !e.target.closest('.vehicle-info-btn')) {
                console.log('Clic en tarjeta, abriendo modal para:', vehicle.title);
                this.openModal(vehicle);
            }
        });

        return card;
    }

    formatPrice(price) {
        return `${parseInt(price).toLocaleString('es-ES')} €`;
    }

    formatKm(km) {
        return `${parseInt(km).toLocaleString('es-ES')} km`;
    }

    normalizePhoneForTel(phone) {
        if (!phone) {
            return '';
        }

        const raw = String(phone).trim();
        const hasPlus = raw.startsWith('+');
        const digits = raw.replace(/\D/g, '');

        if (!digits) {
            return '';
        }

        if (hasPlus) {
            return `+${digits}`;
        }

        if (digits.length === 9) {
            return `+34${digits}`;
        }

        return `+${digits}`;
    }

    normalizePhoneForWhatsApp(phone) {
        if (!phone) {
            return '';
        }

        let digits = String(phone).replace(/\D/g, '');

        if (!digits) {
            return '';
        }

        if (digits.startsWith('00')) {
            digits = digits.slice(2);
        }

        if (digits.length === 9) {
            return `34${digits}`;
        }

        return digits;
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM cargado, inicializando VehicleManager');
    const manager = new VehicleManager();
    
    // Agregar event listeners a los botones estáticos del HTML como fallback
    setTimeout(() => {
        const staticButtons = document.querySelectorAll('.vehicle-card .vehicle-info-btn');
        console.log(`Encontrados ${staticButtons.length} botones estáticos`);
        
        if (staticButtons.length > 0 && manager.vehicles.length > 0) {
            staticButtons.forEach((btn, index) => {
                if (manager.vehicles[index]) {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        console.log('Clic en botón estático, abriendo modal');
                        manager.openModal(manager.vehicles[index]);
                    });
                }
            });
            
            // También agregar event listeners a las tarjetas estáticas
            const staticCards = document.querySelectorAll('.vehicle-card');
            staticCards.forEach((card, index) => {
                if (manager.vehicles[index]) {
                    card.style.cursor = 'pointer';
                    card.addEventListener('click', (e) => {
                        if (!e.target.classList.contains('vehicle-info-btn') && 
                            !e.target.closest('.vehicle-info-btn')) {
                            console.log('Clic en tarjeta estática, abriendo modal');
                            manager.openModal(manager.vehicles[index]);
                        }
                    });
                }
            });
        }
    }, 1000);
});
