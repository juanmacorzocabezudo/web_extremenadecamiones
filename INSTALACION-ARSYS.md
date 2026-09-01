# Guía de Instalación en Hosting Windows de Arsys

## 📋 Requisitos Previos

- Hosting Windows contratado en Arsys
- Dominio www.extremenadecamiones.es configurado
- Acceso FTP al hosting
- Panel de control de Arsys

## 🚀 Pasos de Instalación

### 1. Preparar los Archivos

1. Descargar todos los archivos del proyecto
2. Verificar que la estructura de carpetas esté completa
3. Preparar las imágenes necesarias (ver lista en README.md)

### 2. Conectar por FTP

**Datos de conexión (obtener del panel de Arsys):**
- **Host:** ftp.extremenadecamiones.es (o el que proporcione Arsys)
- **Usuario:** Su usuario FTP
- **Contraseña:** Su contraseña FTP
- **Puerto:** 21 (FTP) o 22 (SFTP)

**Programas FTP recomendados:**
- FileZilla (https://filezilla-project.org/)
- WinSCP (https://winscp.net/)
- Cyberduck (https://cyberduck.io/)

### 3. Subir Archivos

1. Conectar al FTP
2. Navegar al directorio raíz de su sitio (normalmente `/httpdocs` o `/www`)
3. Subir TODOS los archivos y carpetas del proyecto manteniendo la estructura:

```
/httpdocs (o /www)
├── index.html
├── presentacion.html
├── postventa.html
├── ocasion.html
├── contacto.html
├── renault.html
├── manitou.html
├── landini-mccormick.html
├── 404.html
├── .htaccess
├── css/
├── js/
├── php/
├── data/
├── admin/
└── images/
```

### 4. Configurar Permisos (Importante)

En hosting Windows de Arsys, verificar permisos:

**Carpeta `data/`:**
- Debe tener permisos de **escritura**
- Verificar en el panel de control de Arsys o mediante FTP
- Si usa FileZilla: Click derecho > Permisos > Marcar "Escritura"

**Archivo `vehicles.json`:**
- Permisos de lectura y escritura (666 o similar)

### 5. Configurar PHP

#### 5.1 Verificar versión de PHP

En el panel de control de Arsys:
1. Ir a "Configuración PHP"
2. Asegurarse de que PHP está activado
3. Versión recomendada: PHP 7.4 o superior

#### 5.2 Configurar Email

Editar el archivo `php/contact.php`:

```php
// Línea 5: Cambiar el email de destino
$to_email = "info@extremenadecamiones.es"; // ← Usar su email real
```

#### 5.3 Configurar SMTP (si es necesario)

Si el envío de emails no funciona con la función `mail()` de PHP:

1. En el panel de Arsys, configurar una cuenta de email SMTP
2. Modificar `contact.php` para usar SMTP (puede requerir librerías como PHPMailer)

### 6. Añadir Imágenes

Crear la estructura de carpetas en `images/`:

```
images/
├── hero1.jpg
├── hero2.jpg
├── hero3.jpg
├── empresa-header.jpg
├── empresa-instalaciones.jpg
├── postventa-header.jpg
├── taller.jpg
├── ocasion-header.jpg
├── ocasion-garantia.jpg
├── contacto-header.jpg
├── renault-header.jpg
├── renault-truck.jpg
├── manitou-header.jpg
├── manitou-machine.jpg
├── landini-header.jpg
├── landini-tractor.jpg
├── renault-logo.png
├── manitou-logo.png
├── landini-mccormick-logo.png
└── vehicles/
    ├── renault-t-520.jpg
    └── renault-t-480.jpg
```

**Recomendaciones para imágenes:**
- Formato: JPG para fotos, PNG para logos con transparencia
- Tamaño máximo: 1920px de ancho para headers
- Optimizar peso: Usar herramientas como TinyPNG o Squoosh
- Peso recomendado: < 500KB por imagen

### 7. Configurar SSL (HTTPS) - Recomendado

1. En el panel de Arsys, ir a "Certificados SSL"
2. Activar el certificado SSL gratuito (Let's Encrypt)
3. Esperar a que se active (puede tardar unos minutos)
4. Editar `.htaccess` y descomentar la sección de redirección HTTPS:

```apache
# Descomentar estas líneas:
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</IfModule>
```

### 8. Configurar WWW vs Sin WWW

Decidir si el sitio será:
- `www.extremenadecamiones.es` (con www)
- `extremenadecamiones.es` (sin www)

Editar `.htaccess` y descomentar la opción elegida (ver comentarios en el archivo).

### 9. Verificar el Sitio

1. Abrir navegador y visitar: `http://www.extremenadecamiones.es`
2. Verificar que todas las páginas cargan correctamente:
   - ✓ Inicio
   - ✓ Empresa
   - ✓ Postventa
   - ✓ Ocasión
   - ✓ Marcas (Renault, Manitou, Landini)
   - ✓ Contacto

3. Probar funcionalidades:
   - ✓ Slider en página de inicio
   - ✓ Menú responsive (móvil)
   - ✓ Formulario de contacto
   - ✓ Listado de vehículos de ocasión

### 10. Configurar Panel de Administración

El panel de administración está en: `http://www.extremenadecamiones.es/admin/`

**⚠️ IMPORTANTE - Seguridad:**

Para proteger el panel de administración, crear un archivo `.htpasswd`:

1. En el panel de Arsys, buscar "Protección de directorios"
2. Proteger la carpeta `/admin`
3. Crear usuario y contraseña

O manualmente, añadir al archivo `admin/.htaccess`:

```apache
AuthType Basic
AuthName "Área de Administración"
AuthUserFile /ruta/completa/.htpasswd
Require valid-user
```

### 11. Probar Formulario de Contacto

1. Ir a la página de contacto
2. Rellenar el formulario
3. Enviar
4. Verificar que el email llegue correctamente

**Si no funciona el envío de emails:**
- Verificar configuración SMTP en panel de Arsys
- Comprobar que el dominio tiene registros SPF configurados
- Revisar logs de error en el panel de control

### 12. Optimizaciones Finales

#### 12.1 Configurar Google Analytics (opcional)

Añadir código de seguimiento antes del cierre de `</head>` en todos los HTML:

```html
<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=GA_MEASUREMENT_ID"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'GA_MEASUREMENT_ID');
</script>
```

#### 12.2 Crear Sitemap

Crear archivo `sitemap.xml`:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>https://www.extremenadecamiones.es/</loc>
    <changefreq>daily</changefreq>
    <priority>1.0</priority>
  </url>
  <url>
    <loc>https://www.extremenadecamiones.es/presentacion.html</loc>
    <changefreq>monthly</changefreq>
    <priority>0.8</priority>
  </url>
  <!-- Añadir más URLs -->
</urlset>
```

#### 12.3 Configurar Google Search Console

1. Ir a https://search.google.com/search-console
2. Añadir propiedad (dominio)
3. Verificar mediante método DNS o archivo HTML
4. Enviar sitemap.xml

### 13. Backup y Mantenimiento

**Configurar backups automáticos:**
1. En panel de Arsys, activar backups automáticos
2. Frecuencia recomendada: Diaria

**Backup manual:**
- Descargar carpeta `data/` periódicamente
- Descargar carpeta `images/` cuando se añadan nuevas imágenes

## ✅ Checklist Final

- [ ] Todos los archivos subidos correctamente
- [ ] Permisos configurados en carpeta `data/`
- [ ] Email configurado en `contact.php`
- [ ] PHP habilitado y funcionando
- [ ] Todas las imágenes subidas
- [ ] SSL activado (HTTPS)
- [ ] Formulario de contacto probado y funcional
- [ ] Vehículos de ocasión mostrándose correctamente
- [ ] Panel de administración protegido con contraseña
- [ ] Sitio responsive verificado (móvil, tablet, desktop)
- [ ] Todos los enlaces funcionando
- [ ] Favicon añadido (opcional)
- [ ] Google Analytics configurado (opcional)
- [ ] Sitemap creado y enviado (opcional)

## 🆘 Solución de Problemas

### Error 500 - Error Interno del Servidor
- Verificar permisos de archivos
- Revisar logs de error en panel de Arsys
- Comprobar sintaxis de `.htaccess`

### Formulario de contacto no envía emails
- Verificar configuración SMTP
- Comprobar email de destino en `contact.php`
- Revisar logs de PHP

### Vehículos no se muestran
- Verificar que `vehicles.json` tiene el formato correcto
- Comprobar permisos de lectura
- Revisar consola del navegador (F12) para errores JavaScript

### Imágenes no cargan
- Verificar rutas de las imágenes
- Comprobar que los archivos están en la carpeta correcta
- Revisar nombres de archivo (mayúsculas/minúsculas)

## 📞 Soporte Técnico

**Arsys:**
- Teléfono: 902 026 000
- Web: https://www.arsys.es/soporte

**Soporte del sitio:**
- Email: info@extremenadecamiones.es
- Teléfono: 924 37 17 18

---

**Última actualización:** Julio 2026
