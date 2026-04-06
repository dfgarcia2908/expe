---
name: revision-juridica-claude
description: Diseñar, adaptar y auditar prompts para que Claude revise escritos jurídicos con lenguaje técnico-formal, confronte normas y jurisprudencia vigentes, exija verificación de actualidad por fecha y jurisdicción, y compruebe cumplimiento de formato académico-técnico (ICONTEC/ISO). Usar cuando se redacten demandas, conceptos, memoriales, contratos, recursos o cualquier pieza legal que requiera control de rigor normativo y metodológico.
---

# Revisión jurídica para Claude

## Objetivo

Generar prompts reutilizables para revisión legal exhaustiva en Claude, minimizando omisiones de fondo, forma, vigencia normativa y coherencia jurisprudencial.

## Flujo de trabajo

1. **Identificar contexto mínimo obligatorio**
   - Precisar jurisdicción (país + rama del derecho).
   - Precisar tipo de escrito (demanda, concepto, recurso, contrato, etc.).
   - Precisar fecha de corte de vigencia (día/mes/año).
   - Precisar estándar de formato requerido (ICONTEC e ISO aplicables).

2. **Seleccionar plantilla base**
   - Cargar la plantilla adecuada desde `references/prompts.md`.
   - Elegir entre: revisión integral, contraste normativo-jurisprudencial, control de forma ICONTEC/ISO o versión de “auditor severo”.

3. **Inyectar datos del caso**
   - Reemplazar variables: `[JURISDICCIÓN]`, `[MATERIA]`, `[FECHA_CORTE]`, `[TIPO_DOCUMENTO]`, `[TEXTO]`.
   - Añadir hechos relevantes y pretensiones.

4. **Exigir salida verificable**
   - Pedir tabla de hallazgos con: error, riesgo legal, corrección y fuente.
   - Exigir distinción entre norma vigente, derogada, modificada o suspendida.
   - Exigir fecha exacta de cada fuente (norma y sentencia).

5. **Cerrar con control de calidad**
   - Solicitar checklist final “sin omisiones críticas”.
   - Solicitar redacción final completamente jurídica y consistente.

## Reglas de redacción del prompt

- Usar tono imperativo y criterios medibles.
- Prohibir respuestas genéricas (“depende”, “podría ser”) sin soporte legal.
- Ordenar a Claude señalar incertidumbres explícitamente.
- Exigir trazabilidad: cada afirmación jurídica debe vincularse a fuente normativa o jurisprudencial.
- Priorizar precisión técnica sobre brevedad.

## Salida mínima que debe producir Claude

- Diagnóstico ejecutivo (3 a 7 hallazgos críticos).
- Matriz de confrontación norma-jurisprudencia-hecho.
- Correcciones puntuales de redacción jurídica.
- Validación de vigencia por fecha de corte.
- Validación formal ICONTEC/ISO con lista de incumplimientos.
- Versión corregida del escrito.

## Recursos

- Plantillas listas para usar: `references/prompts.md`.
