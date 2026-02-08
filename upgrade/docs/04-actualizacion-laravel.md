# 4. Actualización de Laravel

## Fase 1: Laravel 5.7 → 6.x (LTS)

### 1. Actualizar composer.json

```json
{
    "require": {
        "php": "^7.4",
        "laravel/framework": "^6.0"
    }
}
```

### 2. Cambios Obligatorios

#### Helpers a Facades

```php
// Antes
str_slug($string)
array_get($array, 'key')
str_contains($haystack, $needle)

// Después
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

Str::slug($string)
Arr::get($array, 'key')
Str::contains($haystack, $needle)
```

#### Actualizar Imports

```php
// En todos los archivos que usen helpers
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
```

### 3. Ejecutar Actualización

```bash
composer update
php artisan view:clear
php artisan config:clear
php artisan test
```

## Fase 2: Laravel 6.x → 8.x (LTS)

### 1. Actualizar composer.json

```json
{
    "require": {
        "php": "^7.4",
        "laravel/framework": "^8.0"
    }
}
```

### 2. Mover Modelos

```bash
mkdir app/Models
mv app/*.php app/Models/
# Excepto: helpers.php, Console/, Exceptions/, Http/, Providers/
```

#### Actualizar Namespace

```php
// Antes
namespace App;

// Después
namespace App\Models;
```

#### Actualizar Referencias

```php
// En controladores, rutas, etc.
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\MedicalAppointment;
```

### 3. Actualizar Factories

```bash
php artisan make:factory PatientFactory --model=Patient
```

```php
// database/factories/PatientFactory.php
namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    protected $model = Patient::class;

    public function definition()
    {
        return [
            'name' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'sex' => $this->faker->randomElement(['M', 'F']),
        ];
    }
}
```

#### Actualizar Modelos

```php
// En cada modelo
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;
    
    // resto del código
}
```

### 4. Actualizar Seeders

```bash
mkdir database/seeders
mv database/seeds/* database/seeders/
```

```php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            SettingsTableSeeder::class,
        ]);
    }
}
```

### 5. Actualizar Rutas

```php
// routes/web.php
use App\Http\Controllers\PatientController;
use App\Http\Controllers\MedicalAppointmentController;
use App\Http\Controllers\HomeController;

// Antes
Route::get('/', 'HomeController@index');

// Después
Route::get('/', [HomeController::class, 'index']);

// Aplicar a todas las rutas
Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/patients', [PatientController::class, 'index'])->name('patients');
    // ... resto de rutas
});
```

### 6. Ejecutar Actualización

```bash
composer update
php artisan config:clear
php artisan route:clear
php artisan test
```

## Fase 3: Laravel 8.x → 9.x (LTS)

### 1. Actualizar composer.json

```json
{
    "require": {
        "php": "^8.1",
        "laravel/framework": "^9.0"
    }
}
```

### 2. Actualizar Flysystem

```php
// config/filesystems.php
'disks' => [
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
        'throw' => false, // Nuevo en Laravel 9
    ],
],
```

### 3. Ejecutar Actualización

```bash
composer update
php artisan storage:link
php artisan test
```

## Fase 4: Laravel 9.x → 10.x

### 1. Actualizar composer.json

```json
{
    "require": {
        "php": "^8.1",
        "laravel/framework": "^10.0"
    }
}
```

### 2. Agregar Tipos de Retorno

```php
// En controladores
public function index(): View
{
    $patients = Patient::orderBy('created_at', 'desc')->get();
    return view('doctor.patients.index', ['patients' => $patients]);
}

public function store(Request $request): RedirectResponse
{
    // código
    return redirect()->route('patient', ['id' => $patient->id]);
}
```

### 3. Ejecutar Actualización

```bash
composer update
php artisan test
```

## Fase 5: Laravel 10.x → 11.x (LTS)

### 1. Actualizar composer.json

```json
{
    "require": {
        "php": "^8.2",
        "laravel/framework": "^11.0"
    }
}
```

### 2. Simplificar Estructura (Opcional)

Laravel 11 simplifica la estructura, pero es opcional mantener la estructura anterior.

### 3. Actualizar Configuración

```bash
php artisan config:publish
```

### 4. Ejecutar Actualización

```bash
composer update
php artisan optimize:clear
php artisan test
```

## Script de Migración Automatizada

```bash
#!/bin/bash
# upgrade-laravel.sh

echo "=== Actualización Laravel 5.7 → 11.x ==="

# Laravel 6
echo "Paso 1: Laravel 6.x"
composer require "laravel/framework:^6.0" --no-update
composer update
php artisan view:clear
php artisan config:clear

# Laravel 8
echo "Paso 2: Laravel 8.x"
composer require "laravel/framework:^8.0" --no-update
composer update

# Mover modelos
mkdir -p app/Models
find app -maxdepth 1 -name "*.php" ! -name "helpers.php" -exec mv {} app/Models/ \;

# Laravel 9
echo "Paso 3: Laravel 9.x"
composer require "laravel/framework:^9.0" --no-update
composer update

# Laravel 10
echo "Paso 4: Laravel 10.x"
composer require "laravel/framework:^10.0" --no-update
composer update

# Laravel 11
echo "Paso 5: Laravel 11.x"
composer require "laravel/framework:^11.0" --no-update
composer update

echo "=== Actualización completada ==="
php artisan --version
```

## Verificación Post-Actualización

```bash
# Verificar versión
php artisan --version

# Limpiar caché
php artisan optimize:clear

# Ejecutar tests
php artisan test

# Verificar rutas
php artisan route:list

# Verificar migraciones
php artisan migrate:status
```

## Troubleshooting

### Error: Class not found

```bash
composer dump-autoload
php artisan config:clear
```

### Error: Namespace incorrecto

Buscar y reemplazar en todo el proyecto:
```bash
find app -type f -name "*.php" -exec sed -i 's/namespace App;/namespace App\\Models;/g' {} +
```

### Error: Factory no funciona

```bash
php artisan make:factory NombreFactory --model=Modelo
```

## Checklist de Actualización Laravel

- [ ] Laravel 6.x instalado
- [ ] Helpers actualizados a Facades
- [ ] Tests pasando en Laravel 6
- [ ] Laravel 8.x instalado
- [ ] Modelos movidos a app/Models
- [ ] Factories refactorizadas
- [ ] Seeders actualizados
- [ ] Rutas actualizadas
- [ ] Tests pasando en Laravel 8
- [ ] Laravel 9.x instalado
- [ ] Flysystem actualizado
- [ ] Tests pasando en Laravel 9
- [ ] Laravel 10.x instalado
- [ ] Tipos de retorno agregados
- [ ] Tests pasando en Laravel 10
- [ ] Laravel 11.x instalado
- [ ] Configuración actualizada
- [ ] Tests pasando en Laravel 11

## Siguiente Paso

Proceder con:
- [Actualización de Dependencias](05-dependencias.md)
