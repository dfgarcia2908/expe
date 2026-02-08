# 6. Rutas y API

## Rutas de Autenticación

### Login
```
GET  /login                    - Mostrar formulario de login
POST /login                    - Procesar login
```

### Registro
```
GET  /register                 - Mostrar formulario de registro
POST /register                 - Procesar registro
```

### Recuperación de Contraseña
```
GET  /password/reset           - Formulario de recuperación
POST /password/email           - Enviar email de recuperación
GET  /password/reset/{token}   - Formulario de nueva contraseña
POST /password/reset           - Actualizar contraseña
```

### Logout
```
POST /logout                   - Cerrar sesión
```

## Rutas Protegidas (Requieren Autenticación)

### Dashboard
```
GET  /                         - Dashboard principal
                                 Controller: HomeController@index
                                 Name: dashboard
```

### Configuración General
```
POST /options/save             - Guardar opciones del sistema
                                 Controller: HomeController@options
                                 Name: options.save
```

## Rutas de Configuración del Médico

```
GET  /settings                 - Ver configuración
                                 Controller: DoctorController@settings
                                 Name: doctor.settings

POST /settings/update          - Actualizar configuración
                                 Controller: DoctorController@update
                                 Name: doctor.update
```

## Rutas de Pacientes

### Listado y Visualización
```
GET  /patients                 - Listar todos los pacientes
                                 Controller: PatientController@index
                                 Name: patients

GET  /patient/{id}             - Ver expediente de paciente
                                 Controller: PatientController@show
                                 Name: patient
```

### Crear Paciente
```
GET  /patients/new             - Formulario nuevo paciente
                                 Controller: PatientController@create
                                 Name: patients.new

POST /patients/save            - Guardar nuevo paciente
                                 Controller: PatientController@store
                                 Name: patients.save
```

### Editar Paciente
```
GET  /patients/edit/{id}       - Formulario editar paciente
                                 Controller: PatientController@edit
                                 Name: patients.edit

POST /patients/update          - Actualizar paciente
                                 Controller: PatientController@update
                                 Name: patients.update
```

### Eliminar Paciente
```
GET  /patient/delete/{id}      - Eliminar paciente
                                 Controller: PatientController@destroy
                                 Name: patient.remove
```

### Descargar PDF
```
GET  /patient/{id}/download    - Descargar expediente en PDF
                                 Controller: PatientController@download
                                 Name: patient.download
                                 Query params: ?doc=initial|identification_card|informed_consent
```

## Rutas de Asistentes

### Listado y Visualización
```
GET  /assistants               - Listar asistentes
                                 Controller: AssistantController@index
                                 Name: assistants

GET  /assistant/{id}           - Ver asistente
                                 Controller: AssistantController@show
                                 Name: assistant
```

### Crear Asistente
```
GET  /assistants/new           - Formulario nuevo asistente
                                 Controller: AssistantController@create
                                 Name: assistants.new

POST /assistants/save          - Guardar asistente
                                 Controller: AssistantController@store
                                 Name: assistants.save
```

### Editar Asistente
```
GET  /assistants/edit/{id}     - Formulario editar asistente
                                 Controller: AssistantController@edit
                                 Name: assistants.edit

POST /assistants/update        - Actualizar asistente
                                 Controller: AssistantController@update
                                 Name: assistants.update
```

### Eliminar Asistente
```
GET  /assistant/delete/{id}    - Eliminar asistente
                                 Controller: AssistantController@destroy
                                 Name: assistant.remove
```

## Rutas de Citas Médicas

### Listado y Visualización
```
GET  /medical-appointments     - Listar citas
                                 Controller: MedicalAppointmentController@index
                                 Name: medical_appointments

GET  /medical-appointment/{id} - Ver cita específica
                                 Controller: MedicalAppointmentController@show
                                 Name: medical_appointment
```

### Crear y Editar Citas
```
POST /medical-appointments/save    - Crear nueva cita
                                     Controller: MedicalAppointmentController@store
                                     Name: medical_appointments.save

POST /medical-appointments/update  - Actualizar cita
                                     Controller: MedicalAppointmentController@update
                                     Name: medical_appointments.update
```

### Eliminar Cita
```
GET  /medical-appointment/{id}/remove  - Eliminar cita
                                         Controller: MedicalAppointmentController@destroy
                                         Name: medical_appointments.remove
```

### API JSON (Sin autenticación)
```
GET  /medical-appointments.json    - Obtener citas en formato JSON
                                     Controller: MedicalAppointmentController@json
                                     Name: medical_appointments.json
                                     Response: JSON con eventos para FullCalendar
```

## Rutas de Notas de Evolución

### Crear Nota
```
GET  /evolution-note/new/{id?}     - Formulario nueva nota
                                     Controller: TracingController@create
                                     Name: evolution_note.new
                                     Params: id = patient_id (opcional)

POST /evolution-note/save          - Guardar nota
                                     Controller: TracingController@store
                                     Name: evolution_note.save
```

