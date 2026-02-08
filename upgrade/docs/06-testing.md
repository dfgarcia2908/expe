# 6. Plan de Testing

## Estrategia de Testing

### Niveles de Testing

1. **Unit Tests**: Modelos y lógica de negocio
2. **Feature Tests**: Controladores y rutas
3. **Browser Tests**: Flujos completos de usuario
4. **Manual Tests**: Verificación visual y UX

## Tests Automatizados

### 1. Configurar PHPUnit

```xml
<!-- phpunit.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true">
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory>tests/Feature</directory>
        </testsuite>
    </testsuites>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
    </php>
</phpunit>
```

### 2. Tests de Modelos

```php
// tests/Unit/PatientTest.php
namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PatientTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_has_full_name_attribute()
    {
        $patient = Patient::factory()->create([
            'name' => 'Juan',
            'lastname' => 'Pérez'
        ]);

        $this->assertEquals('Juan Pérez', $patient->full_name);
    }

    public function test_patient_calculates_age_correctly()
    {
        $patient = Patient::factory()->create([
            'birthdate' => now()->subYears(30)
        ]);

        $this->assertEquals(30, $patient->age);
    }

    public function test_patient_has_initial_clinical_history()
    {
        $patient = Patient::factory()->create();
        
        $this->assertInstanceOf(
            \App\Models\InitialClinicalHistory::class,
            $patient->initial_clinical_history
        );
    }
}
```

### 3. Tests de Controladores

```php
// tests/Feature/PatientControllerTest.php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PatientControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_patients_list()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/patients');
        
        $response->assertStatus(200);
        $response->assertViewIs('doctor.patients.index');
    }

    public function test_user_can_create_patient()
    {
        $user = User::factory()->create();
        
        $data = [
            'patient' => [
                'name' => 'Juan',
                'lastname' => 'Pérez',
                'sex' => 'M'
            ]
        ];
        
        $response = $this->actingAs($user)
            ->post('/patients/save', $data);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('patients', [
            'name' => 'Juan',
            'lastname' => 'Pérez'
        ]);
    }

    public function test_guest_cannot_access_patients()
    {
        $response = $this->get('/patients');
        
        $response->assertRedirect('/login');
    }
}
```

### 4. Tests de Autenticación

```php
// tests/Feature/AuthenticationTest.php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password'
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }
}
```

### 5. Ejecutar Tests

```bash
# Todos los tests
php artisan test

# Tests específicos
php artisan test --filter PatientTest

# Con coverage
php artisan test --coverage

# Parallel
php artisan test --parallel
```

## Tests Manuales

### Checklist de Funcionalidades

#### Autenticación
- [ ] Login con credenciales correctas
- [ ] Login con credenciales incorrectas
- [ ] Logout
- [ ] Recuperación de contraseña
- [ ] Registro de nuevo usuario

#### Pacientes
- [ ] Listar pacientes
- [ ] Crear nuevo paciente
- [ ] Ver expediente de paciente
- [ ] Editar paciente
- [ ] Eliminar paciente
- [ ] Buscar paciente
- [ ] Descargar expediente PDF

#### Citas Médicas
- [ ] Ver calendario de citas
- [ ] Crear nueva cita
- [ ] Editar cita
- [ ] Eliminar cita
- [ ] Vista de lista de citas

#### Notas de Evolución
- [ ] Crear nota de evolución
- [ ] Ver nota de evolución
- [ ] Editar nota de evolución
- [ ] Eliminar nota de evolución
- [ ] Descargar nota PDF

#### Prescripciones
- [ ] Crear receta médica
- [ ] Ver receta
- [ ] Editar receta
- [ ] Eliminar receta
- [ ] Descargar receta PDF

#### Estudios
- [ ] Subir archivo de estudio
- [ ] Ver estudio
- [ ] Descargar estudio
- [ ] Eliminar estudio

#### Asistentes
- [ ] Crear asistente
- [ ] Listar asistentes
- [ ] Editar asistente
- [ ] Eliminar asistente

#### Configuración
- [ ] Actualizar datos del médico
- [ ] Subir logo del consultorio
- [ ] Guardar configuración

#### Estadísticas
- [ ] Ver estadísticas de pacientes
- [ ] Ver estadísticas de citas
- [ ] Filtrar por período

## Tests de Navegadores

### Configurar Laravel Dusk

```bash
composer require --dev laravel/dusk
php artisan dusk:install
```

### Test de Flujo Completo

```php
// tests/Browser/PatientFlowTest.php
namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class PatientFlowTest extends DuskTestCase
{
    public function test_complete_patient_flow()
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/patients/new')
                    ->type('patient[name]', 'Juan')
                    ->type('patient[lastname]', 'Pérez')
                    ->select('patient[sex]', 'M')
                    ->press('Guardar')
                    ->assertPathIs('/patient/*')
                    ->assertSee('Juan Pérez');
        });
    }
}
```

## Tests de Rendimiento

### Benchmark de Consultas

```php
// tests/Performance/QueryPerformanceTest.php
namespace Tests\Performance;

use Tests\TestCase;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;

class QueryPerformanceTest extends TestCase
{
    public function test_patients_list_query_performance()
    {
        Patient::factory()->count(100)->create();

        DB::enableQueryLog();
        
        $start = microtime(true);
        Patient::with('initial_clinical_history')->get();
        $end = microtime(true);

        $queries = DB::getQueryLog();
        
        // Debe ejecutarse en menos de 100ms
        $this->assertLessThan(0.1, $end - $start);
        
        // No debe hacer más de 2 queries (N+1 problem)
        $this->assertLessThanOrEqual(2, count($queries));
    }
}
```

## Tests de Seguridad

```php
// tests/Security/SecurityTest.php
namespace Tests\Security;

use Tests\TestCase;
use App\Models\User;
use App\Models\Patient;

class SecurityTest extends TestCase
{
    public function test_csrf_protection_is_enabled()
    {
        $response = $this->post('/patients/save', []);
        
        $response->assertStatus(419); // CSRF token mismatch
    }

    public function test_sql_injection_is_prevented()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->get('/patients?search=\' OR 1=1--');
        
        $response->assertStatus(200);
        // No debe retornar todos los pacientes
    }

    public function test_xss_is_prevented()
    {
        $user = User::factory()->create();
        
        $patient = Patient::factory()->create([
            'name' => '<script>alert("XSS")</script>'
        ]);
        
        $response = $this->actingAs($user)
            ->get('/patient/' . $patient->id);
        
        $response->assertDontSee('<script>', false);
        $response->assertSee('&lt;script&gt;');
    }
}
```

## Reporte de Testing

### Generar Reporte

```bash
# Coverage HTML
php artisan test --coverage-html coverage/

# Coverage texto
php artisan test --coverage-text

# JUnit XML (para CI/CD)
php artisan test --log-junit junit.xml
```

### Métricas Objetivo

- **Code Coverage**: > 70%
- **Tests pasando**: 100%
- **Tiempo de ejecución**: < 5 minutos
- **N+1 queries**: 0

## Checklist de Testing

- [ ] PHPUnit configurado
- [ ] Tests unitarios creados
- [ ] Tests de features creados
- [ ] Tests de autenticación pasando
- [ ] Tests de pacientes pasando
- [ ] Tests de citas pasando
- [ ] Tests de prescripciones pasando
- [ ] Tests manuales completados
- [ ] Tests de navegador ejecutados
- [ ] Tests de rendimiento pasando
- [ ] Tests de seguridad pasando
- [ ] Coverage > 70%
- [ ] Reporte generado

## Siguiente Paso

Proceder con:
- [Plan de Rollback](07-rollback.md)
