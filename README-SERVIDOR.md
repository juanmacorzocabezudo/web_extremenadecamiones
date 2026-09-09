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

## Configuración del formulario de contacto

El formulario usa SMTP autenticado de Arsys y necesita el archivo `config.local.php` en la raíz del sitio. Este archivo está excluido de Git por seguridad, por lo que hay que subirlo manualmente al hosting junto con el resto de los archivos.

Debe contener la configuración SMTP de `info@extremenadecamiones.es`, con el servidor `smtp.serviciodecorreo.es`, el puerto `465`, la contraseña de la cuenta y el destinatario interno `info@extremenadecamiones.es`.

Si el formulario sigue mostrando un error después de subirlo, revisa el `error_log` del hosting: el endpoint registra si falta la configuración o en qué fase responde con error el servidor SMTP.

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
