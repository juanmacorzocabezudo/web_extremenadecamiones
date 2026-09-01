# Extremeña de Camiones - Sitio Web

Sitio web profesional para Extremeña de Camiones, distribuidor oficial de Renault Trucks, Manitou y Landini McCormick en Extremadura.

## Características

- ✅ Diseño moderno y responsive
- ✅ Color rojo corporativo de Renault Trucks (#E1000F)
- ✅ Sistema de gestión de vehículos de ocasión
- ✅ Formulario de contacto funcional
- ✅ Páginas de marcas completas
- ✅ Compatible con hosting Windows (Arsys)
- ✅ PHP + HTML5 + CSS3 + JavaScript
- ✅ Imágenes optimizadas y organizadas
- ✅ Logo de empresa en header y favicon personalizado

## Imágenes

El sitio incluye **27 imágenes** organizadas en diferentes secciones:
- **Hero slider**: 3 imágenes rotativas en la página principal
- **Headers**: Imágenes de cabecera para cada página
- **Logos**: Logos de la empresa y marcas (Renault, Manitou, Landini)
- **Vehículos**: Imágenes de vehículos de ocasión
- **Favicon**: Logo de la empresa para pestaña del navegador

📖 **Ver guía completa**: [IMAGENES.md](IMAGENES.md)

## Estructura del Proyecto

```
extremenadecamiones/
├── index.html              # Página principal
├── presentacion.html       # Presentación de la empresa
├── postventa.html         # Servicios postventa
├── ocasion.html           # Vehículos de ocasión
├── contacto.html          # Formulario de contacto
├── renault.html           # Página Renault Trucks
├── manitou.html           # Página Manitou
├── landini-mccormick.html # Página Landini McCormick
├── css/
│   ├── styles.css         # Estilos principales
│   └── pages.css          # Estilos páginas internas
├── js/
│   ├── main.js            # JavaScript principal
│   └── vehicles.js        # Gestión de vehículos
├── php/
│   ├── contact.php        # Procesamiento formulario contacto
│   ├── get_vehicles.php   # Obtener listado de vehículos
│   └── save_vehicle.php   # Guardar vehículos
├── data/
│   └── vehicles.json      # Base de datos de vehículos
├── admin/
│   └── index.html         # Panel administración vehículos
└── images/                # Carpeta para imágenes
```

## Instalación en Hosting Windows (Arsys)

### 1. Subir Archivos

1. Conectar por FTP al hosting de Arsys
2. Subir todos los archivos al directorio raíz o subdirectorio deseado
3. Asegurarse de que la estructura de carpetas se mantiene

### 2. Configurar Permisos

- La carpeta `data/` debe tener permisos de escritura
- El servidor debe tener PHP habilitado (versión 7.0 o superior)

### 3. Configurar Email en contact.php

Editar el archivo `php/contact.php` y cambiar la línea:

```php
$to_email = "info@extremenadecamiones.es";
```

Por el email donde desea recibir los mensajes.

### 4. Añadir Imágenes

Subir las imágenes necesarias en la carpeta `images/`:

**Imágenes del Hero (slider principal):**
- hero1.jpg
- hero2.jpg
- hero3.jpg

**Imágenes de encabezados de páginas:**
- empresa-header.jpg
- empresa-instalaciones.jpg
- postventa-header.jpg
- taller.jpg
- ocasion-header.jpg
- ocasion-garantia.jpg
- contacto-header.jpg
- renault-header.jpg
- renault-truck.jpg
- manitou-header.jpg
- manitou-machine.jpg
- landini-header.jpg
- landini-tractor.jpg

**Logos de marcas:**
- renault-logo.png
- manitou-logo.png
- landini-mccormick-logo.png

**Vehículos (carpeta images/vehicles/):**
- renault-t-520.jpg
- renault-t-480.jpg
- (añadir más según vehículos en catálogo)

## Gestión de Vehículos de Ocasión

### Panel de Administración

Acceder a: `http://www.extremenadecamiones.es/admin/`

En el panel podrá:
- Ver todos los vehículos actuales
- Añadir nuevos vehículos
- Ver información detallada

### Añadir Vehículo Manualmente

Editar el archivo `data/vehicles.json` y añadir un nuevo objeto al array:

```json
{
    "id": 3,
    "category": "camiones",
    "title": "RENAULT T 460 4X2 Euro 6",
    "brand": "Renault Trucks",
    "image": "images/vehicles/renault-t-460.jpg",
    "badge": "OFERTA",
    "km": "450000",
    "year": "2020",
    "power": "460",
    "euro": "Euro 6",
    "priceOld": "50000",
    "priceCurrent": "45000",
    "description": "Descripción del vehículo",
    "featured": true
}
```

**Categorías disponibles:** camiones, furgonetas, maquinaria, tractores

**Badges disponibles:** OFERTA, NUEVO, RESERVADO, VENDIDO

## Configuración de SEO

Cada página incluye meta tags básicos. Para mejorar el SEO:

1. Añadir Google Analytics
2. Crear sitemap.xml
3. Configurar Google Search Console
4. Optimizar imágenes (tamaño y alt tags)

## Personalización de Colores

Los colores están definidos como variables CSS en `css/styles.css`:

```css
:root {
    --color-primary: #E1000F;        /* Rojo Renault */
    --color-primary-dark: #B8000C;   /* Rojo oscuro */
    --color-primary-light: #FF1A2A;  /* Rojo claro */
    --color-secondary: #1a1a1a;      /* Negro */
    /* ... */
}
```

Modificar estos valores para cambiar la paleta de colores.

## Funcionalidades del Formulario de Contacto

El formulario de contacto (`contacto.html`):
- Validación de campos obligatorios
- Validación de formato de email
- Envío mediante PHP mail()
- Respuesta asíncrona sin recargar página

**Nota:** Para que funcione correctamente, el servidor debe tener configurado el servicio de correo (SMTP).

## Compatibilidad

- ✅ Chrome, Firefox, Safari, Edge (últimas versiones)
- ✅ Responsive: Móvil, Tablet, Desktop
- ✅ PHP 7.0+
- ✅ Hosting Windows (IIS) y Linux (Apache)

## Mantenimiento

### Actualizar Contenido

1. **Textos:** Editar directamente los archivos HTML
2. **Imágenes:** Reemplazar en carpeta images/
3. **Vehículos:** Editar data/vehicles.json o usar panel admin
4. **Estilos:** Modificar css/styles.css y css/pages.css

### Backup

Hacer backup regular de:
- `data/vehicles.json` (base de datos de vehículos)
- Carpeta `images/` (todas las imágenes)

## Soporte

Para soporte técnico o consultas sobre el sitio web:
- Email: info@extremenadecamiones.es
- Teléfono: 924 37 17 18

## Próximas Mejoras Sugeridas

- [ ] Sistema de galería de imágenes para vehículos
- [ ] Filtros avanzados en página de ocasión
- [ ] Blog de noticias
- [ ] Integración con redes sociales
- [ ] Chat en vivo
- [ ] Sistema de citas online
- [ ] Comparador de vehículos
- [ ] Calculadora de financiación

---

**Versión:** 1.0  
**Fecha:** Julio 2026  
**Desarrollado para:** Extremeña de Camiones
