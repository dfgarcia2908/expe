# 2. Arquitectura del Sistema

## Stack Tecnológico

### Backend
- **Laravel 5.7**: Framework PHP MVC
- **PHP >= 7.1.3**: Lenguaje de programación
- **Eloquent ORM**: Mapeo objeto-relacional

### Frontend
- **Vue.js 2.6.6**: Framework JavaScript
- **jQuery 3.x**: Manipulación DOM
- **Bootstrap 4.3.1**: Framework CSS
- **Axios**: Cliente HTTP

### Base de Datos
- **SQLite** (por defecto)
- **MySQL/MariaDB** (alternativa)
- **PostgreSQL** (soporte)

### Librerías Principales
- **DomPDF**: Generación de PDFs
- **Intervention Image**: Procesamiento de imágenes
- **FullCalendar**: Calendario de citas
- **DataTables**: Tablas interactivas
- **Chart.js**: Gráficos y estadísticas

## Patrón MVC

```
Usuario → Rutas → Controladores → Modelos → Base de Datos
                       ↓
                    Vistas
```

## Estructura de Directorios

```
sisgec/
├── app/                    # Lógica de aplicación
│   ├── Http/Controllers/   # Controladores
│   └── [Modelos].php       # Modelos Eloquent
├── database/
│   ├── migrations/         # Migraciones
│   └── seeds/              # Seeders
├── public/                 # Archivos públicos
├── resources/
│   ├── js/                 # JavaScript
│   ├── sass/               # Estilos
│   └── views/              # Vistas Blade
├── routes/                 # Rutas
└── storage/                # Archivos generados
```

## Flujo de Datos

### Registro de Paciente
1. Usuario accede a /patients/new
2. PatientController@create muestra formulario
3. Usuario envía datos
4. PatientController@store crea registros
5. Redirección a /patient/{id}

## Seguridad

- Autenticación Laravel
- Protección CSRF
- Protección XSS
- Protección SQL Injection (Eloquent)
- Roles de usuario

## Compatibilidad

- Chrome/Firefox/Safari/Edge (últimas versiones)
- Linux/macOS/Windows
- Diseño responsivo
