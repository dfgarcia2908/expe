# 7. Frontend

## Estructura de Recursos

```
resources/
├── js/
│   ├── components/          # Componentes Vue.js
│   ├── browserStorage/      # LocalStorage helpers
│   ├── charts/              # Configuración de gráficos
│   ├── datatable/           # DataTables config
│   ├── datepicker/          # Date picker config
│   ├── fullcalendar/        # FullCalendar config
│   ├── utils/               # Utilidades
│   ├── app.js               # Bootstrap Laravel
│   ├── index.js             # Punto de entrada principal
│   ├── sisgec.js            # Lógica específica SISGEC
│   └── autosave.js          # Autoguardado
├── sass/
│   ├── spec/                # Estilos específicos
│   ├── vendor/              # Estilos de terceros
│   ├── _variables.scss      # Variables SASS
│   ├── app.scss             # Estilos Laravel
│   ├── index.scss           # Estilos principales
│   └── pdf.scss             # Estilos para PDFs
├── views/
│   ├── layouts/             # Layouts principales
│   ├── doctor/              # Vistas del médico
│   ├── assistant/           # Vistas del asistente
│   ├── auth/                # Vistas de autenticación
│   ├── pdf/                 # Plantillas PDF
│   ├── parts/               # Componentes reutilizables
│   ├── block/               # Bloques de UI
│   └── atom/                # Componentes atómicos
├── images/                  # Imágenes fuente
└── fonts/                   # Fuentes personalizadas
```

## Librerías JavaScript

### Core
- **Vue.js 2.6.6**: Framework reactivo
- **jQuery 3.x**: Manipulación DOM
- **Bootstrap 4.3.1**: Framework CSS
- **Axios**: Cliente HTTP

### UI Components
- **FullCalendar 3.10.0**: Calendario de citas
- **DataTables 1.10.19**: Tablas interactivas
- **Chart.js 2.7.3**: Gráficos
- **SweetAlert 2.1.2**: Alertas modales
- **Tippy.js 3.4.1**: Tooltips
- **Quill 1.3.6**: Editor WYSIWYG
- **Bootstrap Datepicker**: Selector de fechas
- **Perfect Scrollbar 1.4.0**: Scrollbars personalizados
- **Dropzone 5.5.1**: Subida de archivos
- **IMask 4.1.5**: Máscaras de entrada
- **Devbridge Autocomplete**: Autocompletado

### Visualización
- **Skycons**: Iconos animados
- **jQuery Sparkline**: Mini gráficos
- **Easy Pie Chart**: Gráficos circulares
- **jVectorMap**: Mapas vectoriales
- **Masonry Layout**: Layouts tipo Pinterest

### Utilidades
- **Moment.js 2.24.0**: Manejo de fechas
- **Notify.js**: Notificaciones
- **Offline.js**: Detección de conexión
- **Password Strength Meter**: Medidor de contraseñas
- **Load Google Maps API**: Carga de Google Maps

## Compilación de Assets

### Laravel Mix

Configuración en `webpack.mix.js`:

```javascript
// Aplicación Laravel básica
mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');

// Aplicación SISGEC
mix.js('resources/js/index.js', 'public/js/sisgec.app.js')
   .sass('resources/sass/index.scss', 'public/css/sisgec.app.css')
   .sass('resources/sass/pdf.scss', 'public/css/pdf.css')
   .copy('resources/images', 'public/images')
   .copy('node_modules/fullcalendar/dist/locale-all.js', 'public/js/full-calendar/locale.js');
```

### Comandos de Compilación

```bash
# Desarrollo (sin minificar)
npm run dev

# Desarrollo con watch
npm run watch

# Producción (minificado y optimizado)
npm run production
```

### Assets Generados

```
public/
├── css/
│   ├── app.css                 # Estilos Laravel
│   ├── sisgec.app.css          # Estilos SISGEC
│   └── pdf.css                 # Estilos PDF
├── js/
│   ├── app.js                  # JavaScript Laravel
│   ├── sisgec.app.js           # JavaScript SISGEC
│   └── full-calendar/
│       └── locale.js           # Locales FullCalendar
└── images/                     # Imágenes optimizadas
```

## Layouts

### Layout Principal: sisgec.blade.php

```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - SISGEC</title>
    <link rel="stylesheet" href="{{ asset('css/sisgec.app.css') }}">
    @yield('styles')
</head>
<body>
    @include('parts.header')
    @include('parts.sidebar')
    
    <main class="main-content">
        @yield('content')
    </main>
    
    <script src="{{ asset('js/sisgec.app.js') }}"></script>
    @yield('scripts')
</body>
</html>
```

### Layout de Autenticación: app.blade.php

Layout simplificado para login y registro

## Componentes Principales

### Header (parts/header.blade.php)
- Logo del consultorio
- Menú de usuario
- Notificaciones
- Búsqueda rápida

### Sidebar (parts/sidebar.blade.php)
- Navegación principal
- Menú colapsable
- Iconos de secciones
- Indicadores de estado

### Sidebar Menu Item (atom/sidebar-menu-item.blade.php)
Componente reutilizable para items del menú

## Vistas por Módulo

### Dashboard
- `doctor/desktop.blade.php`: Panel principal con estadísticas

### Pacientes
- `doctor/patients/index.blade.php`: Lista de pacientes
- `doctor/patients/new.blade.php`: Crear paciente
- `doctor/patients/self.blade.php`: Ver expediente
- `doctor/patients/edit.blade.php`: Editar paciente

