# 5. Actualización de Dependencias

## Dependencias PHP (Composer)

### 1. barryvdh/laravel-dompdf

```json
// composer.json
{
    "require": {
        "barryvdh/laravel-dompdf": "^2.0"
    }
}
```

**Cambios**: Compatible, sin breaking changes mayores.

### 2. intervention/image

```json
{
    "require": {
        "intervention/image": "^3.0"
    }
}
```

**Cambios importantes**:

```php
// Antes (v2)
use Intervention\Image\Facades\Image;

Image::make($path)->resize(300, 200)->save();

// Después (v3)
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

$manager = new ImageManager(new Driver());
$image = $manager->read($path);
$image->scale(width: 300)->save();
```

### 3. doctrine/dbal

```json
{
    "require": {
        "doctrine/dbal": "^4.0"
    }
}
```

**Cambios**: Compatible con Laravel 11.

### 4. Actualizar composer.json Completo

```json
{
    "name": "laravel/laravel",
    "description": "The Laravel Framework.",
    "keywords": ["framework", "laravel"],
    "license": "MIT",
    "type": "project",
    "require": {
        "php": "^8.3",
        "barryvdh/laravel-dompdf": "^2.0",
        "doctrine/dbal": "^4.0",
        "intervention/image": "^3.0",
        "laravel/framework": "^11.0",
        "laravel/tinker": "^2.9"
    },
    "require-dev": {
        "fakerphp/faker": "^1.23",
        "laravel/pint": "^1.13",
        "mockery/mockery": "^1.6",
        "nunomaduro/collision": "^8.0",
        "phpunit/phpunit": "^11.0"
    }
}
```

### 5. Ejecutar Actualización

```bash
composer update
```

## Dependencias JavaScript (NPM)

### 1. Laravel Mix → Vite

Laravel 11 usa Vite por defecto. Opciones:

#### Opción A: Mantener Laravel Mix

```json
{
    "devDependencies": {
        "laravel-mix": "^6.0.49"
    }
}
```

#### Opción B: Migrar a Vite (Recomendado)

```bash
npm install --save-dev vite laravel-vite-plugin
```

```javascript
// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/index.scss',
                'resources/js/index.js'
            ],
            refresh: true,
        }),
    ],
});
```

### 2. Actualizar package.json

```json
{
    "name": "sisgec",
    "version": "2.0.0",
    "private": true,
    "type": "module",
    "scripts": {
        "dev": "vite",
        "build": "vite build"
    },
    "devDependencies": {
        "@vitejs/plugin-vue": "^5.0",
        "axios": "^1.6",
        "laravel-vite-plugin": "^1.0",
        "sass": "^1.70",
        "vite": "^5.0"
    },
    "dependencies": {
        "bootstrap": "^5.3",
        "bootstrap-datepicker": "^1.10",
        "chart.js": "^4.4",
        "datatables.net-dt": "^2.0",
        "dropzone": "^6.0",
        "fullcalendar": "^6.1",
        "jquery": "^3.7",
        "moment": "^2.30",
        "sweetalert2": "^11.10",
        "tippy.js": "^6.3",
        "vue": "^3.4"
    }
}
```

### 3. Actualizar Vistas para Vite

```blade
{{-- Antes (Laravel Mix) --}}
<link rel="stylesheet" href="{{ asset('css/sisgec.app.css') }}">
<script src="{{ asset('js/sisgec.app.js') }}"></script>

{{-- Después (Vite) --}}
@vite(['resources/sass/index.scss', 'resources/js/index.js'])
```

### 4. Ejecutar Actualización

```bash
npm install
npm run build
```

## Actualizar Vue.js 2 → 3 (Opcional)

### Cambios Principales

```javascript
// Antes (Vue 2)
import Vue from 'vue';

new Vue({
    el: '#app',
    data: {
        message: 'Hello'
    }
});

// Después (Vue 3)
import { createApp } from 'vue';

createApp({
    data() {
        return {
            message: 'Hello'
        }
    }
}).mount('#app');
```

### Migration Build

```bash
npm install @vue/compat
```

```javascript
// vite.config.js
export default {
    resolve: {
        alias: {
            vue: '@vue/compat'
        }
    }
}
```

## Actualizar Bootstrap 4 → 5

### Cambios Principales

```html
<!-- Antes (Bootstrap 4) -->
<div class="form-group">
    <label>Nombre</label>
    <input type="text" class="form-control">
</div>

<!-- Después (Bootstrap 5) -->
<div class="mb-3">
    <label class="form-label">Nombre</label>
    <input type="text" class="form-control">
</div>
```

### Clases Renombradas

- `ml-*` → `ms-*` (margin-left)
- `mr-*` → `me-*` (margin-right)
- `pl-*` → `ps-*` (padding-left)
- `pr-*` → `pe-*` (padding-right)
- `form-group` → `mb-3`
- `custom-select` → `form-select`

## Script de Actualización

```bash
#!/bin/bash
# update-dependencies.sh

echo "=== Actualizando Dependencias ==="

# Backup
cp composer.json composer.json.backup
cp package.json package.json.backup

# Actualizar Composer
echo "Actualizando dependencias PHP..."
composer update

# Actualizar NPM
echo "Actualizando dependencias JavaScript..."
npm update

# Auditar seguridad
echo "Auditando seguridad..."
composer audit
npm audit

echo "=== Actualización completada ==="
```

## Verificación de Compatibilidad

```bash
# Verificar versiones instaladas
composer show
npm list --depth=0

# Verificar vulnerabilidades
composer audit
npm audit

# Corregir vulnerabilidades
npm audit fix
```

## Dependencias Deprecadas

### Remover

```json
// Remover de composer.json
"fideloper/proxy": "^4.0"  // Incluido en Laravel 11

// Remover de package.json
"laravel-mix": "^4.0"  // Reemplazar con Vite
```

### Reemplazar

```bash
# fideloper/proxy → TrustedProxies middleware (incluido)
# laravel-mix → Vite
```

## Checklist de Dependencias

- [ ] composer.json actualizado
- [ ] Dependencias PHP actualizadas
- [ ] composer audit sin vulnerabilidades
- [ ] package.json actualizado
- [ ] Dependencias JavaScript actualizadas
- [ ] npm audit sin vulnerabilidades
- [ ] Vite configurado (o Mix actualizado)
- [ ] Assets compilando correctamente
- [ ] Vue 3 migrado (si aplica)
- [ ] Bootstrap 5 migrado (si aplica)
- [ ] Tests pasando con nuevas dependencias

## Siguiente Paso

Proceder con:
- [Plan de Testing](06-testing.md)
