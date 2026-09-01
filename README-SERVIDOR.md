# Cómo iniciar el servidor web

Para que el panel de administración funcione correctamente, necesitas tener un servidor PHP en ejecución.

## Opción 1: Servidor PHP integrado (Recomendado)

Abre una terminal en la carpeta raíz del proyecto y ejecuta:

```bash
cd "/Users/juanmariacorzo/Documents/Extremeña de camiones/Web"
php -S localhost:8000
```

Luego accede a:
- **Sitio web**: http://localhost:8000/index.html
- **Panel admin**: http://localhost:8000/admin/login.html

## Opción 2: MAMP/XAMPP

1. Copia la carpeta del proyecto a la carpeta `htdocs` (XAMPP) o `htdocs` (MAMP)
2. Inicia Apache desde el panel de control
3. Accede a: http://localhost/Web/index.html

## Opción 3: Servidor Apache local

Si tienes Apache instalado en macOS:

1. Copia el proyecto a `/Library/WebServer/Documents/`
2. Inicia Apache: `sudo apachectl start`
3. Accede a: http://localhost/Web/index.html

## Credenciales del Admin

- **Usuario**: extremena
- **Contraseña**: Extremena@2026

## Solución de problemas

### Error "Failed to fetch"
Este error significa que el servidor PHP no está ejecutándose. Sigue los pasos anteriores para iniciar el servidor.

### Las imágenes no se suben
Verifica que la carpeta `images/vehicles/` tenga permisos de escritura:
```bash
chmod -R 755 images/vehicles/
```

### Los cambios no se guardan
Verifica que el archivo `data/vehicles.json` tenga permisos de escritura:
```bash
chmod 666 data/vehicles.json
```
