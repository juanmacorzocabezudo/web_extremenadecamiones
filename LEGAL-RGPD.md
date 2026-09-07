# Sistema Legal RGPD/LSSI - Extremeña de Camiones

## ✅ Cumplimiento Legal Completo

Este sitio web cumple con toda la normativa legal española vigente:
- **RGPD** (Reglamento General de Protección de Datos)
- **LSSI** (Ley de Servicios de la Sociedad de la Información)
- **LOPD** (Ley Orgánica de Protección de Datos)

---

## 📋 Componentes Implementados

### 1. Banner de Cookies (OBLIGATORIO)
**Archivo:** `js/cookies.js`

El banner aparece automáticamente en la primera visita del usuario y permite:
- ✅ Aceptar cookies
- ✅ Rechazar cookies
- ✅ Enlace a política de cookies
- ✅ Almacenamiento del consentimiento en localStorage

**Características:**
- Diseño responsive (móvil y escritorio)
- Animación suave de entrada desde abajo
- Preferencias guardadas durante 365 días
- No se carga Google Analytics si el usuario rechaza

### 2. Páginas Legales

#### Aviso Legal
**Archivo:** `aviso-legal.html`

Contiene:
- Datos identificativos de la empresa
- Condiciones de uso del sitio web
- Propiedad intelectual e industrial
- Exclusión de garantías
- Legislación aplicable

#### Política de Privacidad
**Archivo:** `politica-privacidad.html`

Contiene:
- Responsable del tratamiento
- Finalidades del tratamiento de datos
- Base legal (RGPD)
- Derechos del usuario (ARCO)
- Conservación de datos
- Información sobre la AEPD

#### Política de Cookies
**Archivo:** `politica-cookies.html`

Contiene:
- Qué son las cookies
- Tipos de cookies utilizadas
- Tabla detallada de cookies
- Cómo gestionarlas en cada navegador
- Información sobre cookies de terceros

---

## 🎨 Estilos

**Archivo:** `css/pages.css` (líneas finales)

Se han añadido estilos específicos para:
- `.legal-content` - Contenedor de páginas legales
- `.legal-document` - Documento legal formateado
- `.cookie-table` - Tablas de información de cookies
- `.cookies-list` - Lista detallada de cookies
- Responsive completo para móviles

**Archivo:** `css/styles.css` (líneas finales)

Se han añadido estilos para:
- `.cookie-banner` - Banner de cookies
- `.cookie-btn` - Botones del banner
- Animaciones de entrada/salida
- Responsive completo

---

## 🔗 Enlaces Actualizados

Todos los enlaces del footer han sido actualizados en:
- ✅ index.html
- ✅ contacto.html
- ✅ landini.html
- ✅ mccormick.html
- ✅ manitou.html
- ✅ ocasion.html
- ✅ postventa.html
- ✅ presentacion.html
- ✅ renault.html
- ✅ landini-mccormick.html
- ✅ politica-calidad.html

Los enlaces ahora apuntan a:
- `aviso-legal.html`
- `politica-privacidad.html`
- `politica-cookies.html`

---

## 🚀 Implementación Técnica

### Script de Cookies

```javascript
// El script se carga automáticamente en todas las páginas
<script src="js/cookies.js"></script>
```

**Funcionalidades:**
- Comprueba si existe consentimiento previo
- Muestra el banner si es necesario
- Guarda la preferencia del usuario
- Carga Google Analytics solo si se acepta

### Gestión de Consentimiento

```javascript
// Almacenado en localStorage
{
  "accepted": true/false,
  "timestamp": "2026-09-01T00:00:00.000Z",
  "version": "1.0"
}
```

**Nombre de la cookie:** `extremena_cookie_consent`
**Duración:** 365 días

---

## 📊 Cookies Utilizadas

### Cookies Técnicas (No requieren consentimiento)
| Cookie | Finalidad | Duración |
|--------|-----------|----------|
| `extremena_cookie_consent` | Almacena preferencia de cookies | 365 días |
| `adminAuth` | Control de acceso admin | Sesión |

### Cookies Analíticas (Requieren consentimiento)
| Cookie | Proveedor | Finalidad | Duración |
|--------|-----------|-----------|----------|
| `_ga` | Google Analytics | Distinguir usuarios | 2 años |
| `_gid` | Google Analytics | Distinguir usuarios | 24 horas |
| `_gat` | Google Analytics | Limitar solicitudes | 1 minuto |

---

## 📝 Datos de la Empresa

**Razón Social:** EXTREMEÑA DE CAMIONS S.A.  
**Dirección:** Pol. Ind. El Prado, C/ Zaragoza, 21. 06800 Mérida, Badajoz.
**Teléfono:** 924 37 17 18  
**Email:** info@extremenadecamiones.es

---

## ⚙️ Configuración de Google Analytics

Para activar Google Analytics (OPCIONAL):

1. Abrir `js/cookies.js`
2. Localizar el método `loadAnalytics()`
3. Descomentar el código de Google Analytics
4. Reemplazar `UA-XXXXXXXX-X` con tu ID de tracking

```javascript
loadAnalytics() {
    // Descomentar y añadir tu ID
    ga('create', 'TU-ID-AQUI', 'auto');
    ga('send', 'pageview');
}
```

---

## 🔒 Derechos de los Usuarios (RGPD)

Los usuarios pueden ejercer los siguientes derechos:
- **Acceso** - Conocer qué datos se están tratando
- **Rectificación** - Corregir datos incorrectos
- **Supresión** - Eliminar sus datos personales
- **Oposición** - Oponerse al tratamiento
- **Limitación** - Limitar el uso de datos
- **Portabilidad** - Recibir datos en formato estructurado

**Contacto para ejercer derechos:**
- Email: info@extremenadecamiones.es
- Dirección: Pol. Ind. El Prado, C/ Zaragoza, 21. 06800 Mérida, Badajoz.

---

## 🛠️ Mantenimiento

### Actualizar Políticas

1. Editar los archivos HTML correspondientes
2. Actualizar la fecha al final del documento:
```html
<p><strong>Última actualización:</strong> DD de Mes de AAAA</p>
```

### Resetear Consentimiento (Testing)

Desde la consola del navegador:
```javascript
window.cookieConsent.resetConsent();
```

---

## ✅ Checklist de Cumplimiento

- [x] Banner de cookies implementado
- [x] Aviso Legal completo
- [x] Política de Privacidad completa
- [x] Política de Cookies completa
- [x] Enlaces actualizados en todas las páginas
- [x] Sistema de consentimiento funcional
- [x] Diseño responsive completo
- [x] Información de contacto correcta
- [x] Derechos ARCO especificados
- [x] AEPD mencionada

---

## 📅 Última Actualización

**Fecha:** 1 de septiembre de 2026  
**Versión:** 1.0

---

## 📞 Soporte

Para cualquier duda sobre el sistema legal implementado:
- Email: info@extremenadecamiones.es
- Teléfono: 924 37 17 18
