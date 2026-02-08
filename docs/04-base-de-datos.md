# 4. Base de Datos

## Diagrama de Relaciones

```
users (1) ──── (1) doctors (1) ──── (*) medical_appointments
                                    (*) assistants

patients (1) ──── (1) initial_clinical_history
         (1) ──── (1) measures
         (1) ──── (*) medical_appointments

initial_clinical_history (1) ──── (1) anamnesis
                         (1) ──── (1) physical_exploration
                         (1) ──── (*) studies
                         (1) ──── (*) tracings
                         (1) ──── (*) prescriptions

anamnesis (1) ──── (1) non_pathologicals
          (1) ──── (1) pathological_personals
          (1) ──── (1) gynecological_obstetric_histories

physical_exploration (1) ──── (1) neurological_examinations

neurological_examinations (1) ──── (1) orientations
                          (1) ──── (1) superior_cognitive_functions

tracings (1) ──── (*) prescriptions
         (1) ──── (1) measures
```

## Tablas Principales

### users
Usuarios del sistema (médicos y asistentes)

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | ID único |
| name | varchar | Nombre completo |
| email | varchar | Email (único) |
| email_verified_at | timestamp | Verificación de email |
| password | varchar | Contraseña hasheada |
| title | varchar | Título profesional |
| remember_token | varchar | Token de sesión |
| created_at | timestamp | Fecha de creación |
| updated_at | timestamp | Última actualización |

### doctors
Información de médicos

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | ID único |
| user_id | bigint | FK a users |
| professional_license | varchar | Cédula profesional |
| specialty | varchar | Especialidad médica |
| phone | varchar | Teléfono |
| address | text | Dirección del consultorio |
| created_at | timestamp | Fecha de creación |
| updated_at | timestamp | Última actualización |

### patients
Información de pacientes

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | ID único |
| name | varchar | Nombre(s) |
| lastname | varchar | Apellidos |
| nickname | varchar | Apodo |
| sex | varchar | Sexo (M/F) |
| birthdate | date | Fecha de nacimiento |
| scholarship | varchar | Escolaridad |
| occupation | varchar | Ocupación |
| religion | varchar | Religión |
| civil_status | varchar | Estado civil |
| place_of_residence | varchar | Lugar de residencia |
| place_of_birth | varchar | Lugar de nacimiento |
| referred_by | varchar | Referido por |
| email | varchar | Email |
| rfc | varchar | RFC |
| phone | varchar | Teléfono |
| created_at | timestamp | Fecha de creación |
| updated_at | timestamp | Última actualización |

### initial_clinical_history
Historia clínica inicial

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | ID único |
| patient_id | bigint | FK a patients |
| current_condition | text | Padecimiento actual |
| diagnostical_impression | text | Impresión diagnóstica |
| treatment_plan | text | Plan de tratamiento |
| interconsultation | text | Interconsulta |
| treatment | text | Tratamiento |
| created_at | timestamp | Fecha de creación |
| updated_at | timestamp | Última actualización |

### medical_appointments
Citas médicas

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | ID único |
| patient_id | bigint | FK a patients |
| doctor_id | bigint | FK a doctors |
| date | datetime | Fecha y hora de la cita |
| title | varchar | Título de la cita |
| description | text | Descripción |
| created_at | timestamp | Fecha de creación |
| updated_at | timestamp | Última actualización |

### tracings
Notas de evolución

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | ID único |
| initial_clinical_history_id | bigint | FK a initial_clinical_history |
| subjective | text | Subjetivo |
| objective | text | Objetivo |
| analysis | text | Análisis |
| plan | text | Plan |
| created_at | timestamp | Fecha de creación |
| updated_at | timestamp | Última actualización |

### prescriptions
Recetas médicas

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | ID único |
| initial_clinical_history_id | bigint | FK a initial_clinical_history |
| tracing_id | bigint | FK a tracings (nullable) |
| measure_id | bigint | FK a measures (nullable) |
| prescription | text | Contenido de la receta |
| created_at | timestamp | Fecha de creación |
| updated_at | timestamp | Última actualización |

## Modelos Eloquent

### Patient.php
```php
// Relaciones
hasOne('InitialClinicalHistory')
hasOne('Measure')
hasMany('MedicalAppointment')

// Atributos calculados
full_name: nombre completo
age: edad calculada desde birthdate
last_update: última actualización
total_visits: total de visitas
```

### Doctor.php
```php
// Relaciones
belongsTo('User')
hasMany('MedicalAppointment')
hasMany('Assistant')
```

### InitialClinicalHistory.php
```php
// Relaciones
belongsTo('Patient')
hasOne('Anamnesis')
hasOne('PhysicalExploration')
hasMany('Study')
hasMany('Tracing')
hasMany('Prescription')

// Atributos calculados
folio: número de folio formateado
```

### MedicalAppointment.php
```php
// Relaciones
belongsTo('Patient')
belongsTo('Doctor')
```

## Migraciones

Total de migraciones: 26

1. create_users_table
2. create_password_resets_table
3. create_doctors_table
4. create_studies_table
5. create_patients_table
6. create_anamnesis_table
7. create_non_pathologicals_table
8. create_pathological_personals_table
9. create_gynecological_obstetric_histories_table
10. create_initial_clinical_histories_table
11. create_physical_explorations_table
12. create_neurological_examinations_table
13. create_orientations_table
14. create_superior_cognitive_functions_table
15. create_measures_table
16. add_measure_id_to_patients
17. add_title_to_users
18. create_tracings_table
19. add_initial_clinical_history_tracing_table
20. create_prescriptions_table
21. add_relation_from_prescriptions_to_ich
22. add_relation_from_prescriptions_to_measures
23. create_medical_appointments_table
24. create_configs_table
25. create_assistants_table

## Seeders

### UsersTableSeeder
Crea usuario administrador por defecto:
- Email: admin@nidiasoft.com
- Password: admin

### SettingsTableSeeder
Configura opciones iniciales del sistema
