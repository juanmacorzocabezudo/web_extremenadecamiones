# Panel de Administración - Extremeña de Camiones

## Acceso al Panel

**URL:** `https://tu-dominio.com/admin/login.html`

### Credenciales de Acceso
- **Usuario:** `extremena`
- **Contraseña:** `Extremena@2026`

## Funcionalidades

### 1. Sistema de Login
- Página de login segura con validación de credenciales
- Sesión persistente mediante sessionStorage
- Redirección automática si no está autenticado
- Botón de cerrar sesión en el panel

### 2. Gestión de Vehículos

#### Añadir Nuevo Vehículo
Formulario completo con los siguientes campos:
- **Categoría:** Camiones, Furgonetas, Maquinaria, Tractores
- **Marca:** Renault Trucks, Manitou, Landini, McCormick, Otros
- **Título del Vehículo**
- **Año** (1990-2030)
- **Kilómetros**
- **Potencia** (CV)
- **Normativa Euro** (Euro 3-6)
- **Precio Actual** (obligatorio)
- **Precio Anterior** (opcional, para mostrar descuento)
- **Etiqueta:** Sin etiqueta, OFERTA, NUEVO, RESERVADO, VENDIDO
- **Imagen Principal:** URL de la imagen principal
- **Galería de Imágenes:** 4 campos para URLs adicionales
- **Descripción:** Texto descriptivo del vehículo
- **Características:** 10 campos para características destacadas
- **Destacar en portada:** Checkbox para vehículos destacados

#### Editar Vehículos Existentes
- Click en botón "Editar" de cualquier vehículo
- El formulario se rellena automáticamente con los datos
- Al guardar, actualiza el vehículo en lugar de crear uno nuevo

#### Eliminar Vehículos
- Click en botón "Eliminar"
- Confirmación de seguridad antes de eliminar
- Eliminación permanente del vehículo

### 3. Lista de Vehículos
- Visualización en tarjetas con imagen, datos principales y botones de acción
- Actualización automática después de cada operación
- Contador de imágenes en galería y características

## Archivos del Sistema

### Frontend
- `/admin/login.html` - Página de login
- `/admin/index.html` - Panel de administración

### Backend (PHP)
- `/php/get_vehicles.php` - Obtener lista de vehículos
- `/php/save_vehicle.php` - Guardar (añadir/editar) vehículo
- `/php/delete_vehicle.php` - Eliminar vehículo

### Datos
- `/data/vehicles.json` - Base de datos JSON de vehículos

## Estructura de Datos

Cada vehículo se guarda con la siguiente estructura:

```json
{
    "id": 1234567890,
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
    "priceOld": "54000",
    "priceCurrent": "48000",
    "description": "Descripción del vehículo...",
    "features": [
        "Característica 1",
        "Característica 2",
        ...
    ],
    "featured": true
}
```

## Requisitos del Servidor

- PHP 7.0 o superior
- Permisos de escritura en `/data/vehicles.json`
- Servidor web (Apache, Nginx, etc.)

## Notas de Seguridad

⚠️ **IMPORTANTE:**
- Las credenciales actuales están en el código JavaScript (solo para desarrollo)
- Para producción, implementar autenticación en el servidor con:
  - Passwords hasheados (bcrypt, Argon2)
  - Tokens de sesión en lugar de sessionStorage
  - HTTPS obligatorio
  - Protección CSRF
  - Rate limiting para prevenir ataques de fuerza bruta

## Mejoras Futuras

- Upload de imágenes directamente desde el panel
- Preview de imágenes al añadir URLs
- Editor WYSIWYG para descripciones
- Búsqueda y filtrado en la lista de vehículos
- Exportación de datos (CSV, Excel)
- Registro de cambios (audit log)
- Múltiples usuarios con diferentes permisos
- Backup automático de datos
