# 9. Guía para Desarrolladores

## Configuración del Entorno de Desarrollo

### Requisitos Previos

- PHP >= 7.1.3
- Composer
- Node.js y npm
- Git
- Editor de código (VS Code, PHPStorm, Sublime Text)

### Clonar y Configurar

```bash
git clone https://github.com/SISGEC/SISGEC.git
cd SISGEC
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan db:seed
npm run dev
php artisan serve
```

## Estructura del Proyecto

### Convenciones de Código

#### PHP (PSR-2)

```php
<?php

namespace App\Http\Controllers;

use App\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = Patient::orderBy('created_at', 'desc')->get();
        return view('doctor.patients.index', ['patients' => $patients]);
    }
}
```

#### JavaScript (ES6)

```javascript
// Usar const/let en lugar de var
const patientId = 123;
let patientName = 'Juan Pérez';

// Arrow functions
const getPatient = (id) => {
    return axios.get(`/patient/${id}`);
};

// Template literals
console.log(`Paciente: ${patientName}`);
```

#### Blade Templates

```blade
@extends('layouts.sisgec')

@section('title', 'Lista de Pacientes')

@section('content')
    <div class="container">
        <h1>Pacientes</h1>
        
        @foreach($patients as $patient)
            <div class="patient-card">
                {{ $patient->full_name }}
            </div>
        @endforeach
    </div>
@endsection
```

## Crear un Nuevo Módulo

### 1. Crear Migración

```bash
php artisan make:migration create_appointments_table
```

```php
// database/migrations/xxxx_create_appointments_table.php
public function up()
{
    Schema::create('appointments', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedBigInteger('patient_id');
        $table->dateTime('date');
        $table->string('title');
        $table->text('description')->nullable();
        $table->timestamps();
        
        $table->foreign('patient_id')
              ->references('id')
              ->on('patients')
              ->onDelete('cascade');
    });
}
```

### 2. Crear Modelo

```bash
php artisan make:model Appointment
```

```php
// app/Appointment.php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['patient_id', 'date', 'title', 'description'];
    
    protected $dates = ['date', 'created_at', 'updated_at'];
    
    public function patient()
    {
        return $this->belongsTo('App\Patient');
    }
    
    public static function get_defaults()
    {
        return [
            'title' => '',
            'description' => ''
        ];
    }
}
```

### 3. Crear Controlador

```bash
php artisan make:controller AppointmentController --resource
```

```php
// app/Http/Controllers/AppointmentController.php
<?php

namespace App\Http\Controllers;

use App\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with('patient')
                                   ->orderBy('date', 'desc')
                                   ->get();
        return view('doctor.appointments.index', compact('appointments'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date' => 'required|date',
            'title' => 'required|string|max:255',
        ]);
        
        $appointment = Appointment::create($request->all());
        
        return redirect()
            ->route('appointments.show', $appointment->id)
            ->with('notify', [[
                'type' => 'success',
                'message' => 'Cita creada correctamente'
            ]]);
    }
}
```

### 4. Definir Rutas

```php
// routes/web.php
Route::middleware(['auth'])->group(function () {
    Route::resource('appointments', 'AppointmentController');
});
```

### 5. Crear Vistas

```blade
{{-- resources/views/doctor/appointments/index.blade.php --}}
@extends('layouts.sisgec')

@section('title', 'Citas Médicas')

@section('content')
<div class="container">
    <h1>Citas Médicas</h1>
    
    <a href="{{ route('appointments.create') }}" class="btn btn-primary">
        Nueva Cita
    </a>
    
    <table class="table datatable">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Paciente</th>
                <th>Título</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appointment)
            <tr>
                <td>{{ $appointment->date->format('d/m/Y H:i') }}</td>
                <td>{{ $appointment->patient->full_name }}</td>
                <td>{{ $appointment->title }}</td>
                <td>
                    <a href="{{ route('appointments.show', $appointment->id) }}">
                        Ver
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
```

## Trabajar con la Base de Datos

### Eloquent ORM

#### Consultas Básicas

```php
// Obtener todos
$patients = Patient::all();

// Obtener con condición
$patient = Patient::where('name', 'Juan')->first();

// Obtener con múltiples condiciones
$patients = Patient::where('sex', 'M')
                   ->where('age', '>', 18)
                   ->get();

// Ordenar
$patients = Patient::orderBy('created_at', 'desc')->get();

// Limitar resultados
$patients = Patient::take(10)->get();

// Paginación
$patients = Patient::paginate(15);
```

#### Relaciones

```php
// Eager Loading (recomendado)
$patients = Patient::with('initial_clinical_history')->get();

// Lazy Loading
$patient = Patient::find(1);
$history = $patient->initial_clinical_history;

// Crear con relación
$patient = Patient::find(1);
$patient->medical_appointments()->create([
    'date' => now(),
    'title' => 'Consulta de seguimiento'
]);
```

#### Crear y Actualizar

```php
// Crear
$patient = Patient::create([
    'name' => 'Juan',
    'lastname' => 'Pérez',
    'sex' => 'M'
]);

// Actualizar
$patient = Patient::find(1);
$patient->update(['name' => 'Juan Carlos']);

// O
$patient->name = 'Juan Carlos';
$patient->save();

// Crear o actualizar
Patient::updateOrCreate(
    ['email' => 'juan@example.com'],
    ['name' => 'Juan', 'lastname' => 'Pérez']
);
```