### Citas
- `doctor/appointments/index.blade.php`: Calendario y lista

### Notas de Evolución
- `doctor/tracing/new.blade.php`: Nueva nota
- `doctor/tracing/edit.blade.php`: Editar nota
- `doctor/tracing/self.blade.php`: Ver nota

### Prescripciones
- `doctor/prescriptions/new.blade.php`: Nueva receta
- `doctor/prescriptions/edit.blade.php`: Editar receta

### Configuración
- `doctor/settings/index.blade.php`: Configuración general

### PDFs
- `pdf/initial.blade.php`: Expediente inicial
- `pdf/tracing.blade.php`: Nota de evolución
- `pdf/prescription.blade.php`: Receta médica
- `pdf/identification_card.blade.php`: Tarjeta de identificación
- `pdf/informed_consent.blade.php`: Consentimiento informado

## JavaScript Modular

### index.js (Punto de entrada)

```javascript
// Importar dependencias
import './bootstrap';
import './sisgec';
import './autosave';

// Importar módulos
import './datatable/datatable';
import './fullcalendar/fullcalendar';
import './charts/charts';
// ... más módulos
```

### sisgec.js (Lógica principal)

Funcionalidades:
- Inicialización de componentes
- Manejo de eventos globales
- Configuración de AJAX
- Helpers globales

### autosave.js

Sistema de autoguardado:
- Detecta cambios en formularios
- Guarda automáticamente cada X segundos
- Muestra indicador visual
- Recupera datos en caso de cierre accidental

## Estilos SASS

### Variables (_variables.scss)

```scss
// Colores principales
$primary-color: #007bff;
$secondary-color: #6c757d;
$success-color: #28a745;
$danger-color: #dc3545;

// Tipografía
$font-family-base: 'Raleway', sans-serif;
$font-size-base: 14px;

// Espaciado
$spacer: 1rem;
```

### index.scss (Estilos principales)

Importa:
- Variables
- Bootstrap
- Componentes personalizados
- Estilos de terceros
- Utilidades

### pdf.scss (Estilos para PDF)

Estilos específicos para documentos PDF:
- Tamaños de página
- Márgenes
- Tipografía para impresión
- Saltos de página

## Componentes Interactivos

### DataTables

Configuración en `resources/js/datatable/datatable.js`:

```javascript
$('.datatable').DataTable({
    language: {
        url: '/locale/es.json'
    },
    pageLength: 25,
    responsive: true,
    order: [[0, 'desc']]
});
```

### FullCalendar

Configuración en `resources/js/fullcalendar/fullcalendar.js`:

```javascript
$('#calendar').fullCalendar({
    locale: 'es',
    events: '/medical-appointments.json',
    editable: true,
    selectable: true
});
```

### Chart.js

Configuración en `resources/js/charts/charts.js`:

```javascript
new Chart(ctx, {
    type: 'line',
    data: chartData,
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
```

## Formularios

### Validación Cliente

```javascript
$('form').validate({
    rules: {
        'patient[name]': 'required',
        'patient[lastname]': 'required',
        'patient[sex]': 'required'
    },
    messages: {
        'patient[name]': 'El nombre es requerido',
        'patient[lastname]': 'Los apellidos son requeridos'
    }
});
```

### Máscaras de Entrada

```javascript
IMask(document.getElementById('phone'), {
    mask: '(000) 000-0000'
});

IMask(document.getElementById('rfc'), {
    mask: 'aaaa000000***',
    definitions: {
        'a': /[A-Z]/,
        '*': /[A-Z0-9]/
    }
});
```

### Autocompletado

```javascript
$('#patient-search').autocomplete({
    serviceUrl: '/api/patients/search',
    onSelect: function(suggestion) {
        window.location.href = suggestion.data;
    }
});
```

## Notificaciones

### SweetAlert

```javascript
swal({
    title: "¿Estás seguro?",
    text: "Esta acción no se puede deshacer",
    icon: "warning",
    buttons: true,
    dangerMode: true
}).then((willDelete) => {
    if (willDelete) {
        // Ejecutar acción
    }
});
```

### Notify.js

```javascript
$.notify("Paciente guardado correctamente", {
    className: "success",
    position: "top right"
});
```

## Responsive Design

### Breakpoints

```scss
// Extra small devices (portrait phones, less than 576px)
@media (max-width: 575.98px) { }

// Small devices (landscape phones, 576px and up)
@media (min-width: 576px) and (max-width: 767.98px) { }

// Medium devices (tablets, 768px and up)
@media (min-width: 768px) and (max-width: 991.98px) { }

// Large devices (desktops, 992px and up)
@media (min-width: 992px) and (max-width: 1199.98px) { }

// Extra large devices (large desktops, 1200px and up)
@media (min-width: 1200px) { }
```

## Optimización

### Imágenes

Plugin de optimización en webpack.mix.js:

```javascript
new ImageminPlugin({
    pngquant: {
        quality: '95-100'
    },
    test: /\.(jpe?g|png|gif|svg)$/i
});
```

### Lazy Loading

```javascript
$('img[data-src]').lazyload({
    effect: 'fadeIn',
    threshold: 200
});
```

### Minificación

En producción, Laravel Mix automáticamente:
- Minifica JavaScript
- Minifica CSS
- Optimiza imágenes
- Genera source maps
