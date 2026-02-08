# 5. Módulos y Funcionalidades

## Módulo de Autenticación

### Funcionalidades
- Login de usuarios
- Registro de nuevos usuarios
- Recuperación de contraseña
- Verificación de email
- Cierre de sesión

### Rutas
- `GET /login` - Formulario de login
- `POST /login` - Procesar login
- `GET /register` - Formulario de registro
- `POST /register` - Procesar registro
- `GET /password/reset` - Recuperar contraseña
- `POST /logout` - Cerrar sesión

### Controladores
- `LoginController`
- `RegisterController`
- `ForgotPasswordController`
- `ResetPasswordController`
- `VerificationController`

## Módulo de Dashboard

### Funcionalidades
- Vista general del consultorio
- Estadísticas de pacientes
- Estadísticas de citas
- Gráficos de actividad
- Acceso rápido a funciones principales

### Rutas
- `GET /` - Dashboard principal

### Controladores
- `HomeController@index`

### Vistas
- `doctor/desktop.blade.php`

## Módulo de Pacientes

### Funcionalidades

#### Gestión de Pacientes
- Listar todos los pacientes
- Crear nuevo paciente
- Ver expediente completo
- Editar información del paciente
- Eliminar paciente
- Búsqueda de pacientes
- Descargar expediente en PDF

#### Información del Paciente
- Datos personales
- Datos de contacto
- Información demográfica
- Medidas antropométricas

#### Historia Clínica Inicial
- Padecimiento actual
- Impresión diagnóstica
- Plan de tratamiento
- Interconsulta
- Tratamiento

#### Anamnesis
- Antecedentes heredofamiliares
- Antecedentes no patológicos
- Antecedentes patológicos personales
- Historia gineco-obstétrica (mujeres)

#### Exploración Física
- Signos vitales
- Exploración por sistemas
- Examen neurológico
- Orientación
- Funciones cognitivas superiores

### Rutas
- `GET /patients` - Listar pacientes
- `GET /patients/new` - Formulario nuevo paciente
- `POST /patients/save` - Guardar nuevo paciente
- `GET /patient/{id}` - Ver expediente
- `GET /patients/edit/{id}` - Editar paciente
- `POST /patients/update` - Actualizar paciente
- `GET /patient/delete/{id}` - Eliminar paciente
- `GET /patient/{id}/download` - Descargar PDF

### Controladores
- `PatientController`

### Modelos
- `Patient`
- `InitialClinicalHistory`
- `Anamnesis`
- `NonPathological`
- `PathologicalPersonal`
- `GynecologicalObstetricHistory`
- `PhysicalExploration`
- `NeurologicalExamination`
- `Orientation`
- `SuperiorCognitiveFunctions`
- `Measure`

### Vistas
- `doctor/patients/index.blade.php` - Lista
- `doctor/patients/new.blade.php` - Crear
- `doctor/patients/self.blade.php` - Ver
- `doctor/patients/edit.blade.php` - Editar

## Módulo de Citas Médicas

### Funcionalidades
- Calendario de citas
- Crear nueva cita
- Editar cita existente
- Eliminar cita
- Vista de lista de citas
- Asociar cita con paciente
- Notificaciones de citas

### Rutas
- `GET /medical-appointments` - Lista de citas
- `GET /medical-appointment/{id}` - Ver cita
- `POST /medical-appointments/save` - Crear cita
- `POST /medical-appointments/update` - Actualizar cita
- `GET /medical-appointment/{id}/remove` - Eliminar cita
- `GET /medical-appointments.json` - API JSON para calendario

### Controladores
- `MedicalAppointmentController`

### Modelos
- `MedicalAppointment`

### Vistas
- `doctor/appointments/index.blade.php`

### Componentes JavaScript
- FullCalendar para vista de calendario
- AJAX para operaciones CRUD

## Módulo de Notas de Evolución

### Funcionalidades
- Crear nota de evolución (SOAP)
- Editar nota de evolución
- Ver historial de notas
- Eliminar nota
- Descargar nota en PDF
- Asociar prescripciones a notas

### Formato SOAP
- **S**ubjetivo: Lo que el paciente refiere
- **O**bjetivo: Hallazgos del examen físico
- **A**nálisis: Evaluación y diagnóstico
- **P**lan: Plan de tratamiento

### Rutas
- `GET /evolution-note/new/{id?}` - Nueva nota
- `POST /evolution-note/save` - Guardar nota
- `GET /evolution-note/{id}` - Ver nota
- `GET /evolution-note/edit/{id}` - Editar nota
- `POST /evolution-note/update` - Actualizar nota
- `GET /evolution-note/remove/{id}` - Eliminar nota
- `GET /evolution-note/{id}/download` - Descargar PDF

### Controladores
- `TracingController`

### Modelos
- `Tracing`

### Vistas
- `doctor/tracing/new.blade.php`
- `doctor/tracing/edit.blade.php`
- `doctor/tracing/self.blade.php`

## Módulo de Prescripciones

### Funcionalidades
- Crear receta médica
- Editar receta
- Ver receta
- Eliminar receta
- Descargar receta en PDF
- Asociar a historia clínica o nota de evolución
- Incluir medidas antropométricas

