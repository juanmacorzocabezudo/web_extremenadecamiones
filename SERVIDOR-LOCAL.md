# 🌐 Servidor Local - Guía Rápida

## ▶️ Iniciar el servidor

Abre la Terminal y ejecuta:

```bash
cd '/Users/juanmariacorzo/Documents/Extremeña de camiones/Web'
php -S localhost:8000
```

Luego abre tu navegador en: **http://localhost:8000**

## ⏹️ Detener el servidor

Presiona `Ctrl + C` en la Terminal donde está corriendo el servidor.

## 🔗 URLs de prueba

- **Inicio:** http://localhost:8000/index.html
- **Empresa:** http://localhost:8000/presentacion.html
- **Postventa:** http://localhost:8000/postventa.html
- **Ocasión:** http://localhost:8000/ocasion.html
- **Contacto:** http://localhost:8000/contacto.html
- **Renault:** http://localhost:8000/renault.html
- **Manitou:** http://localhost:8000/manitou.html
- **Landini:** http://localhost:8000/landini-mccormick.html
- **Admin:** http://localhost:8000/admin/

## ⚠️ Notas importantes

1. **El servidor solo funciona mientras la Terminal esté abierta**
2. Si el puerto 8000 está ocupado, usa otro puerto: `php -S localhost:8080`
3. Para probar el formulario de contacto localmente, necesitarás configurar el envío de emails
4. Los cambios en HTML/CSS/JS se verán automáticamente (solo recarga la página)
5. Si cambias archivos PHP, reinicia el servidor (Ctrl+C y volver a iniciar)

## 🎨 Probar en diferentes dispositivos

Para ver cómo se ve en móvil/tablet:

1. Abre las DevTools del navegador (F12 o Cmd+Opt+I)
2. Click en el ícono de dispositivo móvil (toggle device toolbar)
3. Selecciona diferentes resoluciones (iPhone, iPad, etc.)

## 📱 Acceder desde tu móvil (misma red WiFi)

1. Obtén tu IP local:
   ```bash
   ipconfig getifaddr en0
   ```

2. Inicia el servidor en todas las interfaces:
   ```bash
   php -S 0.0.0.0:8000
   ```

3. En tu móvil, abre: `http://TU_IP:8000`
   Por ejemplo: `http://192.168.1.100:8000`

## 🐛 Solución de problemas

**Error: "Address already in use"**
- El puerto 8000 está ocupado
- Solución: Usa otro puerto `php -S localhost:8001`

**No se ven las imágenes**
- Las imágenes aún no están añadidas
- Añade las imágenes en la carpeta `images/`

**El formulario de contacto no funciona**
- Normal en desarrollo local
- El envío de emails requiere configuración SMTP
- Puedes verificar que el formulario envía datos abriendo DevTools > Network

**Los vehículos no aparecen**
- Verifica que `data/vehicles.json` existe
- Comprueba la consola del navegador (F12) para errores

## 🔧 Alternativas

Si prefieres una interfaz gráfica, puedes usar:
- **MAMP** (https://www.mamp.info/)
- **XAMPP** (https://www.apachefriends.org/)
- **Local by Flywheel** (https://localwp.com/)

---

**Atajos útiles:**

- `Cmd + R` - Recargar página
- `Cmd + Shift + R` - Recargar sin caché
- `Cmd + Option + I` - Abrir DevTools
- `Ctrl + C` - Detener servidor
