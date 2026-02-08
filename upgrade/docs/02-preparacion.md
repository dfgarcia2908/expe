# 2. Preparación para la Actualización

## 1. Backup Completo

### Base de Datos

```bash
# SQLite
cp database/database.sqlite database/backup-$(date +%Y%m%d).sqlite

# MySQL
mysqldump -u usuario -p sisgec > backup-sisgec-$(date +%Y%m%d).sql
```

### Código Fuente

```bash
# Crear backup del proyecto completo
cd /var/www
tar -czf sisgec-backup-$(date +%Y%m%d).tar.gz sisgec/

# O usar Git
cd sisgec
git checkout -b backup-pre-upgrade
git add .
git commit -m "Backup antes de actualización"
git push origin backup-pre-upgrade
```

### Archivos Subidos

```bash
# Backup de estudios y archivos
tar -czf studies-backup-$(date +%Y%m%d).tar.gz public/studies/
tar -czf storage-backup-$(date +%Y%m%d).tar.gz storage/app/
```

## 2. Crear Entorno de Desarrollo

### Clonar Proyecto

```bash
git clone https://github.com/SISGEC/SISGEC.git sisgec-upgrade
cd sisgec-upgrade
git checkout -b upgrade-to-laravel-11
```

### Instalar Dependencias Actuales

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

### Copiar Base de Datos de Producción

```bash
# SQLite
cp /ruta/produccion/database/database.sqlite database/database.sqlite

# MySQL - crear dump y restaurar
mysql -u usuario -p sisgec_dev < backup-sisgec.sql
```

## 3. Verificar Estado Actual

### Versiones Instaladas

```bash
php -v
composer --version
npm --version
php artisan --version
```

### Dependencias

```bash
composer show
npm list --depth=0
```

### Tests Existentes

```bash
php artisan test
# o
vendor/bin/phpunit
```

## 4. Instalar Herramientas

### Laravel Shift (Opcional - Servicio de Pago)

Automatiza gran parte de la actualización:
- https://laravelshift.com/

### Rector (Automatización de Refactoring)

```bash
composer require rector/rector --dev
```

Crear `rector.php`:

```php
<?php

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/app',
        __DIR__ . '/database',
        __DIR__ . '/routes',
    ]);

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_83
    ]);
};
```

### PHPStan (Análisis Estático)

```bash
composer require phpstan/phpstan --dev
```

Crear `phpstan.neon`:

```neon
parameters:
    level: 5
    paths:
        - app
        - database
```

## 5. Documentar Estado Actual

### Crear Inventario

```bash
# Listar controladores
find app/Http/Controllers -name "*.php" > upgrade/inventory-controllers.txt

# Listar modelos
find app -maxdepth 1 -name "*.php" > upgrade/inventory-models.txt

# Listar rutas
php artisan route:list > upgrade/inventory-routes.txt

# Listar migraciones
ls -la database/migrations/ > upgrade/inventory-migrations.txt
```

### Identificar Código Personalizado

Revisar y documentar:
- Helpers personalizados (`app/helpers.php`)
- Middleware personalizado
- Service Providers personalizados
- Comandos Artisan personalizados

## 6. Configurar Git

### Estrategia de Branches

```bash
# Branch principal de actualización
git checkout -b upgrade-to-laravel-11

# Branches por fase
git checkout -b upgrade-php-7.4
git checkout -b upgrade-laravel-6
git checkout -b upgrade-laravel-8
git checkout -b upgrade-php-8.1
git checkout -b upgrade-laravel-9
git checkout -b upgrade-laravel-10
git checkout -b upgrade-php-8.3
git checkout -b upgrade-laravel-11
```

### .gitignore

Verificar que incluya:

```
/node_modules
/public/hot
/public/storage
/storage/*.key
/vendor
.env
.env.backup
.phpunit.result.cache
Homestead.json
Homestead.yaml
npm-debug.log
yarn-error.log
```

## 7. Preparar Entorno de Testing

### Configurar Base de Datos de Testing

```env
# .env.testing
APP_ENV=testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

### Crear Suite de Tests

```bash
php artisan make:test PatientTest
php artisan make:test MedicalAppointmentTest
php artisan make:test AuthenticationTest
```

## 8. Comunicación con Stakeholders

### Notificar a Usuarios

- Planificar ventana de mantenimiento
- Comunicar tiempo estimado
- Preparar plan de rollback

### Documentar Proceso

- Crear log de cambios
- Documentar decisiones técnicas
- Registrar problemas encontrados

## 9. Checklist Pre-Actualización

- [ ] Backup de base de datos creado
- [ ] Backup de código fuente creado
- [ ] Backup de archivos subidos creado
- [ ] Entorno de desarrollo configurado
- [ ] Base de datos de desarrollo poblada
- [ ] Tests actuales ejecutándose
- [ ] Herramientas instaladas
- [ ] Inventario de código creado
- [ ] Estrategia de Git definida
- [ ] Entorno de testing configurado
- [ ] Stakeholders notificados
- [ ] Plan de rollback preparado

## 10. Recursos Necesarios

### Tiempo
- Desarrollador senior: 3-4 semanas
- Testing: 1 semana
- Buffer: 1 semana

### Infraestructura
- Servidor de desarrollo
- Servidor de staging
- Acceso a producción

### Documentación
- Guías de actualización de Laravel
- Changelog de PHP
- Documentación de dependencias

## Siguiente Paso

Una vez completada la preparación, proceder con:
- [Actualización de PHP](03-actualizacion-php.md)