### Rutas
- `GET /prescription/new` - Nueva receta
- `POST /prescription/save` - Guardar receta
- `GET /prescription/{id}` - Ver receta
- `GET /prescription/edit/{id}` - Editar receta
- `POST /prescription/update` - Actualizar receta
- `GET /prescription/remove/{id}` - Eliminar receta
- `GET /prescription/{id}/download` - Descargar PDF

### Controladores
- `PrescriptionController`

### Modelos
- `Prescription`

### Vistas
- `doctor/prescriptions/new.blade.php`
- `doctor/prescriptions/edit.blade.php`

## Módulo de Estudios

### Funcionalidades
- Subir archivos de estudios (laboratorio, imágenes)
- Asociar estudios a pacientes
- Ver estudios
- Descargar estudios
- Eliminar estudios

### Formatos Soportados
- Documentos: .doc, .docx, .pdf
- Imágenes: .jpg, .png, .gif

### Rutas
- `GET /attachments` - Subir estudio
- `POST /attachments/save` - Guardar estudio
- `GET /attachments/show/{filename}` - Ver estudio
- `GET /attachments/download/{filename}` - Descargar
- `GET /attachments/delete/{id}` - Eliminar

### Controladores
- `StudiesController`

### Modelos
- `Study`

## Módulo de Asistentes

### Funcionalidades
- Registrar asistentes médicos
- Listar asistentes
- Editar información de asistente
- Ver perfil de asistente
- Eliminar asistente
- Asignar permisos limitados

### Rutas
- `GET /assistants` - Listar asistentes
- `GET /assistants/new` - Nuevo asistente
- `POST /assistants/save` - Guardar asistente
- `GET /assistant/{id}` - Ver asistente
- `GET /assistants/edit/{id}` - Editar asistente
- `POST /assistants/update` - Actualizar asistente
- `GET /assistant/delete/{id}` - Eliminar asistente

### Controladores
- `AssistantController`

### Modelos
- `Assistant`

### Vistas
- `doctor/assistants/index.blade.php`
- `doctor/assistants/new.blade.php`
- `doctor/assistants/edit.blade.php`

## Módulo de Configuración

### Funcionalidades
- Configurar datos del médico
- Configurar datos del consultorio
- Subir logo del consultorio
- Subir marca del consultorio
- Configurar información de contacto
- Personalizar documentos PDF

### Rutas
- `GET /settings` - Configuración
- `POST /settings/update` - Actualizar configuración
- `POST /options/save` - Guardar opciones

### Controladores
- `DoctorController@settings`
- `DoctorController@update`
- `HomeController@options`

### Modelos
- `Doctor`
- `Settings`

### Vistas
- `doctor/settings/index.blade.php`

## Módulo de Estadísticas

### Funcionalidades
- Estadísticas de pacientes atendidos
- Estadísticas de pacientes únicos
- Estadísticas de citas médicas
- Gráficos de actividad mensual
- Filtros por período (semana, mes, año)
- Exportación de datos

### Rutas
- `GET /statistics/patients/{metric?}/{lapse?}` - Stats pacientes
- `GET /statistics/appointments/{metric?}/{lapse?}` - Stats citas
- `GET /statistics/monthly_statistics/{lapse}` - Stats mensuales

### Controladores
- `PatientController@statistics`
- `MedicalAppointmentController@statistics`
- `HomeController@monthly_statistics`

### Métricas Disponibles
- Pacientes atendidos
- Pacientes únicos (nuevos)
- Citas programadas
- Citas completadas

### Períodos
- Última semana
- Último mes
- Último año

## Módulo de Generación de PDFs

### Documentos Generados

#### Expediente Clínico Inicial
- Datos del paciente
- Historia clínica completa
- Anamnesis
- Exploración física
- Diagnóstico y tratamiento

#### Nota de Evolución
- Formato SOAP
- Fecha de consulta
- Signos vitales
- Evolución del paciente

#### Receta Médica
- Datos del médico
- Datos del paciente
- Medicamentos prescritos
- Indicaciones
- Firma y cédula profesional

#### Tarjeta de Identificación
- Datos básicos del paciente
- Código QR (opcional)
- Información de contacto

#### Consentimiento Informado
- Datos del paciente
- Procedimiento a realizar
- Riesgos y beneficios
- Firma del paciente

### Vistas PDF
- `pdf/initial.blade.php`
- `pdf/tracing.blade.php`
- `pdf/prescription.blade.php`
- `pdf/identification_card.blade.php`
- `pdf/informed_consent.blade.php`

## Funcionalidades Transversales

### Autoguardado
- Guardado automático de formularios
- Prevención de pérdida de datos
- Indicador visual de guardado

### Búsqueda
- Búsqueda de pacientes por nombre
- Autocompletado
- Búsqueda rápida en listas

### Notificaciones
- Notificaciones de éxito
- Notificaciones de error
- Notificaciones de advertencia
- Sistema de alertas con SweetAlert

### Validación
- Validación de formularios en cliente
- Validación de formularios en servidor
- Mensajes de error descriptivos

### Responsividad
- Diseño adaptable a móviles
- Diseño adaptable a tablets
- Optimizado para escritorio
