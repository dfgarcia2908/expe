# Plantillas de prompts para Claude (revisión jurídica)

Usar estas plantillas sustituyendo variables entre corchetes.

## 1) Prompt maestro de revisión jurídica integral

```text
Actúa como revisor jurídico senior especializado en [MATERIA] en [JURISDICCIÓN].

Objetivo: auditar de forma exhaustiva el siguiente [TIPO_DOCUMENTO], garantizando lenguaje 100% jurídico, confrontación de normas y jurisprudencia vigentes, y cumplimiento formal de normas ICONTEC/ISO aplicables a escritos técnico-jurídicos.

Fecha de corte para vigencia normativa y jurisprudencial: [FECHA_CORTE].

Texto a revisar:
[TEXTO]

Instrucciones obligatorias:
1) Evalúa precisión técnica del lenguaje y elimina coloquialismos, ambigüedades y afirmaciones sin sustento.
2) Confronta cada argumento con normas jurídicas vigentes (constitución, leyes, decretos, reglamentos y/o normas sectoriales pertinentes).
3) Confronta con jurisprudencia aplicable y vigente; indica tribunal/órgano, radicado o identificador, fecha y regla jurisprudencial útil.
4) Identifica antinomias, vacíos, desactualizaciones, citas incompletas o fuentes derogadas/modificadas.
5) Verifica estructura formal, citación, numeración, referencias, tablas, anexos y demás exigencias de ICONTEC/ISO aplicables al documento.
6) No omitas detalles mínimos: revisa coherencia interna, técnica argumentativa, pretensiones, competencia, legitimación, temporalidad y carga probatoria cuando aplique.

Formato de salida obligatorio:
A. Diagnóstico ejecutivo (máximo 10 hallazgos críticos).
B. Tabla de hallazgos con columnas:
   - Nº
   - Fragmento observado
   - Problema detectado
   - Riesgo jurídico
   - Norma/Jurisprudencia de contraste
   - Estado de vigencia (vigente, modificada, derogada, suspendida)
   - Corrección propuesta
C. Checklist de cumplimiento ICONTEC/ISO (Cumple / No cumple / Parcial).
D. Versión corregida integral del texto en lenguaje estrictamente jurídico.
E. Lista de fuentes usadas con fecha exacta y nota de vigencia.

Si hay incertidumbre sobre vigencia o aplicabilidad, decláralo expresamente y propone cómo verificarla.
```

## 2) Prompt de confrontación norma vs jurisprudencia

```text
Actúa como auditor de consistencia jurídica.

Con base en [JURISDICCIÓN], [MATERIA] y fecha de corte [FECHA_CORTE], confronta el texto con:
- Bloque normativo aplicable.
- Jurisprudencia relevante y vigente.

Texto:
[TEXTO]

Entrega:
1) Mapa de problemas por párrafo.
2) Matriz comparativa: argumento del escrito / norma aplicable / regla jurisprudencial / compatibilidad / ajuste recomendado.
3) Riesgo procesal de mantener cada error (alto/medio/bajo).
4) Redacción alternativa jurídicamente sólida para cada párrafo cuestionado.
```

## 3) Prompt de control formal ICONTEC/ISO para escrito jurídico

```text
Actúa como revisor técnico de normalización documental.

Verifica el siguiente [TIPO_DOCUMENTO] conforme a lineamientos ICONTEC e ISO que resulten aplicables al género documental y a [JURISDICCIÓN], con fecha de corte [FECHA_CORTE].

Texto:
[TEXTO]

Valida de forma exhaustiva:
- Portada, paginación, jerarquía de títulos, numeración.
- Citas textuales y parafraseadas, notas y referencias.
- Tablas, figuras, anexos, índices, abreviaturas y siglas.
- Coherencia terminológica y estilo técnico-jurídico.

Devuelve:
A) Lista de incumplimientos (crítico/mayor/menor).
B) Regla ICONTEC/ISO afectada por cada incumplimiento.
C) Corrección exacta propuesta.
D) Versión final normalizada.
```

## 4) Prompt de “revisor implacable” (sin pasar un detalle)

```text
Asume el rol de revisor jurídico implacable. No pases por alto ningún detalle sustantivo ni formal.

Marco de revisión:
- Jurisdicción: [JURISDICCIÓN]
- Materia: [MATERIA]
- Tipo documental: [TIPO_DOCUMENTO]
- Fecha de corte: [FECHA_CORTE]

Documento:
[TEXTO]

Protocolo obligatorio:
1) Detecta cada debilidad argumentativa y defecto de técnica jurídica.
2) Señala cualquier cita incompleta, desactualizada o no verificable.
3) Contrasta obligatoriedad y vigencia de cada soporte legal.
4) Marca inconsistencias fácticas, de competencia, de procedimiento y de pretensión.
5) Corrige sintaxis jurídica, precisión conceptual y estructura lógica.
6) Entrega versión final blindada, lista para radicación.

Formato:
- Resumen de riesgos críticos.
- Lista exhaustiva de observaciones numeradas.
- Corrección línea por línea.
- Texto final consolidado.
```

## 5) Prompt breve para iteraciones rápidas

```text
Revisa este texto jurídico en [JURISDICCIÓN] ([MATERIA]) con fecha de corte [FECHA_CORTE].
Exige lenguaje técnico-jurídico estricto, confronta normas y jurisprudencia vigentes, valida ICONTEC/ISO, y entrega:
1) errores,
2) fundamento,
3) corrección,
4) versión final.

Texto:
[TEXTO]
```

## Recomendación de uso

- Iniciar con el **Prompt maestro**.
- Ejecutar segunda pasada con **revisor implacable**.
- Cerrar con **control formal ICONTEC/ISO**.
