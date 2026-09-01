# 📸 Guía de Imágenes del Sitio Web

## Resumen

Se han instalado y organizado todas las imágenes del sitio web a partir de las 8 imágenes originales proporcionadas:

- **2 imágenes de camiones** (camion1.jpeg, camion2.jpeg)
- **2 imágenes de instalaciones** (empresa.jpg, empresa2.jpg)
- **4 logos** (logoextremena.png, renault.png, manitou.png, landini-mccornik.png)

## Estructura de Carpetas

```
images/
├── camion1.jpeg              # Original
├── camion2.jpeg              # Original
├── empresa.jpg               # Original
├── empresa2.jpg              # Original
├── logoextremena.png         # Original - Logo de la empresa
├── renault.png               # Original - Logo Renault
├── manitou.png               # Original - Logo Manitou
├── landini-mccornik.png      # Original - Logo Landini
│
├── favicon.png               # Copia de logoextremena.png
│
├── renault-logo.png          # Logo para tarjetas de marca
├── manitou-logo.png          # Logo para tarjetas de marca
├── landini-mccormick-logo.png # Logo para tarjetas de marca
│
├── hero1.jpg                 # Slider principal (camion1)
├── hero2.jpg                 # Slider principal (camion2)
├── hero3.jpg                 # Slider principal (empresa)
│
├── empresa-header.jpg        # Cabecera página Presentación
├── empresa-instalaciones.jpg # Contenido página Presentación
│
├── postventa-header.jpg      # Cabecera página Posventa
├── taller.jpg                # Contenido página Posventa
│
├── ocasion-header.jpg        # Cabecera página Ocasión
├── ocasion-garantia.jpg      # Contenido página Ocasión
│
├── contacto-header.jpg       # Cabecera página Contacto
│
├── renault-header.jpg        # Cabecera página Renault
├── renault-truck.jpg         # Contenido página Renault
│
├── manitou-header.jpg        # Cabecera página Manitou
├── manitou-machine.jpg       # Contenido página Manitou
│
├── landini-header.jpg        # Cabecera página Landini
├── landini-tractor.jpg       # Contenido página Landini
│
└── vehicles/
    ├── renault-t-520.jpg     # Vehículo de ocasión
    └── renault-t-480.jpg     # Vehículo de ocasión
```

## Uso de Imágenes por Página

### Página Principal (index.html)
- **Hero Slider**: hero1.jpg, hero2.jpg, hero3.jpg
- **Tarjetas de Marcas**: renault-logo.png, manitou-logo.png, landini-mccormick-logo.png
- **Header**: logoextremena.png

### Presentación (presentacion.html)
- **Header**: empresa-header.jpg
- **Contenido**: empresa-instalaciones.jpg

### Posventa (postventa.html)
- **Header**: postventa-header.jpg
- **Contenido**: taller.jpg

### Ocasión (ocasion.html)
- **Header**: ocasion-header.jpg
- **Contenido**: ocasion-garantia.jpg
- **Vehículos**: Se cargan dinámicamente desde /images/vehicles/

### Contacto (contacto.html)
- **Header**: contacto-header.jpg

### Renault (renault.html)
- **Header**: renault-header.jpg
- **Contenido**: renault-truck.jpg
- **Logo**: renault-logo.png

### Manitou (manitou.html)
- **Header**: manitou-header.jpg
- **Contenido**: manitou-machine.jpg
- **Logo**: manitou-logo.png

### Landini McCormick (landini-mccormick.html)
- **Header**: landini-header.jpg
- **Contenido**: landini-tractor.jpg
- **Logo**: landini-mccormick-logo.png

## Logo en el Header

El logo de la empresa (logoextremena.png) se muestra en el header de todas las páginas junto al texto "EXTREMEÑA DE CAMIONES".

Dimensiones del logo en el header:
- **Desktop**: 50px de alto
- **Mobile**: 40px de alto

## Favicon

El favicon del sitio (favicon.png) es una copia del logo de la empresa y se muestra en:
- Pestaña del navegador
- Marcadores
- Barra de direcciones

## Añadir Nuevas Imágenes

### Para añadir nuevos vehículos:

1. Coloque la imagen en la carpeta `/images/vehicles/`
2. Nombre recomendado: `marca-modelo.jpg` (por ejemplo: `renault-d-wide.jpg`)
3. Tamaño recomendado: Ancho entre 800-1200px
4. Agregue el vehículo en el panel de administración (http://localhost:8000/admin/)

### Para cambiar imágenes del slider:

1. Coloque las nuevas imágenes en `/images/`
2. Nombre: `hero1.jpg`, `hero2.jpg`, `hero3.jpg`
3. Tamaño recomendado: 1920x800px (mínimo 1200x600px)

### Para cambiar headers de páginas:

1. Coloque la imagen en `/images/`
2. Nombre según la página: `[pagina]-header.jpg`
3. Tamaño recomendado: 1920x400px (mínimo 1200x300px)

## Optimización de Imágenes

Para mejorar el rendimiento del sitio, se recomienda:

1. **Comprimir las imágenes** antes de subirlas
   - Usar herramientas como TinyPNG, ImageOptim o Squoosh
   - Objetivo: Reducir 50-70% del tamaño sin perder calidad visible

2. **Formato recomendado**:
   - JPG para fotografías
   - PNG para logos con transparencia
   - WebP para mejor compresión (requiere conversión)

3. **Dimensiones óptimas**:
   - Hero slider: 1920x800px
   - Page headers: 1920x400px
   - Vehículos: 800x600px
   - Logos: 200px de ancho máximo

## Actualizar Imágenes en Arsys

Cuando suba el sitio a Arsys:

1. Suba toda la carpeta `/images/` vía FTP
2. Mantenga la misma estructura de carpetas
3. Verifique que los permisos sean 755 para carpetas y 644 para archivos

## Solución de Problemas

### Las imágenes no se ven:
- Verifique que la ruta sea correcta (case-sensitive en algunos servidores)
- Compruebe los permisos de archivos
- Revise la consola del navegador (F12) para errores

### Las imágenes se ven pixeladas:
- Use imágenes de mayor resolución
- Tamaño mínimo recomendado arriba

### El sitio carga lento:
- Comprima las imágenes
- Considere usar WebP
- Use lazy loading para imágenes fuera de la vista inicial
