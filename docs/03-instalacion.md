# 3. Guía de Instalación

## Requisitos del Sistema

### Software Requerido
- PHP >= 7.1.3
- Composer >= 1.7.2
- Node.js >= 8.11.1
- npm >= 6.1.10
- Git >= 2.10.2

### Extensiones PHP
- OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON

## Instalación en Desarrollo

### 1. Clonar Repositorio

```bash
cd /ruta/desarrollo
git clone https://github.com/SISGEC/SISGEC sisgec
cd sisgec
```

### 2. Instalar Dependencias

```bash
composer install
npm install
```

### 3. Configurar Entorno

```bash
cp .env.example .env
```

Editar `.env`:

```env
APP_NAME=SISGEC
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_DATABASE=database.sqlite
```

### 4. Generar Clave

```bash
php artisan key:generate
```

### 5. Configurar Base de Datos

#### SQLite (Recomendado)

```bash
touch database/database.sqlite
php artisan migrate
php artisan db:seed
```

#### MySQL

```sql
CREATE DATABASE sisgec;
```

Configurar `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sisgec
DB_USERNAME=usuario
DB_PASSWORD=contraseña
```

```bash
php artisan migrate
php artisan db:seed
```

### 6. Compilar Assets

```bash
npm run dev
```

### 7. Iniciar Servidor

```bash
php artisan serve
```

Acceder a: `http://localhost:8000`

### 8. Credenciales por Defecto

```
Email: admin@nidiasoft.com
Password: admin
```

**⚠️ Cambiar inmediatamente después del primer acceso**

## Instalación en Producción

### 1. Preparar Servidor

- Ubuntu 20.04 LTS
- PHP 7.4+
- Nginx/Apache
- MySQL/MariaDB
- Composer, Node.js

### 2. Clonar y Configurar

```bash
cd /var/www
git clone https://github.com/SISGEC/SISGEC.git sisgec
cd sisgec
composer install --optimize-autoloader --no-dev
npm install --production
npm run production
```

### 3. Permisos

```bash
sudo chown -R www-data:www-data /var/www/sisgec
sudo chmod -R 755 /var/www/sisgec
sudo chmod -R 775 /var/www/sisgec/storage
sudo chmod -R 775 /var/www/sisgec/bootstrap/cache
```

### 4. Configurar .env

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com

DB_CONNECTION=mysql
DB_DATABASE=sisgec_prod
DB_USERNAME=usuario
DB_PASSWORD=contraseña_segura
```

### 5. Optimizar

```bash
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 6. Configurar Nginx

```nginx
server {
    listen 80;
    server_name tu-dominio.com;
    root /var/www/sisgec/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 7. SSL con Let's Encrypt

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d tu-dominio.com
```

## Verificación

- [ ] Aplicación carga correctamente
- [ ] Login funciona
- [ ] Crear paciente funciona
- [ ] PDFs se generan
- [ ] No hay errores en logs

## Solución de Problemas

### Error: "No encryption key"

```bash
php artisan key:generate
```

### Error: Permisos en storage

```bash
sudo chmod -R 775 storage/
sudo chown -R www-data:www-data storage/
```

### Error: Assets no cargan

```bash
npm run production
php artisan cache:clear
```

## Actualización

```bash
git pull origin master
composer install --optimize-autoloader --no-dev
npm install --production
npm run production
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
