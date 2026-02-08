# 10. Guía de Despliegue en Producción

## Preparación del Servidor

### Requisitos del Servidor

#### Hardware Mínimo
- CPU: 2 cores
- RAM: 2 GB
- Disco: 20 GB SSD
- Ancho de banda: 100 Mbps

#### Hardware Recomendado
- CPU: 4 cores
- RAM: 4 GB
- Disco: 50 GB SSD
- Ancho de banda: 1 Gbps

#### Software
- Ubuntu 20.04 LTS o CentOS 8
- PHP 7.4 o superior
- Nginx o Apache
- MySQL 8.0 o MariaDB 10.5
- Composer
- Node.js 14.x LTS
- Git
- Certbot (para SSL)

## Instalación en Ubuntu 20.04

### 1. Actualizar Sistema

```bash
sudo apt update
sudo apt upgrade -y
```

### 2. Instalar PHP y Extensiones

```bash
sudo apt install -y php7.4 php7.4-fpm php7.4-mysql php7.4-xml \
    php7.4-mbstring php7.4-curl php7.4-zip php7.4-gd \
    php7.4-sqlite3 php7.4-bcmath php7.4-json
```

### 3. Instalar Nginx

```bash
sudo apt install -y nginx
sudo systemctl enable nginx
sudo systemctl start nginx
```

### 4. Instalar MySQL

```bash
sudo apt install -y mysql-server
sudo mysql_secure_installation
```

Crear base de datos:

```sql
CREATE DATABASE sisgec CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'sisgec_user'@'localhost' IDENTIFIED BY 'contraseña_segura';
GRANT ALL PRIVILEGES ON sisgec.* TO 'sisgec_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 5. Instalar Composer

```bash
cd ~
curl -sS https://getcomposer.org/installer -o composer-setup.php
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
```

### 6. Instalar Node.js y npm

```bash
curl -fsSL https://deb.nodesource.com/setup_14.x | sudo -E bash -
sudo apt install -y nodejs
```

## Desplegar la Aplicación

### 1. Clonar Repositorio

```bash
cd /var/www
sudo git clone https://github.com/SISGEC/SISGEC.git sisgec
cd sisgec
```

### 2. Configurar Permisos

```bash
sudo chown -R www-data:www-data /var/www/sisgec
sudo chmod -R 755 /var/www/sisgec
sudo chmod -R 775 /var/www/sisgec/storage
sudo chmod -R 775 /var/www/sisgec/bootstrap/cache
```

### 3. Instalar Dependencias

```bash
# Dependencias PHP
sudo -u www-data composer install --optimize-autoloader --no-dev

# Dependencias JavaScript
sudo -u www-data npm install --production
```

### 4. Configurar Entorno

```bash
sudo -u www-data cp .env.example .env
sudo -u www-data nano .env
```

Configuración de producción:

```env
APP_NAME=SISGEC
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://tu-dominio.com
APP_DEMO_MODE=false

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sisgec
DB_USERNAME=sisgec_user
DB_PASSWORD=contraseña_segura

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DRIVER=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-contraseña-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 5. Generar Clave y Migrar

```bash
sudo -u www-data php artisan key:generate
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan db:seed --force
```

### 6. Compilar Assets

```bash
sudo -u www-data npm run production
```

### 7. Optimizar Aplicación

```bash
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
```

## Configurar Nginx

### Crear Archivo de Configuración

```bash
sudo nano /etc/nginx/sites-available/sisgec
```

Contenido:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name tu-dominio.com www.tu-dominio.com;
    root /var/www/sisgec/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Limitar tamaño de subida
    client_max_body_size 20M;

    # Logs
    access_log /var/log/nginx/sisgec-access.log;
    error_log /var/log/nginx/sisgec-error.log;
}
```

### Activar Sitio

```bash
sudo ln -s /etc/nginx/sites-available/sisgec /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

## Configurar SSL con Let's Encrypt

### Instalar Certbot

```bash
sudo apt install -y certbot python3-certbot-nginx
```

### Obtener Certificado

```bash
sudo certbot --nginx -d tu-dominio.com -d www.tu-dominio.com
```

### Renovación Automática

```bash
# Verificar renovación automática
sudo certbot renew --dry-run

# Cron job (ya configurado automáticamente)
sudo systemctl status certbot.timer
```

## Configurar Firewall

```bash
# Habilitar UFW
sudo ufw enable

# Permitir SSH
sudo ufw allow 22/tcp

# Permitir HTTP y HTTPS
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

# Ver estado
sudo ufw status
```

## Configurar Backups Automáticos

### Script de Backup

```bash
sudo nano /usr/local/bin/sisgec-backup.sh
```

Contenido:

