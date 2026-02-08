# 8. Seguridad

## Autenticación

### Sistema de Autenticación de Laravel

SISGEC utiliza el sistema de autenticación integrado de Laravel que incluye:

- Hash seguro de contraseñas (bcrypt)
- Protección contra ataques de fuerza bruta
- Tokens de sesión seguros
- Recuperación de contraseña segura

### Configuración de Sesiones

En `config/session.php`:

```php
'driver' => env('SESSION_DRIVER', 'file'),
'lifetime' => 120, // minutos
'expire_on_close' => false,
'encrypt' => false,
'secure' => env('SESSION_SECURE_COOKIE', false),
'http_only' => true,
'same_site' => 'lax',
```

### Hash de Contraseñas

```php
// Al crear usuario
$user->password = Hash::make($request->password);

// Al verificar
if (Hash::check($request->password, $user->password)) {
    // Contraseña correcta
}
```

## Autorización

### Middleware de Autenticación

Todas las rutas protegidas usan el middleware `auth`:

```php
Route::middleware(['auth'])->group(function () {
    // Rutas protegidas
});
```

### Roles de Usuario

SISGEC implementa dos roles principales:

1. **Doctor**: Acceso completo al sistema
2. **Asistente**: Acceso limitado a funciones administrativas

Verificación de rol:

```php
$role = auth()->user()->get_role();

if ($role === 'doctor') {
    // Acceso completo
} else if ($role === 'assistant') {
    // Acceso limitado
}
```

### Gates y Policies

Para implementar autorización más granular (recomendado para futuras versiones):

```php
// En AuthServiceProvider
Gate::define('update-patient', function ($user, $patient) {
    return $user->id === $patient->doctor_id;
});

// En controlador
if (Gate::allows('update-patient', $patient)) {
    // Usuario autorizado
}
```

## Protección CSRF

### Token CSRF en Formularios

Laravel protege automáticamente contra ataques CSRF:

```blade
<form method="POST" action="{{ route('patients.save') }}">
    @csrf
    <!-- Campos del formulario -->
</form>
```

### CSRF en AJAX

En `resources/js/bootstrap.js`:

```javascript
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
```

### Verificación Manual

```php
if ($request->session()->token() !== $request->input('_token')) {
    abort(403, 'CSRF token mismatch');
}
```

## Protección XSS

### Escape de Salida en Blade

```blade
<!-- Escapado automático -->
{{ $patient->name }}

<!-- Sin escapar (usar con precaución) -->
{!! $html_content !!}
```

### Sanitización de Entrada

```php
// Validación y sanitización
$validated = $request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email|max:255',
]);

// Limpieza manual
$clean_input = strip_tags($request->input('field'));
$clean_input = htmlspecialchars($clean_input, ENT_QUOTES, 'UTF-8');
```

## Protección SQL Injection

### Eloquent ORM

Eloquent protege automáticamente contra SQL injection:

```php
// Seguro - usa prepared statements
$patient = Patient::where('name', $name)->first();

// Seguro - binding automático
$patients = Patient::whereIn('id', $ids)->get();
```

### Query Builder

```php
// Seguro
DB::table('patients')
    ->where('name', '=', $name)
    ->get();

// INSEGURO - nunca hacer esto
DB::select("SELECT * FROM patients WHERE name = '$name'");
```

### Validación de IDs

```php
// Validar que el ID es numérico
$id = (int) $request->input('id');

// O usar validación de Laravel
$request->validate([
    'id' => 'required|integer|exists:patients,id'
]);
```

## Validación de Entrada

### Reglas de Validación

```php
$request->validate([
    'patient.name' => 'required|string|max:255',
    'patient.lastname' => 'required|string|max:255',
    'patient.email' => 'nullable|email|max:255',
    'patient.phone' => 'nullable|string|max:20',
    'patient.birthdate' => 'nullable|date|before:today',
    'patient.sex' => 'required|in:M,F',
]);
```

### Validación Personalizada

```php
$request->validate([
    'rfc' => ['required', function ($attribute, $value, $fail) {
        if (!preg_match('/^[A-Z]{4}\d{6}[A-Z0-9]{3}$/', $value)) {
            $fail('El RFC no tiene un formato válido.');
        }
    }],
]);
```

### Mensajes de Error Personalizados

```php
$messages = [
    'patient.name.required' => 'El nombre del paciente es obligatorio',
    'patient.email.email' => 'El email no tiene un formato válido',
];

$request->validate($rules, $messages);
```

## Protección de Archivos

### Subida Segura de Archivos

```php
// Validar tipo y tamaño
$request->validate([
    'file' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:10240', // 10MB
]);

// Generar nombre seguro
$filename = sha1(date('YmdHis') . str_random(30));
$extension = $request->file('file')->getClientOriginalExtension();
$safe_filename = $filename . '.' . $extension;

// Guardar en ubicación segura
$request->file('file')->move($path, $safe_filename);
```

### Prevención de Path Traversal

```php
// INSEGURO
$file = $request->input('filename');
$path = storage_path('app/' . $file);

// SEGURO
$file = basename($request->input('filename'));
$path = storage_path('app/' . $file);

// Verificar que el archivo existe y está en la ubicación correcta
if (!file_exists($path) || !str_starts_with(realpath($path), storage_path('app'))) {
    abort(404);
}
```

### Control de Acceso a Archivos

