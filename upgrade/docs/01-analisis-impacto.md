# 1. Análisis de Impacto

## Breaking Changes PHP 7.1 → 8.3

### PHP 7.2
- `each()` deprecado → usar `foreach`
- `create_function()` deprecado → usar closures
- `__autoload()` deprecado → usar `spl_autoload_register()`

### PHP 7.3
- Cambios menores, mayormente compatible

### PHP 7.4
- Array/string access con llaves `{}` deprecado → usar `[]`
- `array_key_exists()` con objetos deprecado

### PHP 8.0
- **Named arguments**
- **Union types**
- **Match expression**
- **Nullsafe operator** `?->`
- **Constructor property promotion**
- Cambios en manejo de errores (más excepciones)

### PHP 8.1
- **Enums**
- **Readonly properties**
- **Fibers**
- `null` a tipos no-nullable deprecado

### PHP 8.2
- **Readonly classes**
- Propiedades dinámicas deprecadas

### PHP 8.3
- **Typed class constants**
- Mejoras en rendimiento

## Breaking Changes Laravel 5.7 → 11.x

### Laravel 6.x (LTS)
- Requiere PHP 7.2+
- Lazy collections
- Eloquent subquery enhancements
- Job middleware
- **Cambio**: `Str` y `Arr` helpers movidos a clases

### Laravel 7.x
- Requiere PHP 7.2.5+
- Fluent string operations
- HTTP client
- **Cambio**: `date` factory type removido

### Laravel 8.x (LTS)
- Requiere PHP 7.3+
- Laravel Jetstream
- Model factories refactorizadas
- **Cambio**: Namespace `App\Models` por defecto
- **Cambio**: Paginación con Tailwind por defecto

### Laravel 9.x (LTS)
- Requiere PHP 8.0+
- Symfony 6.x
- **Cambio**: `lang` directory → `resources/lang`
- **Cambio**: Flysystem 3.x
- **Cambio**: Sintaxis de rutas actualizada

### Laravel 10.x
- Requiere PHP 8.1+
- **Cambio**: Todos los métodos tienen tipos de retorno
- **Cambio**: Deprecaciones removidas

### Laravel 11.x (LTS)
- Requiere PHP 8.2+
- Estructura de aplicación simplificada
- **Cambio**: Menos archivos de configuración por defecto
- **Cambio**: Rutas simplificadas

## Impacto en SISGEC

### Código Afectado

#### Helpers Deprecados
```php
// Antes (Laravel 5.7)
str_slug($string)
array_get($array, 'key')

// Después (Laravel 6+)
Str::slug($string)
Arr::get($array, 'key')
```

#### Factories
```php
// Antes (Laravel 5.7)
factory(User::class)->create()

// Después (Laravel 8+)
User::factory()->create()
```

#### Namespace de Modelos
```php
// Antes
namespace App;

// Después (Laravel 8+)
namespace App\Models;
```

#### Seeders
```php
// Antes
class DatabaseSeeder extends Seeder

// Después (Laravel 8+)
namespace Database\Seeders;
class DatabaseSeeder extends Seeder
```

#### Rutas
```php
// Antes (Laravel 5.7)
Route::get('/patients', 'PatientController@index');

// Después (Laravel 8+)
use App\Http\Controllers\PatientController;
Route::get('/patients', [PatientController::class, 'index']);
```

### Dependencias Afectadas

#### Composer
- `barryvdh/laravel-dompdf`: Actualizar a versión compatible
- `intervention/image`: Actualizar a v2.7+
- `fideloper/proxy`: Reemplazar con TrustedProxies de Laravel

#### NPM
- `laravel-mix`: Actualizar a v6+
- `vue`: Considerar actualizar a Vue 3
- `bootstrap`: Actualizar a v5

### Archivos a Modificar

1. **composer.json**: Actualizar versiones
2. **package.json**: Actualizar dependencias frontend
3. **app/**: Mover modelos a `app/Models/`
4. **database/seeds/**: Mover a `database/seeders/`
5. **routes/web.php**: Actualizar sintaxis de rutas
6. **config/**: Actualizar archivos de configuración
7. **Controladores**: Agregar tipos de retorno
8. **Modelos**: Agregar tipos de propiedades

## Estimación de Esfuerzo

### Alto Impacto (3-5 días cada uno)
- Actualización de rutas
- Refactorización de factories
- Actualización de seeders
- Testing completo

### Medio Impacto (1-2 días cada uno)
- Actualización de helpers
- Movimiento de modelos
- Actualización de dependencias
- Tipos de retorno en controladores

### Bajo Impacto (<1 día)
- Actualización de configuración
- Namespace updates
- Imports

## Compatibilidad de Dependencias

### Verificar Compatibilidad

```bash
# Verificar versiones disponibles
composer show barryvdh/laravel-dompdf --all
composer show intervention/image --all
```

### Dependencias Críticas

| Paquete | Versión Actual | Versión Objetivo | Compatible |
|---------|----------------|------------------|------------|
| laravel/framework | 5.7.* | 11.* | ⚠️ Requiere cambios |
| barryvdh/laravel-dompdf | 0.8.3 | 2.* | ✅ Compatible |
| intervention/image | 2.4 | 3.* | ⚠️ Breaking changes |
| doctrine/dbal | 2.8 | 4.* | ✅ Compatible |

## Recomendaciones

1. **Crear rama de desarrollo** para actualización
2. **Backup completo** antes de iniciar
3. **Actualizar incrementalmente**, no saltar versiones
4. **Testing exhaustivo** en cada paso
5. **Documentar cambios** realizados
6. **Mantener versión antigua** funcionando hasta validar nueva