### Query Builder

```php
use Illuminate\Support\Facades\DB;

// Select
$patients = DB::table('patients')
              ->where('sex', 'M')
              ->get();

// Join
$results = DB::table('patients')
             ->join('initial_clinical_history', 'patients.id', '=', 'initial_clinical_history.patient_id')
             ->select('patients.*', 'initial_clinical_history.current_condition')
             ->get();

// Agregaciones
$count = DB::table('patients')->count();
$avg = DB::table('measures')->avg('weight');
```

## Testing

### Crear Tests

```bash
php artisan make:test PatientTest
```

```php
// tests/Feature/PatientTest.php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PatientTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_can_view_patients_list()
    {
        $user = factory(User::class)->create();
        
        $response = $this->actingAs($user)
                         ->get('/patients');
        
        $response->assertStatus(200);
        $response->assertViewIs('doctor.patients.index');
    }
    
    public function test_user_can_create_patient()
    {
        $user = factory(User::class)->create();
        
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
}
```

### Ejecutar Tests

```bash
# Todos los tests
php artisan test

# Test específico
php artisan test --filter PatientTest

# Con coverage
php artisan test --coverage
```

## Debugging

### Laravel Debugbar

```bash
composer require barryvdh/laravel-debugbar --dev
```

### dd() y dump()

```php
// Detener ejecución y mostrar variable
dd($patient);

// Mostrar sin detener
dump($patient);

// En Blade
@dd($patient)
@dump($patient)
```

### Log

```php
use Illuminate\Support\Facades\Log;

Log::debug('Debug message', ['patient_id' => $patient->id]);
Log::info('Info message');
Log::warning('Warning message');
Log::error('Error message');
```

### Tinker

```bash
php artisan tinker

>>> $patient = App\Patient::find(1)
>>> $patient->name
>>> $patient->initial_clinical_history
```

## Comandos Artisan Útiles

### Desarrollo

```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Ver rutas
php artisan route:list

# Ver configuración
php artisan config:show

# Crear enlace simbólico storage
php artisan storage:link

# Modo mantenimiento
php artisan down
php artisan up
```

### Base de Datos

```bash
# Migrar
php artisan migrate

# Rollback
php artisan migrate:rollback

# Refrescar (drop all + migrate)
php artisan migrate:fresh

# Refrescar con seeders
php artisan migrate:fresh --seed

# Ver estado de migraciones
php artisan migrate:status
```

### Generadores

```bash
php artisan make:model Patient
php artisan make:controller PatientController
php artisan make:migration create_patients_table
php artisan make:seeder PatientSeeder
php artisan make:factory PatientFactory
php artisan make:request PatientRequest
php artisan make:middleware CheckRole
php artisan make:command SendReminders
```

## Git Workflow

### Branching Strategy

```bash
# Crear rama para nueva funcionalidad
git checkout -b feature/nueva-funcionalidad

# Hacer cambios y commits
git add .
git commit -m "Agregar nueva funcionalidad"

# Actualizar desde master
git checkout master
git pull origin master
git checkout feature/nueva-funcionalidad
git rebase master

# Push
git push origin feature/nueva-funcionalidad

# Crear Pull Request en GitHub
```

### Commits Semánticos

```bash
git commit -m "feat: agregar módulo de citas"
git commit -m "fix: corregir error en validación de pacientes"
git commit -m "docs: actualizar README"
git commit -m "style: formatear código"
git commit -m "refactor: reorganizar controladores"
git commit -m "test: agregar tests para pacientes"
git commit -m "chore: actualizar dependencias"
```

## Mejores Prácticas

### Controladores

- Mantener controladores delgados
- Mover lógica compleja a servicios o modelos
- Un método por acción
- Validar entrada

### Modelos

- Definir relaciones claramente
- Usar accessors y mutators para lógica de atributos
- Implementar scopes para consultas comunes

```php
// Accessor
public function getFullNameAttribute()
{
    return "{$this->name} {$this->lastname}";
}

// Mutator
public function setNameAttribute($value)
{
    $this->attributes['name'] = ucfirst($value);
}

// Scope
public function scopeMale($query)
{
    return $query->where('sex', 'M');
}

// Uso
$patient->full_name;
$patient->name = 'juan'; // Se guarda como 'Juan'
Patient::male()->get();
```

### Vistas

- Usar componentes reutilizables
- Evitar lógica compleja en vistas
- Usar @include para código repetitivo
- Aprovechar layouts

### Seguridad

- Siempre validar entrada
- Usar Eloquent para prevenir SQL injection
- Escapar salida en vistas
- Proteger rutas con middleware
- No exponer información sensible

## Recursos Adicionales

### Documentación

- [Laravel 5.7](https://laravel.com/docs/5.7)
- [Vue.js](https://vuejs.org/)
- [Bootstrap 4](https://getbootstrap.com/docs/4.3/)

### Herramientas

- [Laravel Telescope](https://laravel.com/docs/5.7/telescope) - Debugging
- [Laravel Debugbar](https://github.com/barryvdh/laravel-debugbar) - Profiling
- [PHPStorm](https://www.jetbrains.com/phpstorm/) - IDE
- [VS Code](https://code.visualstudio.com/) - Editor

### Comunidad

- [GitHub Issues](https://github.com/SISGEC/SISGEC/issues)
- [Wiki del Proyecto](https://github.com/SISGEC/SISGEC/wiki)
