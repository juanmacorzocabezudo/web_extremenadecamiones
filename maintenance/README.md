# Página de Mantenimiento - Extremeña de Camiones

Esta carpeta contiene una página de mantenimiento simple y profesional para publicar temporalmente en el dominio de Arsys.

## Contenido

- `index.html` - Página principal de mantenimiento
- `css/maintenance.css` - Estilos de la página
- Se utilizan recursos existentes:
  - `../images/logoextremena.png` - Logo de la empresa
  - `../images/favicon.png` - Icono del sitio

## Características

✅ Diseño moderno y responsive
✅ Logo de Extremeña de Camiones
✅ Mensaje claro de mantenimiento
✅ Información de contacto (teléfono y email)
✅ Animaciones suaves
✅ Compatible con móviles y tablets
✅ Redirección automática a HTTPS

## Instalación en Arsys

### Opción 1: Subir solo la carpeta maintenance (Recomendado)

1. Accede al panel de control de Arsys
2. Ve al administrador de archivos (FTP/FileManager)
3. Sube todo el contenido de la carpeta `maintenance` a la raíz del dominio
4. También necesitas subir la carpeta `images` que está en el nivel superior
5. La estructura debe quedar:
   ```
   /
   ├── index.html (el de maintenance)
   ├── css/
   │   └── maintenance.css
   └── images/
       ├── logoextremena.png
       └── favicon.png
   ```

### Opción 2: Renombrar el index actual

1. En el servidor de Arsys, renombra el `index.html` actual a `index.html.backup`
2. Copia el archivo `maintenance/index.html` y súbelo como nuevo `index.html` en la raíz
3. Copia la carpeta `maintenance/css` al servidor
4. Asegúrate de que la carpeta `images` ya esté en el servidor

### Opción 3: Subir todo el proyecto

Si quieres mantener ambas versiones en el servidor:
1. Sube toda la carpeta del proyecto a Arsys
2. La página de mantenimiento estará accesible en: `tudominio.com/maintenance/`
3. Cuando quieras activarla, simplemente renombra los archivos index como en la Opción 2

## Restaurar el sitio web normal

Cuando quieras volver a la web principal:

1. Si usaste la Opción 1:
   - Elimina el `index.html` de mantenimiento
   - Restaura el `index.html` original del proyecto principal

2. Si usaste la Opción 2:
   - Renombra `index.html` (mantenimiento) a `maintenance.html`
   - Renombra `index.html.backup` a `index.html`

## Personalización

Puedes personalizar los mensajes editando el archivo `index.html`:

- **Título principal**: Línea 27 - `<h1>Sitio en Mantenimiento</h1>`
- **Mensaje principal**: Línea 29 - `<p class="main-message">Estamos trabajando...`
- **Mensaje secundario**: Línea 30 - `<p class="sub-message">...estará disponible próximamente`
- **Teléfono**: Línea 35 - `tel:+34924371718`
- **Email**: Línea 42 - `mailto:info@extremenadecamiones.es`

## Colores y estilos

Los colores principales se pueden modificar en `css/maintenance.css`:

- **Gradiente de fondo**: Línea 21 (púrpura/violeta)
- **Color de enlaces**: `#667eea` (azul/púrpura)
- **Puedes cambiar al color corporativo de Renault o Manitou si lo prefieres**

## Vista previa local

Para ver la página antes de subirla:

1. Abre el archivo `maintenance/index.html` directamente en tu navegador
2. O usa un servidor local desde la carpeta raíz del proyecto

## Soporte

Si tienes problemas con la instalación en Arsys, verifica:

- Que las rutas a las imágenes sean correctas (`../images/...`)
- Que la carpeta `images` esté en el nivel correcto
- Que los permisos de los archivos en el servidor permitan lectura
