# 1. Introducción a SISGEC

## ¿Qué es SISGEC?

SISGEC (Sistema Gestor de Expedientes Clínicos) es una aplicación web diseñada para gestionar expedientes clínicos en consultorios médicos pequeños, específicamente aquellos encuadrados en el nivel uno de la atención médica en México.

## Problemática que Resuelve

El sistema aborda las siguientes problemáticas:

- **Eficiencia en la gestión**: Optimiza el tiempo del personal médico al digitalizar los expedientes clínicos
- **Organización de información**: Centraliza toda la información de los pacientes en un solo lugar
- **Cumplimiento normativo**: Se ajusta a la normatividad mexicana aplicable a expedientes clínicos
- **Accesibilidad**: Permite acceso rápido a la información médica desde cualquier dispositivo con navegador web

## Características Principales

### Gestión de Pacientes
- Registro completo de datos personales
- Historial clínico inicial detallado
- Seguimiento de consultas y evolución
- Generación de expedientes en PDF

### Gestión de Citas Médicas
- Calendario de citas
- Programación y seguimiento de consultas
- Notificaciones y recordatorios

### Expediente Clínico Completo
- **Anamnesis**: Antecedentes heredofamiliares, no patológicos, patológicos personales
- **Exploración física**: Signos vitales, examen neurológico
- **Historia gineco-obstétrica**: Para pacientes femeninas
- **Notas de evolución**: Seguimiento de cada consulta
- **Prescripciones médicas**: Recetas y tratamientos
- **Estudios**: Adjuntar resultados de laboratorio e imágenes

### Funcionalidades Adicionales
- Gestión de asistentes médicos
- Estadísticas y reportes
- Generación de documentos PDF
- Consentimientos informados
- Tarjetas de identificación de pacientes

## Usuarios del Sistema

SISGEC está diseñado para dos tipos de usuarios principales:

1. **Médicos**: Acceso completo al sistema para gestionar pacientes, citas y expedientes
2. **Asistentes**: Acceso limitado para tareas administrativas y de apoyo

## Metodología de Desarrollo

El proyecto fue desarrollado utilizando:

- **Metodología**: RUP (Rational Unified Process)
- **Modelo de ciclo de vida**: Cascada modificado
- **Requerimientos**: Basados en necesidades de médicos certificados
- **Normatividad**: Cumplimiento con regulaciones mexicanas de expedientes clínicos

## Ventajas del Sistema

1. **Acceso desde cualquier lugar**: Al ser una aplicación web, se puede acceder desde cualquier dispositivo
2. **Respaldo automático**: Los datos se almacenan de forma segura en la base de datos
3. **Búsqueda rápida**: Localización inmediata de expedientes de pacientes
4. **Generación de reportes**: Documentos PDF profesionales listos para imprimir
5. **Escalabilidad**: Puede crecer con las necesidades del consultorio
6. **Open Source**: Código abierto que permite personalización y mejoras

## Casos de Uso Principales

### Para el Médico
1. Registrar nuevo paciente con historia clínica completa
2. Consultar expediente de paciente existente
3. Agregar nota de evolución después de cada consulta
4. Generar receta médica
5. Programar citas médicas
6. Generar reportes y estadísticas

### Para el Asistente
1. Registrar nuevos pacientes
2. Programar y gestionar citas
3. Consultar información básica de pacientes
4. Generar documentos administrativos

## Alcance del Sistema

SISGEC está diseñado específicamente para:

- ✅ Consultorios médicos pequeños (1-3 médicos)
- ✅ Atención médica de nivel uno
- ✅ Especialidades generales y algunas especialidades
- ✅ Gestión de hasta cientos de pacientes

No está diseñado para:

- ❌ Hospitales grandes con múltiples departamentos
- ❌ Gestión de inventario de medicamentos
- ❌ Facturación electrónica compleja
- ❌ Integración con sistemas de laboratorio externos

## Próximos Pasos

Para comenzar a utilizar SISGEC, consulta:

- [Arquitectura del Sistema](02-arquitectura.md)
- [Guía de Instalación](03-instalacion.md)
- [Módulos y Funcionalidades](05-modulos.md)
