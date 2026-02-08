# 7. Plan de Rollback

## Estrategia de Rollback

### Principios

1. **Siempre tener un plan B**
2. **Backups verificados antes de iniciar**
3. **Capacidad de revertir en < 30 minutos**
4. **Documentar cada paso**

## Preparación Pre-Actualización

### 1. Backups Completos

```bash
#!/bin/bash
# backup-complete.sh

BACKUP_DIR="/var/backups/sisgec"
DATE=$(date +%Y%m%d_%H%M%S)

# Crear directorio
mkdir -p $BACKUP_DIR

# Backup de código
tar -czf $BACKUP_DIR/code_$DATE.tar.gz /var/www/sisgec

# Backup de base de datos
mysqldump -u sisgec_user -p sisgec > $BACKUP_DIR/db_$DATE.sql

# Backup de archivos subidos
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/sisgec/public/studies

# Backup de configuración
cp /var/www/sisgec/.env $BACKUP_DIR/env_$DATE

echo "Backup completado: $DATE"
```

### 2. Verificar Backups

```bash
# Verificar integridad
tar -tzf $BACKUP_DIR/code_$DATE.tar.gz > /dev/null
echo "Código: OK"

# Verificar SQL
mysql -u sisgec_user -p sisgec_test < $BACKUP_DIR/db_$DATE.sql
echo "Base de datos: OK"
```

### 3. Documentar Estado Actual

```bash
# Versiones
php -v > pre-upgrade-versions.txt
php artisan --version >> pre-upgrade-versions.txt
composer show >> pre-upgrade-versions.txt

# Configuración
cp .env pre-upgrade.env
php artisan route:list > pre-upgrade-routes.txt
```

## Procedimientos de Rollback

### Rollback Nivel 1: Código

Si la actualización falla durante desarrollo:

```bash
# Revertir con Git
git reset --hard HEAD
git clean -fd

# O restaurar desde backup
cd /var/www
rm -rf sisgec
tar -xzf /var/backups/sisgec/code_YYYYMMDD_HHMMSS.tar.gz
```

### Rollback Nivel 2: Base de Datos

Si hay problemas con migraciones:

```bash
# Rollback de migraciones
php artisan migrate:rollback

# O restaurar desde backup
mysql -u sisgec_user -p sisgec < /var/backups/sisgec/db_YYYYMMDD_HHMMSS.sql
```

### Rollback Nivel 3: Completo

Si la actualización falla en producción:

```bash
#!/bin/bash
# rollback-complete.sh

BACKUP_DATE="20240101_120000"  # Ajustar fecha
BACKUP_DIR="/var/backups/sisgec"

echo "=== Iniciando Rollback Completo ==="

# Modo mantenimiento
cd /var/www/sisgec
php artisan down

# Restaurar código
cd /var/www
rm -rf sisgec
tar -xzf $BACKUP_DIR/code_$BACKUP_DATE.tar.gz

# Restaurar base de datos
mysql -u sisgec_user -p sisgec < $BACKUP_DIR/db_$BACKUP_DATE.sql

# Restaurar archivos
cd /var/www/sisgec/public
rm -rf studies
tar -xzf $BACKUP_DIR/files_$BACKUP_DATE.tar.gz

# Restaurar configuración
cp $BACKUP_DIR/env_$BACKUP_DATE /var/www/sisgec/.env

# Permisos
chown -R www-data:www-data /var/www/sisgec
chmod -R 755 /var/www/sisgec
chmod -R 775 /var/www/sisgec/storage

# Limpiar caché
cd /var/www/sisgec
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Salir de mantenimiento
php artisan up

echo "=== Rollback Completado ==="
```

## Rollback por Fase

### Fase 1: Rollback PHP

```bash
# Cambiar versión de PHP en Nginx
sudo nano /etc/nginx/sites-available/sisgec

# Cambiar:
# fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
# Por:
# fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;

sudo systemctl reload nginx

# Actualizar composer.json
composer require "php:^7.4"
composer install
```

### Fase 2: Rollback Laravel

```bash
# Revertir versión en composer.json
composer require "laravel/framework:5.7.*"
composer install

# Revertir cambios de código
git checkout backup-pre-upgrade -- app/
git checkout backup-pre-upgrade -- routes/
git checkout backup-pre-upgrade -- database/

# Limpiar
php artisan config:clear
php artisan cache:clear
```

### Fase 3: Rollback Dependencias

```bash
# Restaurar composer.json original
git checkout backup-pre-upgrade -- composer.json
composer install

# Restaurar package.json original
git checkout backup-pre-upgrade -- package.json
npm install
npm run production
```

