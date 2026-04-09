# Prompts profesionales para revisar adjuntos y redactar acción de tutela (derecho de petición) en Colombia

## Cómo usar estos prompts
1. Carga todos los archivos adjuntos en el chat/modelo.
2. Usa los prompts en el orden sugerido (auditoría → comparación → redacción → control de calidad).
3. Exige siempre trazabilidad por fuente: cada afirmación debe señalar de qué adjunto salió.
4. Si falta soporte en adjuntos/fuentes aportadas, el modelo debe decir **"No soportado en adjuntos"**.

---

## Prompt 1 — Auditoría estricta de fuentes (sin alucinaciones)

```text
Actúa como abogado/a litigante colombiano/a experto/a en acción de tutela por vulneración del derecho fundamental de petición.

Tarea:
1) Lee TODOS los archivos adjuntos, sin omitir secciones.
2) Identifica y extrae únicamente hechos, fechas, radicados, nombres, pruebas y pretensiones que estén expresamente en los adjuntos.
3) Construye una "Matriz de soporte" con columnas:
   - Afirmación propuesta
   - Fuente exacta (nombre del adjunto y sección/página)
   - Cita breve textual relevante
   - Nivel de soporte: Alto / Medio / Bajo
4) Prohibición total de inventar hechos, normas, sentencias o fechas.
5) Si algo no está en adjuntos o fuentes dadas, marca: "No soportado en adjuntos".

Formato de salida:
- Resumen ejecutivo (máx. 12 viñetas)
- Matriz de soporte
- Lista de vacíos probatorios
- Lista de contradicciones entre adjuntos
```

---

## Prompt 2 — Comparar adjuntos y consolidar "lo mejor" jurídicamente

```text
Con base EXCLUSIVA en los adjuntos ya cargados, compara los documentos entre sí para construir una versión superior, más clara y más sólida jurídicamente.

Objetivo:
- Conservar íntegramente el contenido útil de cada adjunto (sin mutilarlo).
- Unificar redacción, eliminar redundancias y resolver contradicciones.
- Mantener fidelidad total a los hechos probados.

Instrucciones:
1) Crea una tabla comparativa por temas: hechos, pruebas, fundamentos normativos, jurisprudencia, pretensiones, anexos.
2) Señala qué fragmento de cada adjunto es "la mejor versión" y por qué (claridad, precisión, fuerza probatoria, técnica jurídica).
3) Propón texto consolidado por cada tema, citando el adjunto fuente.
4) No uses fuentes externas ni supuestos no documentados.

Entrega:
- Tabla comparativa completa
- Versión consolidada tema por tema
- Riesgos jurídicos detectados
- Recomendaciones de mejora probatoria
```

---

## Prompt 3 — Auditoría de normas y jurisprudencia aplicables (Colombia)

```text
Realiza una auditoría jurídica técnica para acción de tutela por derecho de petición en Colombia.

Alcance:
- Verifica pertinencia y correcta aplicación de normas y jurisprudencia citadas en los adjuntos/fuentes aportadas.
- No agregues jurisprudencia o normas no aportadas, salvo que se solicite expresamente.

Checklist mínimo de auditoría:
1) Constitución Política (núcleo del derecho de petición y tutela).
2) Ley 1755 de 2015 (petición).
3) Decreto 2591 de 1991 (tutela).
4) Reglas de subsidiariedad, inmediatez, legitimación, carencia actual de objeto, hecho superado.
5) Jurisprudencia constitucional citada por los adjuntos: validar que la ratio usada corresponda al caso.

Salida requerida:
- Tabla "Norma/Jurisprudencia vs Uso en el caso"
  - Fuente citada en adjunto
  - Regla aplicable
  - Cómo se conecta con los hechos probados
  - Errores o citas débiles
- Semáforo de solidez argumentativa (Verde/Amarillo/Rojo)
- Ajustes concretos de argumentación, sin inventar fuentes
```

---

## Prompt 4 — Redacción final de acción de tutela (estilo profesional)