```php
// Verificar permisos antes de servir archivo
public function download($filename) {
    $file = basename($filename);
    $path = public_path('studies/' . $file);
    
    if (!file_exists($path)) {
        abort(404);
    }
    
    // Verificar que el usuario tiene acceso
    $study = Study::where('filename', $file)->first();
    if (!$study || !$this->userCanAccessStudy($study)) {
        abort(403);
    }
    
    return response()->download($path);
}
```

## Protección de Datos Sensibles

### Variables de Entorno

Nunca incluir credenciales en el código:

```php
// INCORRECTO
$password = 'mi_contraseña_secreta';

// CORRECTO
$password = env('DB_PASSWORD');
```

### Archivo .env

```env
# Nunca commitear este archivo
APP_KEY=base64:...
DB_PASSWORD=contraseña_segura
MAIL_PASSWORD=contraseña_email
```

### .gitignore

```
.env
.env.backup
.env.production
```

## Headers de Seguridad

### Configuración en Nginx

```nginx
add_header X-Frame-Options "SAMEORIGIN";
add_header X-XSS-Protection "1; mode=block";
add_header X-Content-Type-Options "nosniff";
add_header Referrer-Policy "strict-origin-when-cross-origin";
add_header Content-Security-Policy "default-src 'self'";
```

### Middleware Personalizado

```php
// app/Http/Middleware/SecurityHeaders.php
public function handle($request, Closure $next)
{
    $response = $next($request);
    
    $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
    $response->headers->set('X-XSS-Protection', '1; mode=block');
    $response->headers->set('X-Content-Type-Options', 'nosniff');
    
    return $response;
}
```

## Logging y Auditoría

### Registro de Eventos Importantes

```php
// Registro de acceso a expedientes
Log::info('Expediente accedido', [
    'user_id' => auth()->id(),
    'patient_id' => $patient->id,
    'ip' => $request->ip(),
    'timestamp' => now()
]);

// Registro de modificaciones
Log::warning('Expediente modificado', [
    'user_id' => auth()->id(),
    'patient_id' => $patient->id,
    'changes' => $patient->getDirty()
]);
```

### Monitoreo de Logs

```bash
# Ver logs en tiempo real
tail -f storage/logs/laravel.log

# Buscar eventos específicos
grep "Expediente accedido" storage/logs/laravel.log
```

## Modo Demo

### Protección en Modo Demo

```php
// En .env
APP_DEMO_MODE=true

// En controlador
if (config('app.demo_mode')) {
    return redirect()->back()->with('notify', [[
        'type' => 'warning',
        'message' => 'Esta acción no está permitida en modo demo'
    ]]);
}
```

## Backup y Recuperación

### Backup de Base de Datos

```bash
# SQLite
cp database/database.sqlite database/backup-$(date +%Y%m%d).sqlite

# MySQL
mysqldump -u usuario -p sisgec > backup-$(date +%Y%m%d).sql
```

### Backup Automatizado

```bash
# Cron job diario
0 2 * * * cd /var/www/sisgec && php artisan backup:run
```

## Actualizaciones de Seguridad

### Mantener Dependencias Actualizadas

```bash
# Actualizar dependencias PHP
composer update

# Verificar vulnerabilidades
composer audit

# Actualizar dependencias JavaScript
npm update
npm audit
npm audit fix
```

### Verificar Versión de Laravel

```bash
php artisan --version

# Actualizar Laravel
composer update laravel/framework
```

## Checklist de Seguridad

### Antes de Producción

- [ ] Cambiar APP_KEY
- [ ] Configurar APP_ENV=production
- [ ] Configurar APP_DEBUG=false
- [ ] Usar HTTPS (SSL/TLS)
- [ ] Configurar contraseñas seguras de BD
- [ ] Cambiar credenciales por defecto
- [ ] Configurar permisos de archivos correctamente
- [ ] Habilitar logs de auditoría
- [ ] Configurar backups automáticos
- [ ] Implementar rate limiting
- [ ] Configurar firewall
- [ ] Actualizar todas las dependencias
- [ ] Revisar configuración de sesiones
- [ ] Configurar headers de seguridad
- [ ] Implementar monitoreo

### Mantenimiento Regular

- [ ] Revisar logs semanalmente
- [ ] Actualizar dependencias mensualmente
- [ ] Verificar backups mensualmente
- [ ] Auditoría de seguridad trimestral
- [ ] Cambiar contraseñas trimestralmente
- [ ] Revisar permisos de usuarios

## Cumplimiento Normativo

### HIPAA (Aplicable en contexto médico)

- Encriptación de datos en tránsito (HTTPS)
- Encriptación de datos en reposo (recomendado)
- Control de acceso basado en roles
- Auditoría de acceso a expedientes
- Backup y recuperación de datos

### NOM-024-SSA3-2012 (México)

Cumplimiento con normatividad mexicana:
- Expediente clínico completo
- Firma electrónica (pendiente implementar)
- Resguardo de información
- Confidencialidad de datos

## Recomendaciones Adicionales

1. **Implementar 2FA**: Autenticación de dos factores
2. **Rate Limiting**: Limitar intentos de login
3. **Encriptación de BD**: Para datos muy sensibles
4. **WAF**: Web Application Firewall
5. **Monitoreo**: Herramientas como Sentry
6. **Pentesting**: Pruebas de penetración periódicas
7. **Capacitación**: Entrenar usuarios en seguridad