## Puntos de No Retorno

### Identificar Cambios Irreversibles

1. **Migraciones destructivas**: DROP COLUMN, DROP TABLE
2. **Cambios de datos**: Transformaciones irreversibles
3. **Eliminación de archivos**: Sin backup

### Mitigar Riesgos

```php
// En migraciones, siempre incluir down()
public function up()
{
    Schema::table('patients', function (Blueprint $table) {
        $table->string('new_field');
    });
}

public function down()
{
    Schema::table('patients', function (Blueprint $table) {
        $table->dropColumn('new_field');
    });
}
```

## Verificación Post-Rollback

```bash
#!/bin/bash
# verify-rollback.sh

echo "=== Verificando Rollback ==="

# Verificar versiones
echo "PHP Version:"
php -v

echo "Laravel Version:"
php artisan --version

# Verificar aplicación
echo "Verificando aplicación..."
curl -I http://localhost

# Verificar base de datos
echo "Verificando base de datos..."
php artisan migrate:status

# Verificar login
echo "Verificando autenticación..."
# Ejecutar test de login

echo "=== Verificación Completada ==="
```

## Comunicación Durante Rollback

### Template de Notificación

```
ASUNTO: SISGEC - Rollback en Progreso

Estimados usuarios,

Debido a problemas técnicos durante la actualización, 
estamos revirtiendo el sistema a la versión anterior.

Tiempo estimado: 30 minutos
Estado: En progreso

Actualizaremos cuando el sistema esté disponible.

Equipo SISGEC
```

## Checklist de Rollback

### Pre-Rollback
- [ ] Backups verificados
- [ ] Usuarios notificados
- [ ] Modo mantenimiento activado
- [ ] Equipo técnico disponible

### Durante Rollback
- [ ] Código restaurado
- [ ] Base de datos restaurada
- [ ] Archivos restaurados
- [ ] Configuración restaurada
- [ ] Permisos verificados
- [ ] Caché limpiado

### Post-Rollback
- [ ] Aplicación funcionando
- [ ] Tests pasando
- [ ] Login funcional
- [ ] Funcionalidades críticas verificadas
- [ ] Usuarios notificados
- [ ] Modo mantenimiento desactivado
- [ ] Incidente documentado

## Lecciones Aprendidas

### Documentar

Después de cada rollback, documentar:

1. **Causa del problema**
2. **Tiempo de detección**
3. **Tiempo de rollback**
4. **Impacto en usuarios**
5. **Acciones correctivas**

### Template

```markdown
# Incidente: Rollback Actualización SISGEC

## Fecha
YYYY-MM-DD HH:MM

## Causa
[Descripción del problema]

## Impacto
- Usuarios afectados: X
- Tiempo de inactividad: X minutos
- Funcionalidades afectadas: [lista]

## Acciones Tomadas
1. [Acción 1]
2. [Acción 2]

## Tiempo de Resolución
- Detección: X minutos
- Rollback: X minutos
- Verificación: X minutos
- Total: X minutos

## Prevención Futura
- [Medida 1]
- [Medida 2]

## Responsable
[Nombre]
```

## Mejores Prácticas

1. **Probar rollback antes de actualizar**
2. **Mantener backups por 30 días**
3. **Automatizar proceso de rollback**
4. **Documentar cada paso**
5. **Tener equipo disponible durante actualización**
6. **Realizar actualizaciones en horarios de bajo tráfico**
7. **Mantener comunicación constante**

## Scripts de Emergencia

### Rollback Rápido (< 5 minutos)

```bash
#!/bin/bash
# emergency-rollback.sh

# Usar último backup
LATEST_BACKUP=$(ls -t /var/backups/sisgec/code_*.tar.gz | head -1)
LATEST_DB=$(ls -t /var/backups/sisgec/db_*.sql | head -1)

cd /var/www
rm -rf sisgec
tar -xzf $LATEST_BACKUP

mysql -u sisgec_user -p sisgec < $LATEST_DB

cd sisgec
php artisan up

echo "Rollback de emergencia completado"
```

## Contactos de Emergencia

```
Desarrollador Principal: [Nombre] - [Teléfono]
DBA: [Nombre] - [Teléfono]
DevOps: [Nombre] - [Teléfono]
Gerente de Proyecto: [Nombre] - [Teléfono]
```

## Conclusión

Un plan de rollback bien ejecutado minimiza el tiempo de inactividad y el impacto en usuarios. Siempre es mejor prevenir que lamentar.