```bash
#!/bin/bash

# Configuración
BACKUP_DIR="/var/backups/sisgec"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="sisgec"
DB_USER="sisgec_user"
DB_PASS="contraseña_segura"
APP_DIR="/var/www/sisgec"

# Crear directorio de backups
mkdir -p $BACKUP_DIR

# Backup de base de datos
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/db_$DATE.sql.gz

# Backup de archivos subidos
tar -czf $BACKUP_DIR/files_$DATE.tar.gz $APP_DIR/public/studies $APP_DIR/storage/app

# Eliminar backups antiguos (más de 30 días)
find $BACKUP_DIR -type f -mtime +30 -delete

echo "Backup completado: $DATE"
```

Dar permisos:

```bash
sudo chmod +x /usr/local/bin/sisgec-backup.sh
```

### Cron Job para Backups

```bash
sudo crontab -e
```

Agregar:

```cron
# Backup diario a las 2 AM
0 2 * * * /usr/local/bin/sisgec-backup.sh >> /var/log/sisgec-backup.log 2>&1
```

## Monitoreo y Logs

### Ver Logs de Nginx

```bash
# Access log
sudo tail -f /var/log/nginx/sisgec-access.log

# Error log
sudo tail -f /var/log/nginx/sisgec-error.log
```

### Ver Logs de Laravel

```bash
sudo tail -f /var/www/sisgec/storage/logs/laravel.log
```

### Configurar Logrotate

```bash
sudo nano /etc/logrotate.d/sisgec
```

Contenido:

```
/var/www/sisgec/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
}
```

## Optimización de Rendimiento

### PHP-FPM

```bash
sudo nano /etc/php/7.4/fpm/pool.d/www.conf
```

Ajustar:

```ini
pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 500
```

Reiniciar:

```bash
sudo systemctl restart php7.4-fpm
```

### OPcache

```bash
sudo nano /etc/php/7.4/fpm/conf.d/10-opcache.ini
```

Configurar:

```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

### MySQL

```bash
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
```

Optimizar:

```ini
[mysqld]
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
max_connections = 200
query_cache_size = 64M
```

Reiniciar:

```bash
sudo systemctl restart mysql
```

## Mantenimiento

### Actualizar Aplicación

```bash
cd /var/www/sisgec

# Modo mantenimiento
sudo -u www-data php artisan down

# Actualizar código
sudo -u www-data git pull origin master

# Actualizar dependencias
sudo -u www-data composer install --optimize-autoloader --no-dev
sudo -u www-data npm install --production
sudo -u www-data npm run production

# Migrar base de datos
sudo -u www-data php artisan migrate --force

# Limpiar y optimizar
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

# Salir de mantenimiento
sudo -u www-data php artisan up
```

### Verificar Estado del Sistema

```bash
# Estado de servicios
sudo systemctl status nginx
sudo systemctl status php7.4-fpm
sudo systemctl status mysql

# Uso de disco
df -h

# Uso de memoria
free -h

# Procesos
top
```

## Seguridad Adicional

### Fail2Ban

```bash
sudo apt install -y fail2ban
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

Configurar:

```bash
sudo nano /etc/fail2ban/jail.local
```

```ini
[nginx-http-auth]
enabled = true

[nginx-noscript]
enabled = true

[nginx-badbots]
enabled = true
```

### Cambiar Credenciales por Defecto

```bash
cd /var/www/sisgec
sudo -u www-data php artisan tinker
```

```php
$user = App\User::where('email', 'admin@nidiasoft.com')->first();
$user->email = 'tu-email@dominio.com';
$user->password = Hash::make('nueva_contraseña_segura');
$user->save();
exit
```

## Checklist de Despliegue

- [ ] Servidor configurado con requisitos mínimos
- [ ] PHP y extensiones instaladas
- [ ] Nginx/Apache configurado
- [ ] MySQL instalado y configurado
- [ ] Aplicación clonada y dependencias instaladas
- [ ] Archivo .env configurado correctamente
- [ ] Base de datos migrada y poblada
- [ ] Assets compilados para producción
- [ ] Permisos de archivos configurados
- [ ] SSL/TLS configurado
- [ ] Firewall configurado
- [ ] Backups automáticos configurados
- [ ] Logs configurados
- [ ] Monitoreo implementado
- [ ] Credenciales por defecto cambiadas
- [ ] Pruebas de funcionalidad realizadas
- [ ] Documentación actualizada

## Solución de Problemas en Producción

### Error 500

```bash
# Ver logs
sudo tail -f /var/www/sisgec/storage/logs/laravel.log
sudo tail -f /var/log/nginx/sisgec-error.log

# Verificar permisos
sudo chown -R www-data:www-data /var/www/sisgec/storage
sudo chmod -R 775 /var/www/sisgec/storage
```

### Error de Base de Datos

```bash
# Verificar conexión
mysql -u sisgec_user -p sisgec

# Verificar configuración
sudo -u www-data php artisan config:clear
```

### Assets no se cargan

```bash
# Recompilar
cd /var/www/sisgec
sudo -u www-data npm run production

# Limpiar caché
sudo -u www-data php artisan cache:clear
```

## Contacto y Soporte

Para problemas de despliegue:
- GitHub Issues: https://github.com/SISGEC/SISGEC/issues
- Email: jesus@yosoydev.net