### Ver y Editar Nota
```
GET  /evolution-note/{id}          - Ver nota
                                     Controller: TracingController@show
                                     Name: evolution_note

GET  /evolution-note/edit/{id}     - Formulario editar nota
                                     Controller: TracingController@edit
                                     Name: evolution_note.edit

POST /evolution-note/update        - Actualizar nota
                                     Controller: TracingController@update
                                     Name: evolution_note.update
```

### Eliminar Nota
```
GET  /evolution-note/remove/{id}   - Eliminar nota
                                     Controller: TracingController@destroy
                                     Name: evolution_note.remove
```

### Descargar PDF
```
GET  /evolution-note/{id}/download - Descargar nota en PDF
                                     Controller: TracingController@download
                                     Name: evolution_note.download
```

## Rutas de Prescripciones

### Crear Prescripción
```
GET  /prescription/new             - Formulario nueva receta
                                     Controller: PrescriptionController@create
                                     Name: prescription.new

POST /prescription/save            - Guardar receta
                                     Controller: PrescriptionController@store
                                     Name: prescription.save
```

### Ver y Editar Prescripción
```
GET  /prescription/{id}            - Ver receta
                                     Controller: PrescriptionController@index
                                     Name: prescription

GET  /prescription/edit/{id}       - Formulario editar receta
                                     Controller: PrescriptionController@edit
                                     Name: prescription.edit

POST /prescription/update          - Actualizar receta
                                     Controller: PrescriptionController@update
                                     Name: prescription.update
```

### Eliminar Prescripción
```
GET  /prescription/remove/{id}     - Eliminar receta
                                     Controller: PrescriptionController@destroy
                                     Name: prescription.remove
```

### Descargar PDF
```
GET  /prescription/{id}/download   - Descargar receta en PDF
                                     Controller: PrescriptionController@download
                                     Name: prescription.download
```

## Rutas de Estudios/Adjuntos

### Gestión de Archivos
```
GET  /attachments                  - Formulario subir archivo
                                     Controller: StudiesController@create

POST /attachments/save             - Guardar archivo
                                     Controller: StudiesController@store

GET  /attachments/delete/{id}      - Eliminar archivo
                                     Controller: StudiesController@destroy
```

### Visualización y Descarga (Sin autenticación)
```
GET  /attachments/show/{filename}      - Ver archivo
                                         Controller: StudiesController@show

GET  /attachments/download/{filename}  - Descargar archivo
                                         Controller: StudiesController@download
```

## Rutas de Estadísticas

### Estadísticas de Pacientes
```
GET  /statistics/patients/{metric?}/{lapse?}
     Controller: PatientController@statistics
     Name: statistics.patients
     
     Metrics:
     - patients_treated: Pacientes atendidos
     - unique_patients: Pacientes nuevos
     
     Lapse:
     - last_week: Última semana
     - last_month: Último mes
     - last_year: Último año (default)
     
     Response: JSON
```

### Estadísticas de Citas
```
GET  /statistics/appointments/{metric?}/{lapse?}
     Controller: MedicalAppointmentController@statistics
     Name: statistics.appointments
     
     Response: JSON
```

### Estadísticas Mensuales
```
GET  /statistics/monthly_statistics/{lapse}
     Controller: HomeController@monthly_statistics
     Name: statistics.monthly_statistics
     
     Response: JSON con datos para gráficos
```

## Ruta de Prueba

```
GET  /test                         - Endpoint de prueba
                                     Response: "Ok" (200)
```

## Middleware Aplicado

### Grupo 'auth'
Todas las rutas excepto:
- Rutas de autenticación
- `/attachments/show/{filename}`
- `/medical-appointments.json`
- `/test`

### Middleware CSRF
Todas las rutas POST están protegidas con token CSRF

## Formato de Respuestas

### Respuestas HTML
La mayoría de rutas GET retornan vistas Blade

### Respuestas JSON
Rutas de estadísticas y API retornan JSON:

```json
{
  "count": 30,
  "total": 150,
  "data": {
    "2024-01-01": 5,
    "2024-01-02": 3
  },
  "sparkline": [5, 3, 7, 2]
}
```

### Respuestas PDF
Rutas de descarga retornan archivos PDF:
- Content-Type: application/pdf
- Content-Disposition: attachment

### Redirecciones
Después de operaciones POST:
- Éxito: Redirect con mensaje de éxito
- Error: Redirect back con mensaje de error

## Notificaciones

Las rutas que modifican datos utilizan sesión flash para notificaciones:

```php
$notify = [
    [
        "type" => "success|error|warning|info",
        "message" => "Mensaje descriptivo"
    ]
];
return redirect()->route('ruta')->with("notify", $notify);
```

## Generación de URLs

### En Vistas Blade
```php
{{ route('patient', ['id' => $patient->id]) }}
{{ route('patients.edit', ['id' => 1]) }}
{{ route('dashboard') }}
```

### En Controladores
```php
return redirect()->route('patient', ['id' => $patient->id]);
return redirect()->route('patients');
```

### En JavaScript
```javascript
window.location.href = "{{ route('patient', ['id' => $patient->id]) }}";
```