```text
Redacta la versión final de la acción de tutela por vulneración del derecho fundamental de petición, usando EXCLUSIVAMENTE el contenido soportado en adjuntos/fuentes entregadas.

Condiciones obligatorias:
- No alucinar: toda afirmación debe tener respaldo en adjuntos.
- Lenguaje técnico-jurídico claro, humano, persuasivo y respetuoso.
- Mantener integridad del contenido de adjuntos (sin mutilar), pero optimizando orden, coherencia y fuerza argumentativa.
- Señalar expresamente cualquier dato faltante con etiqueta: [PENDIENTE DE SOPORTE].

Estructura:
1) Encabezado y competencia
2) Identificación de partes
3) Hechos (cronológicos, numerados)
4) Derecho fundamental vulnerado
5) Fundamentos de derecho (normas y jurisprudencia citadas en adjuntos)
6) Requisitos de procedibilidad
7) Pretensiones
8) Pruebas y anexos
9) Juramento
10) Notificaciones

Además:
- Incluye una "trazabilidad probatoria" al final de cada hecho (Adjunto X, p. Y).
- Genera una versión en tono de litigio estratégico y otra en tono sobrio judicial.
```

---

## Prompt 5 — Ajuste formal según ICONTEC Colombia

```text
Revisa y corrige el documento final para cumplimiento formal de presentación escrita en Colombia bajo criterios de estilo tipo ICONTEC (claridad, estructura, numeración, citas, consistencia formal), sin alterar el fondo jurídico probado.

Verifica:
1) Jerarquía de títulos y numeración.
2) Coherencia terminológica (mismo término para mismo sujeto/hecho).
3) Ortografía, puntuación, sintaxis y legibilidad.
4) Formato homogéneo de fechas, radicados, identificaciones y anexos.
5) Citas y referencias internas uniformes.

Reglas:
- No eliminar contenido sustancial de adjuntos.
- No introducir hechos nuevos.
- Si una corrección exige soporte no disponible, marcar: [REQUIERE SOPORTE DOCUMENTAL].

Salida:
- Versión depurada final
- Lista de cambios formales aplicados
- Lista de pendientes documentales
```

---

## Prompt 6 — Control de calidad final (modo "auditor externo")

```text
Actúa como auditor/a externo/a de calidad jurídica antes de radicar tutela.

Objetivo:
Detectar riesgos de inadmisión, improcedencia o debilidad argumentativa.

Evalúa y puntúa (0 a 5):
- Claridad fáctica
- Suficiencia probatoria
- Coherencia normativa
- Solidez jurisprudencial
- Procedibilidad de tutela
- Precisión de pretensiones
- Calidad formal del escrito

Entrega:
1) Scorecard con puntajes y justificación
2) Top 10 riesgos procesales
3) Top 10 mejoras accionables (priorizadas)
4) Versión "lista para firma" con checklist de radicación

Restricción absoluta:
No usar nada por fuera de adjuntos/fuentes aportadas.
```

---

## Prompt maestro (todo en uno)

```text
Quiero que trabajes como equipo jurídico senior en Colombia para construir una acción de tutela por vulneración del derecho de petición, con estándar de firma litigiosa.

MANDATOS CRÍTICOS:
- Usa SOLO los archivos adjuntos y fuentes que yo aporte.
- CERO alucinación: no inventes hechos, pruebas, normas ni jurisprudencia.
- No mutiles adjuntos: conserva todo lo útil y mejóralo técnicamente.
- En cada afirmación relevante, indica trazabilidad de fuente.
- Si falta soporte, marca: [NO SOPORTADO EN ADJUNTOS].

FASE A — AUDITORÍA:
1) Resume hechos probados y no probados.
2) Detecta contradicciones y vacíos.
3) Construye matriz de soporte probatorio.

FASE B — CONSOLIDACIÓN:
4) Compara todos los adjuntos por temas.
5) Elige lo mejor de cada uno y explica criterio.
6) Integra texto único robusto.

FASE C — AUDITORÍA NORMATIVA/JURISPRUDENCIAL:
7) Verifica uso correcto de normas y jurisprudencia citadas.
8) Evalúa procedibilidad de tutela y riesgos.

FASE D — REDACCIÓN FINAL:
9) Entrega tutela completa con estructura judicial profesional.
10) Incluye versión técnica y versión ciudadana clara.
11) Ajusta forma bajo criterios ICONTEC Colombia (sin alterar hechos).

FASE E — CONTROL FINAL:
12) Score de calidad, riesgos y mejoras priorizadas.
13) Checklist de radicación.

Formato de respuesta:
- Secciones numeradas
- Tablas donde haya auditoría/comparación
- Citas de fuente por afirmación
- Lista de pendientes documentales
```

---

## Recomendación práctica de uso
- Ejecuta el **Prompt 1** y **Prompt 2** primero.
- Si el resultado es sólido, corre **Prompt 3**.
- Luego pide documento final con **Prompt 4** + **Prompt 5**.
- Cierra con **Prompt 6** antes de firmar/radicar.
